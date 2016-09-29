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
            try{
                $db->query("DELETE FROM ".MAIN_DB_PREFIX."inventaire_app WHERE k_inventaire_id = '".$id."' ");
                $object->delete($id);

            }catch (Exception $e){
                //print error
            }


            Header("Location: ".dol_buildpath('/inventaire/index.php',1 ) );
            exit;
        }

        break;
    /**
    @remarks Create new inventaire
     */
    case 'create':


        if($_POST['label']==""){
            $mesg='<div class="error">'.$langs->trans('ErrorFieldRequired',$langs->transnoentities('name')).'</div>';
            //setEventMessage($mesg);
            Header("Location: ".$_SERVER['PHP_SELF']."?action=add");
            exit;
        }


        $_dateobj= new DateTime('now');
        $object->name = GETPOST('label');
        $date = GETPOST('date_created');
        $date =  $date != ""? implode('-', array_reverse(explode('/', $date))): $_dateobj->format('Y-m-d');

        if( $object->verifyDate($date)==false){
            $mesg='<div class="error">'.$langs->trans('ErrorFieldRequired',$langs->transnoentities('name')).'</div>';
            //Header("Location: ".$_SERVER['PHP_SELF']."?action=add");
            setEventMessage($mesg);
            Header("Location: ".dol_buildpath('/inventaire/fiche.php?action=add',1));
            //setEventMessage($mesg);
            exit;
        }


        $_date_invobj = new DateTime($date);
        $heur_now=$_dateobj->format('H:i:s');
        $date_inventaire = $_date_invobj->format('Y-m-d '.$heur_now);

        /*entrepot(s)*/
        $ent = array();
        foreach(GETPOST('entrepot') as $key=>$row)
            if($row == 1 ) $ent[] = $key;

        $object->date_created = $date_inventaire;
        $object->entrepots  =implode(',', $ent);
        $object->create($user);
        var_dump($object->date_created ,$this->entrepots ,$object->create($user));
        exit;

        if( ! $object->create($user) ){
            $mesg='<div class="error">'.$langs->trans('ErrorCreate','inventaire').'</div>';
            Header("Location: ".dol_buildpath('/inventaire/fiche.php?action=add',1));
            exit;
        }


        Header("Location: ".dol_buildpath('/inventaire/fiche.php?id='.$object->id,1));
        break;
    /**
    @remarks applied inventaire
     */
    case 'applied':
        $id = GETPOST('id');

        if(!isset($id) || empty($id) ){
            $mesg='<div class="error">'.$langs->trans('ErrorForDelete','inventaire').'</div>';
            Header("Location: ".DOL_URL_ROOT.'/inventaire/index.php');
            exit;
        }

        if($object->fetch($id)){
            $_dateobjNow= new DateTime('now');
            $date_now= $_dateobjNow->format("Y-m-d");
            $_dateobjInv= new DateTime($object->datec);
            $date_inventaire=$_dateobjInv->format("Y-m-d");
            if(strtotime($date_inventaire) <= strtotime($date_now)){

                $object->fix($user);
                $tmpresult = $snaptshot->extractProducts($object->entrepots, $object->datec);

                $InventaireLine= new InventaireApp($db);
                $Inventaireligneentrepot= new InventaireLigneEntrepot($db);

                $InventaireLine->k_inventaire_id = $object->id;

                foreach($tmpresult as $pid => $row) {
                    $somme_mouvement_stock=$Inventaireligneentrepot->sommeStock($pid, $date_inventaire, $date_now);
                    $InventaireLine->k_product_id = $pid;
                    $InventaireLine->pmp = $row['ppmp'];
                    $InventaireLine->stock_reel=$row['reel'] - $somme_mouvement_stock;
// 				  $InventaireLine->row_value = serialize($row['stock']);
// 				  $InventaireLine->row_pmp = serialize($row['pmp']);
// 				  $InventaireLine->origin_value = serialize($row['stock']);
// 				  $InventaireLine->origin_pmp = serialize($row['pmp']);

                    $lineid = $InventaireLine->create($user);

                    foreach ($row['stock'] as $keid => $v) {
                        $somme_mouvement_stock = $Inventaireligneentrepot->sommeStock($pid, $date_inventaire, $date_now, $keid);
                        $Inventaireligneentrepot->fk_inventaire_line_id = $lineid;
                        $Inventaireligneentrepot->fk_entrepot_id = $keid;
                        $Inventaireligneentrepot->value = $row['stock'][$keid] - $somme_mouvement_stock;
                        $Inventaireligneentrepot->pmp = $row['pmp'][$keid];
                        $Inventaireligneentrepot->origin_value = $row['stock'][$keid] - $somme_mouvement_stock;
                        $Inventaireligneentrepot->origin_pmp = $row['pmp'][$keid];

                        $Inventaireligneentrepot->create($user);
                    }
                }
            }


            Header("Location: ".dol_buildpath('/inventaire/fiche.php?id='.$id,1));
            exit;
        }

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
            $langs->trans("Name").':</td><td><input type="text" class="flat" name="label" size="18" required="required"></td><td>'.$langs->trans("InventaireNameHelp").'</td>'.

            '</tr><td>'.
                $langs->trans("Date").':</td><td><input type="text" onchange="dpChangeDay(\'date_created\',\'dd-MM-yyyy\'); "  maxlength="11" size="18" name="date_created" id="date_created" ><button onclick="showDP(\'/core/\',\'date_created\',\'dd-MM-yyyy\',\'fr_FR\');" class="dpInvisibleButtons" type="button" id="reButton"><img border="0" class="datecallink" title="Sélectionnez une date" alt="" src="/theme/eldy/img/object_calendarday.png"></button>
                <input type="hidden" value="22" name="date_createdday" id="date_createdday"><input type="hidden" value="" name="date_createdmonth" id="date_createdmonth"><input type="hidden" value="2016" name="date_createdyear" id="date_createdyear"></td><td>'.$langs->trans("InventaireDate").'</td>'.
            '</tr><td>';


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
        if($object->statut < 2)
            print '<a href="fiche.php?action=applied&amp;id='.GETPOST('id').'" class="butAction">'. $langs->trans("InventaireConfirmAppliedButton").'</a>';

        if($object->statut < 3)
            print '<a href="fiche.php?action=delete&amp;id='.GETPOST('id').'" class="butAction">'. $langs->trans("InventaireConfirmDeleteButton").'</a>';
        print '</div>';




}