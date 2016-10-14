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
dol_include_once('/inventaire/class/reconstitue_pmp.php');

global $db;

$_pmp= new ReconstituePmp($db);
$product= $_pmp->extractProducts();

foreach ($product as $pr) {
    $id= (int) $pr['id'];
    $pmp_unit= new ReconstituePmp($db);
    $data_mv=$_pmp->getStockMouvement($id);
    if(count($data_mv)>0){
        foreach($data_mv as $mv){
            if($mv['value']>0 && $mv['price']>0){
                $pmp_unit->calculatePmp($mv['value'], $mv['price']);
            }else {
                $pmp_unit->stock= $pmp_unit->stock + $mv['value'];
            }
         $result=$pmp_unit->insertPmp($mv['fk_product'], $pmp_unit->pmp, $pmp_unit->stock, $mv['tms']);
        }
    }

}
$data['ok']='ok';
echo json_encode($data);

