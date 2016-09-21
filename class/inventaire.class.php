<?php
/* Copyright (C) 2007-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2014	   Juanjo Menent		<jmenent@2byte.es>
 * Copyright (C) ---Put here your own copyright and developer email---
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *  \file       dev/skeletons/inventairename.class.php
 *  \ingroup    mymodule othermodule1 othermodule2
 *  \brief      This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *				Initialy built by build_class_from_table on 2016-08-03 11:11
 */

// Put here all includes required by your class file
require_once(DOL_DOCUMENT_ROOT."/core/class/commonobject.class.php");
//require_once(DOL_DOCUMENT_ROOT."/societe/class/societe.class.php");
//require_once(DOL_DOCUMENT_ROOT."/product/class/product.class.php");


/**
 *	Put here description of your class
 */
class Inventaire extends CommonObject
{
	var $db;							//!< To store db handler
	var $error;							//!< To return error code (or message)
	var $errors=array();				//!< To return several error codes (or messages)
	var $element='inventairename';			//!< Id that identify managed objects
	var $table_element='inventaire_name';		//!< Name of table without prefix where object is stored

    var $id;
    
	var $row_id;
	var $name;
	var $entrepots;
	var $statut;
	var $date_created='';
	var $fk_user_created;
	var $date_modified='';
	var $fk_user_modified;
	var $date_applied='';

    


    /**
     *  Constructor
     *
     *  @param	DoliDb		$db      Database handler
     */
    function __construct($db)
    {
        $this->db = $db;
        return 1;
    }


    /**
     *  Create object into database
     *
     *  @param	User	$user        User that creates
     *  @param  int		$notrigger   0=launch triggers after, 1=disable triggers
     *  @return int      		   	 <0 if KO, Id of created object if OK
     */
    function create($user, $notrigger=0)
    {
    	global $conf, $langs;
		$error=0;

		// Clean parameters
        
		if (isset($this->row_id)) $this->row_id=trim($this->row_id);
		if (isset($this->name)) $this->name=trim($this->name);
		if (isset($this->entrepots)) $this->entrepots=trim($this->entrepots);
		if (isset($this->statut)) $this->statut=trim($this->statut);
		if (isset($this->fk_user_created)) $this->fk_user_created=trim($this->fk_user_created);
		if (isset($this->fk_user_modified)) $this->fk_user_modified=trim($this->fk_user_modified);

        

		// Check parameters
		// Put here code to add control on parameters values

        // Insert request
		$sql = "INSERT INTO ".MAIN_DB_PREFIX.$this->table_element."(";
		
		$sql.= "name,";
		$sql.= "entrepots,";
		$sql.= "statut,";
		$sql.= "date_created,";
		$sql.= "fk_user_created,";
		$sql.= "date_modified,";
		$sql.= "fk_user_modified,";
		$sql.= "date_applied";

		
        $sql.= ") VALUES (";
        
		$sql.= " ".(! isset($this->name)?'NULL':"'".$this->db->escape($this->name)."'").",";
		$sql.= " ".(! isset($this->entrepots)?'NULL':"'".$this->db->escape($this->entrepots)."'").",";
		$sql.= " ".(! isset($this->statut)?'NULL':"'".$this->statut."'").",";
		$sql.= " ".(! isset($this->date_created) || dol_strlen($this->date_created)==0?'NULL':"'".$this->db->idate($this->date_created)."'").",";
		$sql.= " ".(! isset($this->fk_user_created)?'NULL':"'".$this->fk_user_created."'").",";
		$sql.= " ".(! isset($this->date_modified) || dol_strlen($this->date_modified)==0?'NULL':"'".$this->db->idate($this->date_modified)."'").",";
		$sql.= " ".(! isset($this->fk_user_modified)?'NULL':"'".$this->fk_user_modified."'").",";
		$sql.= " ".(! isset($this->date_applied) || dol_strlen($this->date_applied)==0?'NULL':"'".$this->db->idate($this->date_applied)."'")."";

        
		$sql.= ")";

		$this->db->begin();

	   	dol_syslog(__METHOD__, LOG_DEBUG);
        $resql=$this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
        {
            $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX.$this->table_element);

			if (! $notrigger)
			{
	            // Uncomment this and change MYOBJECT to your own tag if you
	            // want this action calls a trigger.

	            //// Call triggers
	            //$result=$this->call_trigger('MYOBJECT_CREATE',$user);
	            //if ($result < 0) { $error++; //Do also what you must do to rollback action if trigger fail}
	            //// End call triggers
			}
        }

