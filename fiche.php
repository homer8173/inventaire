<?php
/**
 *	\file       htdocs/inventaire/fiche.php
 *	\brief      File for inventaire
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

/*
 * load class
 */
dol_include_once('/inventaire/class/inventaire.class.php');
dol_include_once('/inventaire/class/entrepot.inventaire.class.php');
dol_include_once('/inventaire/class/snapshot.inventaire.class.php');
dol_include_once('/inventaire/class/inventaire.app.class.php');
dol_include_once('/inventaire/class/inventaire.ligne.entrepot.class.php');

dol_include_once('/inventaire/core/lib/inventaire.lib.php');

// oscimmods Required
dol_include_once('/oscimmods/class/Datatable.class.php');

$langs->load("inventaire");

$action = GETPOST('action');

$object = new Inventaire($db);
$snaptshot = new Snapshot($db);
$Entrepot = new InventaireEntrepot($db);



$entrepot = $Entrepot->listAll();
$object->fetch(GETPOST('id'));
$list = explode(',',$object->entrepots);

switch($action){

    /**
    @remarks Delete inventaire
     */
    case 'deleteconfirm':
        $id = GETPOST('id');

        if(!isset($id) || empty($id) ){
            $mesg='<div class="error">'.$langs->trans('ErrorForDelete','inventaire').'</div>';
            Header("Location: ".dol_buildpath('/inventaire/index.php', 1) );
            exit;
        }

        if(	$object->fetch($id) ){

            $db->query("DELETE FROM ".MAIN_DB_PREFIX."inventaire_name WHERE k_inventaire_id = '".$id."' ");


            $object->delete($id);

            Header("Location: ".dol_buildpath('/inventaire/index.php',1 ) );
            exit;
        }

        break;
    /**
    @remarks Create new inventaire
     */
    case 'create':

        if(!isset($_POST['label'])){
            $mesg='<div class="error">'.$langs->trans('ErrorFieldRequired',$langs->transnoentities('name')).'</div>';
            Header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        }

        $ent = array();
        foreach(GETPOST('entrepot') as $key=>$row)
            if($row == 1 ) $ent[] = $key;

        $object->name = GETPOST('label');
        $object->entrepots =implode(',', $ent);

        if( ! $object->create($user) ){
            $mesg='<div class="error">'.$langs->trans('ErrorCreate','inventaire').'</div>';
            Header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        }
        else {

            $tmpresult = $snaptshot->ExtractProducts( GETPOST('entrepot') );

            $InventaireLine= new InventaireApp($db);
            $Inventaireligneentrepot= new InventaireLigneEntrepot($db);
            $InventaireLine->k_inventaire_id = $object->id;

            // insert line for one product and entrepot/qt stocked in db by serialize row
            foreach($tmpresult as $pid => $row){
                $InventaireLine->k_product_id = $pid;
                $InventaireLine->pmp = $row['ppmp'];
// 				  $InventaireLine->row_value = serialize($row['stock']);
// 				  $InventaireLine->row_pmp = serialize($row['pmp']);
// 				  $InventaireLine->origin_value = serialize($row['stock']);
// 				  $InventaireLine->origin_pmp = serialize($row['pmp']);

                $lineid = $InventaireLine->create($user);

                foreach($row['stock'] as $keid=>$v){
                    $Inventaireligneentrepot->fk_inventaire_line_id = $lineid;
                    $Inventaireligneentrepot->fk_entrepot_id = $keid;
                    $Inventaireligneentrepot->value = $row['stock'][$keid];
                    $Inventaireligneentrepot->pmp = $row['pmp'][$keid];
                    $Inventaireligneentrepot->origin_value = $row['stock'][$keid];
                    $Inventaireligneentrepot->origin_pmp = $row['pmp'][$keid];

                    $Inventaireligneentrepot->create($user);
                }
            }
        }

        Header("Location: ".dol_buildpath('/inventaire/fiche.php?id='.$object->id,1));
        break;





}







/**
@remarks Display
 */

llxHeader('',$langs->trans("InventaireFicheTitle"),$helpurl);




print '<table class="notopnoleftnoright" width="100%">';
print '<tr><td valign="top" width="30%" class="notopnoleft">';





$var=false;


