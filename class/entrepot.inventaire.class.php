<?php
require_once DOL_DOCUMENT_ROOT .'/product/stock/class/entrepot.class.php';

/*
 * Class InventaireEntrepot
 */

class InventaireEntrepot Extends Entrepot
{
    public  function  listAll(){
        $sql = "SELECT * ";
        $sql.= "FROM ".MAIN_DB_PREFIX."entrepot ";
        $entrepot =array();
        $resql = $this->db->query($sql);
        if($resql)
        {
            $num=$this->db->num_rows($resql);
            $i = 0;
            while ($i<$num)
            {
                $i++;
                $objp = $this->db->fetch_object($resql);
                $entrepot[$objp->rowid]= $objp;
            }
        }

        return $entrepot;

    }

}