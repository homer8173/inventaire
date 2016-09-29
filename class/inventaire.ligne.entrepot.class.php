<?php
/**
 * Created by PhpStorm.
 * User: imiary
 * Date: 9/22/16
 * Time: 11:30 AM
 */

class InventaireLigneEntrepot {
    public $db ;


    public $fk_inventaire_line_id;
    public $fk_entrepot_id;
    public $value = -1;
    public $pmp = 0;
    public $origin_value;
    public $origin_pmp;
    public $fk_user_modified;
    public $date_modified;

    function __construct($db ){
        $this->db = $db;
    }

    public function create($user){

        $sql= "INSERT INTO ".MAIN_DB_PREFIX."inventaire_entrepot (
			fk_inventaire_line_id,
			fk_entrepot_id,
			value,
			pmp,
			origin_value,
			origin_pmp,
			fk_user_modified,
			date_modified
		)
		VALUES (
			'".$this->fk_inventaire_line_id."',
			'".$this->fk_entrepot_id."',
			'".$this->value."',
			'".$this->pmp."',
			'".$this->origin_value."',
			'".$this->origin_pmp."',
			'".$user->id."',
			'NOW()'
		) ";
        $this->db->begin();
        $er= array($this->db);
        return  $er ;//$this->db->query($sql);

    }

    public function update($user){
        $sql= "UPDATE ".MAIN_DB_PREFIX."inventaire_entrepot SET ";

        if( !($this->value === false)  )
            $sql.= "value = '".$this->value."',  ";

        if( !($this->pmp === false) )
            $sql.= " pmp = '".$this->pmp."', ";

        $sql.= " fk_user_modified = '".$user->id."',
					date_modified = NOW()
				WHERE row_id = '".$this->id."'
				";

        return $this->db->query($sql);
    }

    public function fetch($id){
        global $langs;
        $sql = "SELECT";
        $sql.= " row_id,";
        $sql.= " fk_inventaire_line_id,";
        $sql.= " fk_entrepot_id,";
        $sql.= " value,";
        $sql.= " pmp,";
        $sql.= " origin_value,";
        $sql.= " origin_pmp,";
        $sql.= " date_modified,";
        $sql.= " fk_user_modified";
        $sql.= " FROM ".MAIN_DB_PREFIX."inventaire_entrepot ";
        $sql.= " WHERE row_id = ".$id;

        dol_syslog(get_class($this)."::fetch sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
        if ($resql){
            if ($this->db->num_rows($resql)){
                $obj = $this->db->fetch_object($resql);

                $this->id              = $obj->row_id;
                $this->fk_inventaire_line_id        = $obj->fk_inventaire_line_id;
                $this->fk_entrepot_id        = $obj->fk_entrepot_id;
                $this->value        = $obj->value;

                $this->pmp        = $obj->pmp;
                $this->origin_value       = $obj->origin_value;
                $this->origin_pmp     = $obj->origin_pmp;

                $this->date_modified  = $this->db->jdate($obj->date_modified);
                $this->fk_user_modified   = $obj->fk_user_modified;

            }
            $this->db->free($resql);

            return 1;
        }
        else{
            $this->error="Error ".$this->db->lasterror();
            dol_syslog(get_class($this)."::fetch ".$this->error, LOG_ERR);
            return -1;
        }
    }

    /*
     * somme stock physique à la date t
     */
    public function sommeStock($produit_id, $date1, $date2, $entrepot=""){
        $sql= "SELECT ";
        $sql.= " tms,";
        $sql.= " datem,";
        $sql.= " fk_product,";
        $sql.= " fk_entrepot,";
        $sql.= " value,";
        $sql.= " type_mouvement,";
        $sql.= " SUM(value) as somme";
        $sql.= " FROM " . MAIN_DB_PREFIX ."stock_mouvement";
        $sql.= " WHERE fk_product =".$produit_id;
        $sql.= " AND datem BETWEEN '$date1' AND '$date2'";

        if($entrepot!="")
            $sql.= " AND fk_entrepot =".$entrepot;

        $resql=$this->db->query($sql);
        $somme=0;
        if($resql){
            if($this->db->num_rows($resql)){
                $obj = $this->db->fetch_object($resql);
                $somme= (int) $obj->somme;
            }
          return $somme;
        }
        return 2;

    }

    public function fetchByInventaireLineEntrepot($inventaire_line_id, $entrepot_id){
        global $langs;
        $sql = "SELECT";
        $sql.= " row_id,";
        $sql.= " fk_inventaire_line_id,";
        $sql.= " fk_entrepot_id,";
        $sql.= " value,";
        $sql.= " pmp,";
        $sql.= " origin_value,";
        $sql.= " origin_pmp,";
        $sql.= " date_modified,";
        $sql.= " fk_user_modified";
        $sql.= " FROM ".MAIN_DB_PREFIX."inventaire_entrepot ";
        $sql.= " WHERE fk_inventaire_line_id = '".$inventaire_line_id."' AND fk_entrepot_id = '".$entrepot_id."' ";

        dol_syslog(get_class($this)."::fetch sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
        if ($resql){
            if ($this->db->num_rows($resql)){
                $obj = $this->db->fetch_object($resql);

                $this->id              = $obj->row_id;
                $this->fk_inventaire_line_id        = $obj->fk_inventaire_line_id;
                $this->fk_entrepot_id        = $obj->fk_entrepot_id;
                $this->value        = $obj->value;

                $this->pmp        = $obj->pmp;
                $this->origin_value       = $obj->origin_value;
                $this->origin_pmp     = $obj->origin_pmp;

                $this->date_modified  = $this->db->jdate($obj->date_modified);
                $this->fk_user_modified   = $obj->fk_user_modified;

            }
            $this->db->free($resql);

            return 1;
        }
        else{
            $this->error="Error ".$this->db->lasterror();
            dol_syslog(get_class($this)."::fetch ".$this->error, LOG_ERR);
            return -1;
        }
    }

}