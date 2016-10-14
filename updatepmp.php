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
    $data_mv=$_pmp->getStockMouvement($id);
    if(count($data_mv)>0){
        foreach($data_mv as $mv){
            if($mv['value']>0 && $mv['price']>0){
                $_pmp->calculatePmp($mv['value'], $mv['price']);
            }else {
                $_pmp->stock= $_pmp->stock + $mv['value'];
            }
         $result=$_pmp->insertPmp($mv['fk_product'], $_pmp->pmp, $_pmp->stock, $mv['tms']);
        }
    }

}
$data['ok']='ok';
echo json_encode($data);

