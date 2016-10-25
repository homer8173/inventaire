<?php

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
global $db;

$object = new Inventaire($db);
$snaptshot = new Snapshot($db);
$Entrepot = new InventaireEntrepot($db);


$entrepot = $Entrepot->listAll();



 $data= array();
$data['message']='';
$id=GETPOST('idinv','int');
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
                    $InventaireLine->stock_reel=$row['reel'];
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
    if(strtotime($date_inventaire) > strtotime($date_now)) {
        $data['message'] = 'Inventaire pas encore disponible à cette date!';
    }



        }
echo json_encode($data);
die();