switch($action){
    case 'applied':
        print_fiche_titre($langs->trans("InventaireFormApplied"));
        $object->fetch(GETPOST('id'));
        $list = explode(',',$object->entrepots );

        print '<form method="post" action="fiche.php?action=appliedconfirm&amp;id='.GETPOST('id').'">';
        print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';


        dol_include_once('/inventaire/tpl/inventaire.description.tpl');

        print '<div class="tabBar"><table class="border" width="100%">'.
            '<table class="noborder" width="100%">'.
            '<td colspan="2">'.$langs->trans("InventaireConfirmApplied").'</td>'.

            '<tr><td colspan="2"><input type="checkbox" name="absolute" value="1" />'. $langs->trans("InventaireAppliedAbsolute").'</td></tr>';

        print '<tr class="liste_titre">'.
            '<td colspan="2">'.$langs->trans("InventaireChooseEntrepot").'</td>'.
            '</tr>';



        foreach( $entrepot as $key=>$row ) {
            if(in_array($key, $list) || count($list) ==0 )
                print '<tr><td><input type="checkbox" class="flat" checked="checked" name="entrepot['.$key.']" value="1" ></td><td>'.$row->label.'</td>'.
                    '</tr>';

        }

        print '<tr><td></td><td><input type="submit" value="'.$langs->trans("InventaireConfirmAppliedButton").'" class="button"></td></tr>';
        print "</table></div>\n";
        print "</form><br>\n";
        break;

    case 'fixed':
        print_fiche_titre($langs->trans("InventaireFormFixed"));
        $object->fetch(GETPOST('id'));


        print '<form method="post" action="'.dol_buildpath('inventaire/fiche.php?action=fixedconfirm&amp;id='.GETPOST('id'),1).'">';
        print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';

        dol_include_once('/inventaire/tpl/inventaire.description.tpl');


        print '<div class="tabBar"><table class="border" width="100%">'.
            '<table class="noborder" width="100%">'.
            '<td colspan="2">'.$langs->trans("InventaireConfirmFixed").'</td>'.
            '<tr><td colspan="2"><input type="submit" value="'.$langs->trans("InventaireConfirmFixedButton").'" class="button"></td></tr>';
        print "</table></div>\n";
        print "</form><br>\n";
        break;

    case 'delete':
        print_fiche_titre($langs->trans("InventaireFormDelete"));
        $object->fetch(GETPOST('id'));


        print '<form method="post" action="fiche.php?action=deleteconfirm&amp;id='.GETPOST('id').'">';
        print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';


        dol_include_once('/inventaire/tpl/inventaire.description.tpl');

        print '<div class="tabBar"><table class="border" width="100%">'.
            '<table class="noborder" width="100%">'.
            '<td colspan="2">'.$langs->trans("InventaireConfirmDelete").'</td>'.
            '<tr><td colspan="2"><input type="submit" value="'.$langs->trans("InventaireConfirmDeleteButton").'" class="button"></td></tr>';
        print "</table></div>\n";
        print "</form><br>\n";
        break;

    case 'add':
        print_fiche_titre($langs->trans("InventaireFormAdd"));

        print '<table class="noborder" width="100%">';
        print '<form method="post" action="fiche.php?action=create">';
        print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
        print '<tr class="liste_titre">'.
            '<td colspan="3">'.$langs->trans("InventaireAddSnapShot").'</td>'.
            '</tr>'.
            '<tr><td>'.
            $langs->trans("Name").':</td><td><input type="text" class="flat" name="label" size="18" ></td><td>'.$langs->trans("InventaireNameHelp").'</td>'.
            '</tr>';


        print '<tr class="liste_titre">'.
            '<td colspan="3">'.$langs->trans("InventaireChooseEntrepot").'</td>'.
            '</tr>';



        foreach( $entrepot as $key=>$row ) {

            print '<tr><td></td><td><input type="checkbox" class="flat" checked="checked" name="entrepot['.$key.']" value="1" ></td><td>'.$row->label.'</td>'.
                '</tr>';

        }



        print '<tr><td></td><td></td><td><input type="submit" value="'.$langs->trans("InventaireAddButton").'" class="button"></td></tr>';
        print "</form></table><br>\n";
        break;


    default:


        dol_fiche_head( InventairePrepareHead() , 'card', $langs->trans("InventaireCard"), 0, 'inventaire@inventaire');



        $object->fetch(GETPOST('id'));

        dol_include_once('/inventaire/tpl/inventaire.description.tpl');


        print '<div class="tabsAction">';
        if($object->statut >= 1 && $object->statut < 2)
            print '<a href="fiche.php?action=fixed&amp;id='.GETPOST('id').'" class="butAction">'. $langs->trans("InventaireConfirmFixedButton").'</a>';

        if($object->statut == 2)
            print '<a href="fiche.php?action=applied&amp;id='.GETPOST('id').'" class="butAction">'. $langs->trans("InventaireConfirmAppliedButton").'</a>';

        if($object->statut < 3)
            print '<a href="fiche.php?action=delete&amp;id='.GETPOST('id').'" class="butAction">'. $langs->trans("InventaireConfirmDeleteButton").'</a>';
        print '</div>';




}