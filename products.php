<?php
/*
 * Copyright (C) 2013		 		Oscim					       <dom@oscim.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *	\file       htdocs/inventaire/fiche.php
 *	\brief      File for inventaire
 *	\author     Oscim <mail oscim@users.sourceforge.net>
 *	\version    $Id: inventaire.php,v 1.56 2013/15/06 15:28:01 oscim Exp $
 *  \note
 */


$res = 0;
if (!$res && file_exists("../main.inc.php"))
    $res = @include("../main.inc.php");
if (!$res && file_exists("../../main.inc.php"))
    $res = @include("../../main.inc.php");
if (!$res && file_exists("../../../main.inc.php"))
    $res = @include("../../../main.inc.php");
if (!$res && file_exists("../../../../main.inc.php"))
    $res = @include("../../../../main.inc.php");
if (!$res && file_exists("../../../dolibarr/htdocs/main.inc.php"))
    $res = @include("../../../dolibarr/htdocs/main.inc.php");     // Used on dev env only
if (!$res && file_exists("../../../../dolibarr/htdocs/main.inc.php"))
    $res = @include("../../../../dolibarr/htdocs/main.inc.php");   // Used on dev env only
if (!$res && file_exists("../../../../../dolibarr/htdocs/main.inc.php"))
    $res = @include("../../../../../dolibarr/htdocs/main.inc.php");   // Used on dev env only
if (!$res)
    die("Include of main fails");
    
    
require_once(DOL_DOCUMENT_ROOT."/core/class/html.formfile.class.php");
// dol_include_once('/product/sock/class/entrepot.class.php');



dol_include_once('/inventaire/class/inventaire.class.php');
dol_include_once('/inventaire/class/snapshot.inventaire.class.php');
dol_include_once('/inventaire/class/entrepot.inventaire.class.php');
dol_include_once('/inventaire/class/inventaire.ligne.entrepot.class.php');
dol_include_once('/inventaire/class/inventaire.app.class.php');

dol_include_once('/inventaire/core/lib/inventaire.lib.php');


// oscimmods Required
dol_include_once('/oscimmods/class/Datatable.class.php');

$langs->load("inventaire");

$action = GETPOST('action');
$entrepotid= GETPOST('entrepotid'); 




$object = new Inventaire($db);
$snaptshot = new Snapshot($db);
$Entrepot = new InventaireEntrepot($db);


$Datatable = new Datatable('/inventaire/products', GETPOST('sSearch') , $conf->liste_limit );

$entrepot = $Entrepot->listAll();

$aColumns = array();
$aColumns = array('p.ref','p.label');

$object->fetch(GETPOST('id'));
$list = explode(',',$object->entrepots);
				
foreach( $entrepot as $key=>$row)
	if($key==$entrepotid){
	$aColumns[] = 'ide.origin_value';
	//$aColumns[] = 'ide.value';
	//$aColumns[] = 'ide.origin_pmp';
	//$aColumns[] = 'ide.pmp';
	}
//$aColumns[] = 'id.date_modified';

