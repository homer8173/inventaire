<?php
/**
 * Created by PhpStorm.
 * User: imiary
 * Date: 9/21/16
 * Time: 5:28 PM
 */
class Snapshot {

    public $db;

    public $entrepot = array();

    function __construct($db, $entrepot= array()){
        $this->db = $db ;
        $this->entrepot = $entrepot;


    }
    /**
    @brief extract product in db , for configuration option restrict

    @param $entrepots array result of chekcbox choose entrepots
     */
    function ExtractProducts(  $entrepots ){
        global $conf;


        $sql = "SELECT p.rowid as id, p.ref, p.label as produit, p.fk_product_type as type, p.pmp as ppmp, p.price, p.price_ttc,";
        $sql.= " ps.pmp, ps.reel as value, fk_entrepot as entrepot";
        $sql.= " FROM  ".MAIN_DB_PREFIX."product p LEFT JOIN ".MAIN_DB_PREFIX."product_stock ps ON (ps.fk_product = p.rowid) ";
        $sql.= " WHERE 1 ";

        $ent = array();
        foreach($entrepots as $key=>$row)
            if($row == 1 ) $ent[] = $key;


        $sql.= " AND fk_entrepot IN (". implode(',', $ent) .")  ";

        $sql.= " AND ( fk_product_type = 0 ";
        if ($conf->global->PRODUCT_VIRTUAL == 1)  $sql.= " OR fk_product_type = 1 ";
        $sql.= " ) ";
        $sql.= " AND ( p.tosell = '".(($conf->global->PRODUCT_HORS_VENTE == 1)? 1 :0)."' ";
        $sql.= " OR p.tobuy = '".(($conf->global->PRODUCT_HORS_ACHAT == 1)? 1 : 0)."') ";
        $sql.= " GROUP BY fk_entrepot ,p.rowid   ";

        $resql = $this->db->query($sql);
        if ($resql)
        {
            $num = $this->db->num_rows($resql);
            $i = 0;
            $var=True;
            $tmpresult = array();

            while ($i < $num)
            {
                $objp = $this->db->fetch_object($resql);
                $i++;
                $tmpresult[$objp->id]['stock'][$objp->entrepot] = $objp->value;
                $tmpresult[$objp->id]['pmp'][$objp->entrepot] = $objp->pmp;
                $tmpresult[$objp->id]['ppmp'] = $objp->ppmp;
            }

        }

        return $tmpresult;
    }



}