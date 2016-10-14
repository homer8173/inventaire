<?php
/**
 * Created by PhpStorm.
 * User: imiary
 * Date: 10/13/16
 * Time: 3:18 PM
 */
class ReconstituePmp
{
    public $db;
    public $pmp=0;
    public $stock=0;
    public $stock_total;
    public $table_element="product_pmp";

    public function __construct($db){
        $this->db= $db;
    }

    public function  calculatePmp($value=1, $prix){

        $total = $value+$this->stock;
        $pmp= ($this->pmp*$this->stock + $value*$prix)/($total);
        $data['total'] = $total;
        $data['pmp'] = $pmp;
        $this->stock = $total;
        $this->pmp = $pmp;
        return $data;
    }
    /*
     * insertion cache pmp
     */
    public function insertPmp($id_product, $pmp, $stock, $date) {
         $error= 0;

        $sql = "INSERT INTO ".MAIN_DB_PREFIX.$this->table_element."(";
        $sql.= " fk_product,";
        $sql.= " pmp,";
        $sql.= " stock,";
        $sql.= " date_created";
        $sql.= ") VALUES (";
        $sql.= " ".(! isset($id_product)?'NULL':"'".$id_product."'").",";
        $sql.= " ".(! isset($pmp)?'NULL':$pmp).",";
        $sql.= " ".(! isset($stock)?'0':"'".$stock."'").",";
        $sql.= " ".(! isset($date)?'NOW()':"'".$date."'");
        $sql.= ")";

        $this->db->begin();
        $resql=$this->db->query($sql);
        return $this->db;
        if (! $resql) {
            $error++; $this->errors[]="Error ".$this->db->lasterror();
        }


        // Commit or rollback

        if ($error)
        {
            foreach($this->errors as $errmsg)
            {
                dol_syslog(get_class($this)."::create ".$errmsg, LOG_ERR);
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

    public function getStockMouvement($id_product){
        $sql =" SELECT fk_product,fk_entrepot,value,price,type_mouvement,label,tms";
        $sql .=" FROM ".MAIN_DB_PREFIX."stock_mouvement as s ";
        $sql .=" WHERE 1 ";
        $sql .= " AND fk_product=".$id_product;
        $resql= $this->db->query($sql);
        if($this->db->num_rows($resql)){
            while ($objp = $this->db->fetch_array($resql))
            {
                $tmpresult[] = $objp;
            }

        }
        return $tmpresult;

    }

    function extractProducts(){
        global $conf;

        $sql = "SELECT p.rowid as id, p.ref, p.label as produit, p.fk_product_type as type, p.pmp as ppmp, p.price, p.price_ttc, p.stock as reel,";
        $sql.= " ps.pmp, ps.reel as value, fk_entrepot as entrepot";
        $sql.= " FROM  ".MAIN_DB_PREFIX."product p LEFT JOIN ".MAIN_DB_PREFIX."product_stock ps ON (ps.fk_product = p.rowid) ";
        $sql.= " WHERE 1 ";

        /*$ent = array();
        foreach($entrepots as $key=>$row)
            if($row == 1 ) $ent[] = $key;*/

        $sql.= " ORDER BY p.rowid ASC ";
        //$this->db->begin();
        $resql = $this->db->query($sql);
        if ($resql)
        {
            $tmpresult = array();

            while ($objp = $this->db->fetch_array($resql))
            {
                $tmpresult[] = $objp;
            }

        }

        return $tmpresult;
    }


}