$Datatable->SetColumn($aColumns);
				
				
switch($action){


	/**
		@remarks Update value in current inventaire
						Call by ajax
	*/
	case 'updateligne':


		$id = GETPOST('id');
		$value = GETPOST('value'); 
		
		
		if(!isset($id) || empty($id) ){
			$mesg='<div class="error">'.$langs->trans('ErrorForDelete','inventaire').'</div>';
    	exit;
		}

		if(	$object->fetch($id) ){

			preg_match('#valuestock_([0-9]*)_([0-9]*)#i', str_replace(array('[',']'), array('_'),$_GET['name']), $match );

			preg_match('#valuepmp_([0-9]*)_([0-9]*)#i', str_replace(array('[',']'), array('_'),$_GET['name']), $match2 );

		if(count($match2) > 0 ){ 
			$select = 'pmp'; 
			$pid = (int)$match2[2];
			$key = (int)$match2[1];
		}
		else{
			$select = 'value'; 
			$pid = (int)$match[2];
			$key = (int)$match[1];
		}
		


			$inventaireligne = new InventaireApp($db);
			
			if( $inventaireligne->fetch($id, $pid) > 0 ){

				$inventaireligneentrepot = new InventaireLigneEntrepot($db);
				
				$inventaireligneentrepot->fetchByInventaireLineEntrepot($inventaireligne->id, $entrepotid);

				// force bloque this value 
				$inventaireligneentrepot->pmp = false;
				$inventaireligneentrepot->value = false;
				
				
				if($select=='pmp') 
					$inventaireligneentrepot->pmp = $value; 
				else 
					$inventaireligneentrepot->value = $value; 
					
				if( $inventaireligneentrepot->update($user) )
					if( $inventaireligne->update($user) ){
						$object->statut = 1;
						if( $object->update($user) )
							exit; 
					}
				
			}
			
    	exit;
		}
	break;


	/**
			@remarks Ajax call for complete table result
	*/
	case 'dataTable':

		$object->fetch(GETPOST('id'));
		$list = explode(',',$object->entrepots);

		

		  $sql = "SELECT id.*,
				ide.value  as valuec,
				ide.pmp  as valuep,
				ide.origin_value  as valueoc,
				ide.origin_pmp  as valueop,
				p.label , p.ref ";
		  $sql.= " FROM ".MAIN_DB_PREFIX."inventaire_app id INNER JOIN ".MAIN_DB_PREFIX."inventaire_entrepot ide on (id.row_id = ide.fk_inventaire_line_id) INNER JOIN  ".MAIN_DB_PREFIX."product p ON (p.rowid = id.k_product_id) ";
		  $sql.= " WHERE k_inventaire_id = ".GETPOST('id') ;
		  $sql.= " AND fk_entrepot_id = ".GETPOST('entrepotid') ;
// 		  $sql.= " GROUP BY id.row_id ";
// 		  echo $sql; 
// 		  exit;
		  /*
		  * Filtering
		  * NOTE this does not match the built-in DataTables filtering which does it
		  * word by word on any field. It's possible to do here, but concerned about efficiency
		  * on very large tables, and MySQL's regex functionality is very limited
		  */
		  if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		  {
						  $search = addslashes(trim($_GET['sSearch']));
						  $sql.=" AND (   p.label LIKE '%".$search."%' OR p.ref LIKE '%".$search."%'  OR p.barcode = '".$search."' ) ";
		  }


		  $output = $Datatable->GetOutput(  $db,  $sql );
		  $sql = $Datatable->GetSql(  $sql );
		  $resql = $db->query($sql);

		  if ($resql)
		  {
	
			  $num = $output['iTotalRecords']; 
			  $i = 0;
			  
			  $var=True;
			  while ($i < $num)
			  {
				$objp = $db->fetch_object($resql);
				$tmp =  $objp->valuec ; //unserialize($objp->row_value);
				$tmp2 =  $objp->valuep ;
				$tmp3 =  $objp->valueoc  ;
				$tmp4 = $objp->valueop  ;
				$i++;
				$curr = array();

				$curr[]='<a href="'.dol_buildpath('/product/card.php?id='.$objp->k_product_id,1).'" >'.$objp->ref.'</a>';
				$curr[]='<a href="'.dol_buildpath('/product/card.php?id='.$objp->k_product_id,1).'" >'.$objp->label.'</a>';

				$c = 0; 
				foreach( $entrepot as $key=>$row)
				  if($key==$entrepotid){
						$curr[]=$tmp3;
				  
						//$curr[]= '<input type="text" class="ajaxupdate"  name="valuestock['.$row->rowid.']['.$objp->k_product_id.']" size="18" '.(($object->statut >= 2)?'disabled="disabled"' : '').' value="'.(int)$tmp.'">';
						
						//$curr[]=$tmp4;
						
						//$curr[]= '<input type="text" class="ajaxupdate"  name="valuepmp['.$row->rowid.']['.$objp->k_product_id.']" size="18" '.(($object->statut >= 2)?'disabled="disabled"' : '').' value="'.$tmp2.'">';
						
					$c++;
					}

				//$curr[]= ' '.(!empty($objp->date_modified) ? date("d-m-Y",$db->jdate($objp->date_modified)) : '-' );


				  $output['aaData'][] = $curr;

			  }
			}


				echo json_encode( $output );
				exit;
		break;
		
		default: 

}




	


/**
	@remarks Display
*/

llxHeader('',$langs->trans("InventaireFicheTitle"),$helpurl);
?>
<script type="text/javascript">



function updateinajaxline() {
			/* Inline ajax update */
			this.$('input.ajaxupdate').change( function() {
					$.get('products.php?action=updateligne&id=<?php echo GETPOST('id') ?>&entrepotid=<?php echo GETPOST('entrepotid') ?>', { 'name': $(this).attr('name'), 'value': $(this).val() });

					$(this).parent().parent().addClass('modified' );
			} );

			/* Force row change color for indicate row modified */
			this.$('tr').each( function() {
					var nTds = $('td:last-child', this);
					var datemod =nTds.text();
					if ( datemod  !='-')
							$(this).addClass('modified' );
			} );
}

</script>

<?php







$var=false;



	

		dol_fiche_head( InventairePrepareHead() , 'products'.$entrepotid, $langs->trans("InventaireCard"), 0, 'inventaire@inventaire');

	  $Datatable->DisplayHeader('search','id='.GETPOST('id').'&entrepotid='.$entrepotid,'updateinajaxline');
	
		$object->fetch(GETPOST('id'));

		dol_include_once('/inventaire/tpl/inventaire.description.tpl');




		print '<table id="dataTable" class="liste noborder" width="100%">';
					print '<thead>
						<tr class="liste_titre">'.
						'<th style="min-width:50px;text-align:left">'. $langs->trans("Ref").'</th>'.
						'<th style="min-width:250px;text-align:left">'. $langs->trans("InventaireProductName").'</th>';

					foreach( $entrepot as $key=>$row)
		 if($key==$entrepotid){
				//echo '<th style="width:100px;text-align:left">'. $langs->trans("InventaireProductOriginStock").'</th>';
				echo '<th style="width:100px;text-align:left">'. $langs->trans("InventaireProductStock").'</th>';
				//echo '<th style="width:100px;text-align:left">'. $langs->trans("InventaireProductOriginPmp").'</th>';
				//echo '<th style="width:100px;text-align:left">'. $langs->trans("InventaireProductPmp").'</th>';
			}

		//print '<th style="width:100px;text-align:left">'. $langs->trans("InventaireLineUpdate").'</th>'. '</tr></thead>';
		print "<tbody>\n";
// 				AJax Call
		print "</tbody>\n";

		print "</table>\n";



?>