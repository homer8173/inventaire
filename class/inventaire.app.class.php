<?php
/**
 * Created by PhpStorm.
 * User: imiary
 * Date: 9/22/16
 * Time: 11:14 AM
 */
class InventaireApp {
    public $db ;

    public $id;

    public $k_inventaire_id;
    public $k_product_id;
    public $pmp;
    public $row_value;
    public $row_pmp;
    public $stock_reel;

    public $origin_value;
    public $origin_pmp;
    public $table_element ='inventaire_app';

    public function __construct($db ){
        $this->db = $db;
    }

    public function create($user){
        $sql= "INSERT INTO ".MAIN_DB_PREFIX.$this->table_element."(
			k_inventaire_id,
			k_product_id,
			pmp,
			reel
			) VALUES (
			'".$this->k_inventaire_id."',
			'".$this->k_product_id."',
			'".$this->pmp."',
			'".$this->stock_reel."'
			) ";


        $this->db->query($sql);

        $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX.$this->table_element);

        return $this->id;
    }

    public function update($user){
        $sql= "UPDATE ".MAIN_DB_PREFIX.$this->table_element." SET ";

        $sql.= " fk_user_modified = '".$user->id."',
					date_modified = NOW()
				WHERE row_id = '".$this->id."'
				";

        return $this->db->query($sql);
    }

    public function fetch($k_inventaire, $k_product){

        $sql = "SELECT *  ";
        $sql.= " FROM ".MAIN_DB_PREFIX.$this->table_element;
        $sql.= " WHERE k_inventaire_id = '".$k_inventaire."' AND k_product_id = '".$k_product."' " ;
        $tmp = array();
        $resql = $this->db->query($sql);
        if ($resql)
        {
            $num = $this->db->num_rows($resql);
            $i = 0;
            $var=True;

            $objp = $this->db->fetch_object($resql);

            $this->id = $objp->row_id;
            $this->k_inventaire_id = $objp->k_inventaire_id;
            $this->k_product_id = $objp->k_product_id;
            $this->pmp = $objp->pmp;
            $this->applied = $objp->applied;
            $this->date_modified = $this->db->jdate($objp->date_modified);
            $this->fk_user_modified = $objp->fk_user_modified;
            $this->stock_reel = $objp->reel;

            return 1;
        }

        return 0;
    }

    public function delete(){

    }

}