        // Commit or rollback
        if ($error)
		{
			foreach($this->errors as $errmsg)
			{
	            dol_syslog(__METHOD__." ".$errmsg, LOG_ERR);
	            $this->error.=($this->error?', '.$errmsg:$errmsg);
			}
			$this->db->rollback();
			return -1*$error;
		}
		else
		{
			$this->db->commit();
            return $this->id;
		}
    }


    /**
     *  Load object in memory from the database
     *
     *  @param	int		$id    	Id object
     *  @param	string	$ref	Ref
     *  @return int          	<0 if KO, >0 if OK
     */
    function fetch($id,$ref='')
    {
    	global $langs;
        $sql = "SELECT";
		$sql.= " t.rowid,";
		
		$sql.= " t.row_id,";
		$sql.= " t.name,";
		$sql.= " t.entrepots,";
		$sql.= " t.statut,";
		$sql.= " t.date_created,";
		$sql.= " t.fk_user_created,";
		$sql.= " t.date_modified,";
		$sql.= " t.fk_user_modified,";
		$sql.= " t.date_applied";

		
        $sql.= " FROM ".MAIN_DB_PREFIX.$this->table_element." as t";
        if ($ref) $sql.= " WHERE t.ref = '".$ref."'";
        else $sql.= " WHERE t.rowid = ".$id;

    	dol_syslog(get_class($this)."::fetch");
        $resql=$this->db->query($sql);
        if ($resql)
        {
            if ($this->db->num_rows($resql))
            {
                $obj = $this->db->fetch_object($resql);

                $this->id    = $obj->rowid;
                
				$this->row_id = $obj->row_id;
				$this->name = $obj->name;
				$this->entrepots = $obj->entrepots;
				$this->statut = $obj->statut;
				$this->date_created = $this->db->jdate($obj->date_created);
				$this->fk_user_created = $obj->fk_user_created;
				$this->date_modified = $this->db->jdate($obj->date_modified);
				$this->fk_user_modified = $obj->fk_user_modified;
				$this->date_applied = $this->db->jdate($obj->date_applied);

                
            }
            $this->db->free($resql);

            return 1;
        }
        else
        {
      	    $this->error="Error ".$this->db->lasterror();
            return -1;
        }
    }


    /**
     *  Update object into database
     *
     *  @param	User	$user        User that modifies
     *  @param  int		$notrigger	 0=launch triggers after, 1=disable triggers
     *  @return int     		   	 <0 if KO, >0 if OK
     */
    function update($user, $notrigger=0)
    {
    	global $conf, $langs;
		$error=0;

		// Clean parameters
        
		if (isset($this->row_id)) $this->row_id=trim($this->row_id);
		if (isset($this->name)) $this->name=trim($this->name);
		if (isset($this->entrepots)) $this->entrepots=trim($this->entrepots);
		if (isset($this->statut)) $this->statut=trim($this->statut);
		if (isset($this->fk_user_created)) $this->fk_user_created=trim($this->fk_user_created);
		if (isset($this->fk_user_modified)) $this->fk_user_modified=trim($this->fk_user_modified);

        

		// Check parameters
		// Put here code to add a control on parameters values

        // Update request
        $sql = "UPDATE ".MAIN_DB_PREFIX.$this->table_element." SET";
        
		$sql.= " row_id=".(isset($this->row_id)?$this->row_id:"null").",";
		$sql.= " name=".(isset($this->name)?"'".$this->db->escape($this->name)."'":"null").",";
		$sql.= " entrepots=".(isset($this->entrepots)?"'".$this->db->escape($this->entrepots)."'":"null").",";
		$sql.= " statut=".(isset($this->statut)?$this->statut:"null").",";
		$sql.= " date_created=".(dol_strlen($this->date_created)!=0 ? "'".$this->db->idate($this->date_created)."'" : 'null').",";
		$sql.= " fk_user_created=".(isset($this->fk_user_created)?$this->fk_user_created:"null").",";
		$sql.= " date_modified=".(dol_strlen($this->date_modified)!=0 ? "'".$this->db->idate($this->date_modified)."'" : 'null').",";
		$sql.= " fk_user_modified=".(isset($this->fk_user_modified)?$this->fk_user_modified:"null").",";
		$sql.= " date_applied=".(dol_strlen($this->date_applied)!=0 ? "'".$this->db->idate($this->date_applied)."'" : 'null')."";

        
        $sql.= " WHERE rowid=".$this->id;

		$this->db->begin();

		dol_syslog(__METHOD__);
        $resql = $this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
		{
			if (! $notrigger)
			{
	            // Uncomment this and change MYOBJECT to your own tag if you
	            // want this action calls a trigger.

	            //// Call triggers
	            //$result=$this->call_trigger('MYOBJECT_MODIFY',$user);
	            //if ($result < 0) { $error++; //Do also what you must do to rollback action if trigger fail}
	            //// End call triggers
			 }
		}

        // Commit or rollback
		if ($error)
		{
			foreach($this->errors as $errmsg)
			{
	            dol_syslog(__METHOD__." ".$errmsg, LOG_ERR);
	            $this->error.=($this->error?', '.$errmsg:$errmsg);
			}
			$this->db->rollback();
			return -1*$error;
		}
		else
		{
			$this->db->commit();
			return 1;
		}
    }


 	/**
	 *  Delete object in database
	 *
     *	@param  User	$user        User that deletes
     *  @param  int		$notrigger	 0=launch triggers after, 1=disable triggers
	 *  @return	int					 <0 if KO, >0 if OK
	 */
	function delete($user, $notrigger=0)
	{
		global $conf, $langs;
		$error=0;

		$this->db->begin();

		if (! $error)
		{
			if (! $notrigger)
			{
				// Uncomment this and change MYOBJECT to your own tag if you
		        // want this action calls a trigger.

	            //// Call triggers
	            //$result=$this->call_trigger('MYOBJECT_DELETE',$user);
	            //if ($result < 0) { $error++; //Do also what you must do to rollback action if trigger fail}
	            //// End call triggers
			}
		}

		if (! $error)
		{
    		$sql = "DELETE FROM ".MAIN_DB_PREFIX.$this->table_element;
    		$sql.= " WHERE rowid=".$this->id;

    		dol_syslog(__METHOD__);
    		$resql = $this->db->query($sql);
        	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }
		}

        // Commit or rollback
		if ($error)
		{
			foreach($this->errors as $errmsg)
			{
	            dol_syslog(__METHOD__." ".$errmsg, LOG_ERR);
	            $this->error.=($this->error?', '.$errmsg:$errmsg);
			}
			$this->db->rollback();
			return -1*$error;
		}
		else
		{
			$this->db->commit();
			return 1;
		}
	}



	/**
	 *	Load an object from its id and create a new one in database
	 *
	 *	@param	int		$fromid     Id of object to clone
	 * 	@return	int					New id of clone
	 */
	function createFromClone($fromid)
	{
		global $user,$langs;

		$error=0;

		$object=new Inventairename($this->db);

		$this->db->begin();

		// Load source object
		$object->fetch($fromid);
		$object->id=0;
		$object->statut=0;

		// Clear fields
		// ...

		// Create clone
		$result=$object->create($user);

		// Other options
		if ($result < 0)
		{
			$this->error=$object->error;
			$error++;
		}

		if (! $error)
		{


		}

		// End
		if (! $error)
		{
			$this->db->commit();
			return $object->id;
		}
		else
		{
			$this->db->rollback();
			return -1;
		}
	}


	/**
	 *	Initialise object with example values
	 *	Id must be 0 if object instance is a specimen
	 *
	 *	@return	void
	 */
	function initAsSpecimen()
	{
		$this->id=0;
		
		$this->row_id='';
		$this->name='';
		$this->entrepots='';
		$this->statut='';
		$this->date_created='';
		$this->fk_user_created='';
		$this->date_modified='';
		$this->fk_user_modified='';
		$this->date_applied='';

		
	}

    /**
     *	Return clicable name (with picto eventually)
     *
     *	@param		int		$withpicto		0=_No picto, 1=Includes the picto in the linkn, 2=Picto only
     *	@param		string	$option			Options
     *	@return		string					String with URL
     */
    public function getNomUrl($withpicto=0,$option='')
    {
        global $langs;

        $result='';
        $label = '<u>' . $this->name . '</u>';
        if (! empty($this->ref))
            $label .= '<br><b>' . $langs->trans('Ref') . ':</b> '.$this->ref;

        $link = '<a href="'.dol_buildpath('/custom/inventaire/fiche.php?id='.$this->id,1).'" title="'.dol_escape_htmltag($label, 1).'" class="classfortooltip">';
        $linkend='</a>';

        $picto='inventaire';


        if ($withpicto) $result.=($link.img_object($label, $picto, 'class="classfortooltip"').$linkend);
        if ($withpicto && $withpicto != 2) $result.=' ';
        if ($withpicto != 2) $result.=$link.$this->ref.$linkend;
        return $result;
    }
}
