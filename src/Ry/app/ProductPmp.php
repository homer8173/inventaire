<?php

namespace Ry\app;

require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';

use Product;

class ProductPmp
{
    public $db;
    public $pmp=0;
    public $product_id;
    public $stock;
    public $price;
    public $table_element="product_pmp";

    public function __construct(Product $product) {
        $this->product_id = $product->id;
        $this->stock = $product->stock_reel;
        $this->price = $product->price;
        $this->pmp = $product->pmp;


    }

    /**
     * @param $pmp
     * @param int $stock_total
     * @param $price
     * @param $qty
     * @return float
     * calcul pmp @todo controle valeur retourner
     */

    public function setPmp($pmp, $stock_total=1, $price,$qty){
        $stock=$stock_total-$qty;
        $stock_total= $stock_total!=0 ? $stock_total: 1;
        $pmp = ($stock*$pmp + $qty*$price)/$stock_total;
        return $pmp;

    }
    /*
     * Mis à jour pmp du produit
     */

    public function updatePmp() {
        global $db;
        $error =0;
        $sql="UPDATE ". MAIN_DB_PREFIX ."product";
        $sql.=" SET pmp = ".$this->pmp;
        $sql.=" WHERE rowid=".$this->product_id;

        $db->begin();

        $resql=$db->query($sql);
        if (! $resql) {
            $error++; $this->errors[]="Error ".$db->lasterror();
        }


        // Commit or rollback

        if ($error)
        {
            foreach($this->errors as $errmsg)
            {
                dol_syslog(get_class($this)."::update ".$errmsg, LOG_ERR);
                $this->error.=($this->error?', '.$errmsg:$errmsg);
            }
            $db->rollback();
            return  -1*$error;
        }
        else
        {
            $db->commit();
            return $this->pmp;

        }
    }
    /*
     * insertion cache pmp
     */
    public function insertPmp() {
        global $db;
        $error= 0;

        $sql = "INSERT INTO ".MAIN_DB_PREFIX.$this->table_element."(";
        $sql.= " fk_product,";
        $sql.= " pmp,";
        $sql.= " stock,";
        $sql.= " date_created";
        $sql.= ") VALUES (";
        $sql.= " ".(! isset($this->product_id)?'NULL':"'".$this->product_id."'").",";
        $sql.= " ".(! isset($this->pmp)?'NULL':$this->pmp).",";
        $sql.= " ".(! isset($this->stock)?'0':"'".$this->stock."'").",";
        $sql.= "NOW()";
        $sql.= ")";

        $db->begin();
        $resql=$db->query($sql);
        if (! $resql) {
            $error++; $this->errors[]="Error ".$db->lasterror();
        }


        // Commit or rollback

        if ($error)
        {
            foreach($this->errors as $errmsg)
            {
                dol_syslog(get_class($this)."::update ".$errmsg, LOG_ERR);
                $this->error.=($this->error?', '.$errmsg:$errmsg);
            }
            $db->rollback();
            return  -1*$error;
        }
        else
        {
            $db->commit();
            return 1;

        }

     }


}