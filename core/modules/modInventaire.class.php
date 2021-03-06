<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) <year>  <name of author>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * 	\defgroup	mymodule	MyModule module
 * 	\brief		MyModule module descriptor.
 * 	\file		core/modules/modMyModule.class.php
 * 	\ingroup	mymodule
 * 	\brief		Description and activation file for module MyModule
 */
include_once DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php";

/**
 * Description and activation class for module MyModule
 */
class modInventaire extends DolibarrModules
{

	/**
	 * 	Constructor. Define names, constants, directories, boxes, permissions
	 *
	 * 	@param	DoliDB		$db	Database handler
	 */
	public function __construct($db)
	{
		global $langs, $conf;

		// DolibarrModules is abstract in Dolibarr < 3.8
		if (is_callable('parent::__construct')) {
			parent::__construct($db);
		} else {
			$this->db = $db;
		}

		// Id for module (must be unique).
		// Use a free id here
		// (See http://wiki.dolibarr.org/index.php/List_of_modules_id for available ranges).
		$this->numero = 70000;
		// Key text used to identify module (for permissions, menus, etc...)
		$this->rights_class = 'inventaire';

		// Family can be 'crm','financial','hr','projects','products','ecm','technic','other'
		// It is used to group modules in module setup page
		$this->family = "products";
		// Module label (no space allowed)
		// used if translation string 'ModuleXXXName' not found
		// (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i', '', get_class($this));
		// Module description
		// used if translation string 'ModuleXXXDesc' not found
		// (where XXX is value of numeric property 'numero' of module)
		$this->description = "Gestion inventaire stock produit";
		// Possible values for version are: 'development', 'experimental' or version
		$this->version = '0.1.1';
		// Key used in llx_const table to save module status enabled/disabled
		// (where MYMODULE is value of property name of module in uppercase)
		$this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
		// Where to store the module in setup page
		// (0=common,1=interface,2=others,3=very specific)
		$this->special = 2;
		// Name of image file used for this module.
		// If file is in theme/yourtheme/img directory under name object_pictovalue.png
		// use this->picto='pictovalue'
		// If file is in module/img directory under name object_pictovalue.png
		// use this->picto='pictovalue@module'
		$this->picto = 'inventaire@inventaire'; // mypicto@mymodule
		// Defined all module parts (triggers, login, substitutions, menus, css, etc...)
		// for default path (eg: /mymodule/core/xxxxx) (0=disable, 1=enable)
		// for specific path of parts (eg: /mymodule/core/modules/barcode)
		// for specific css file (eg: /mymodule/css/mymodule.css.php)
		$this->module_parts = array(
			// Set this to 1 if module has its own trigger directory
			'triggers' => 1,
		);

		// Data directories to create when module is enabled.
		// Example: this->dirs = array("/mymodule/temp");
		$this->dirs = array();

		// Config pages. Put here list of php pages
		// stored into mymodule/admin directory, used to setup module.
		$this->config_page_url = array("admin_inventaire.php@inventaire");

		// Dependenciesf
		// A condition to hide module
		$this->hidden = false;
		// List of modules class name as string that must be enabled if this module is enabled
		// Example : $this->depends('modAnotherModule', 'modYetAnotherModule')
		$this->depends = array();
		// List of modules id to disable if this one is disabled
		$this->requiredby = array();
		// List of modules id this module is in conflict with
		$this->conflictwith = array();
		// Minimum version of PHP required by module
		$this->phpmin = array(5, 3);
		// Minimum version of Dolibarr required by module
		$this->need_dolibarr_version = array(3, 2);
		// Language files list (langfiles@mymodule)
		$this->langfiles = array("inventaire@inventaire");
		// Constants
		// List of particular constants to add when module is enabled
		// (name, type ['chaine' or ?], value, description, visibility, entity ['current' or 'allentities'], delete on unactive)
		// Example:

        $this->const = array();

        $r++;
        $this->const[$r][0] = "PRODUCT_HORS_VENTE";
        $this->const[$r][1] = "integer";
        $this->const[$r][2] = "0";
        $this->const[$r][3] = "intégrer les produits hors vente dans l'inventaire";
        $this->const[$r][4] = 0;
        $this->const[$r][5] = 1; // supprime la constante à la désactivation du module

        $r++;
        $this->const[$r][0] = "PRODUCT_HORS_ACHAT";
        $this->const[$r][1] = "integer";
        $this->const[$r][2] = "0";
        $this->const[$r][3] = "intégrer les produits hors achat dans l'inventaire";
        $this->const[$r][4] = 0;
        $this->const[$r][5] = 1; // supprime la constante à la désactivation du modules


        $r++;
        $this->const[$r][0] = "PRODUCT_VIRTUAL";
        $this->const[$r][1] = "integer";
        $this->const[$r][2] = "0";
        $this->const[$r][3] = "intégrer les produits virtuel dans l'inventaire";
        $this->const[$r][4] = 0;
        $this->const[$r][5] = 1; // supprime la constante à la désactivation du modules

        $r++;
        $this->const[$r][0] = "FICHEINVENTAIRE_ADDON";
        $this->const[$r][1] = "chaine";
        $this->const[$r][2] = "plaisance";
        $this->const[$r][3] = "module de numérotation";
        $this->const[$r][4] = 0;
        $this->const[$r][5] = 1; // supprime la constante à la désactivation du modules


        // Array to add new pages in new tabs
		// Example:
		$this->tabs = array(
			//	// To add a new tab identified by code tabname1
			//	'objecttype:+tabname1:Title1:langfile@mymodule:$user->rights->mymodule->read:/mymodule/mynewtab1.php?id=__ID__',
			//	// To add another new tab identified by code tabname2
			//	'objecttype:+tabname2:Title2:langfile@mymodule:$user->rights->othermodule->read:/mymodule/mynewtab2.php?id=__ID__',
			//	// To remove an existing tab identified by code tabname
			//	'objecttype:-tabname'
		);
		// 'categories_x'	  to add a tab in category view (replace 'x' by type of category (0=product, 1=supplier, 2=customer, 3=member)
		// 'contact'          to add a tab in contact view
		// 'contract'         to add a tab in contract view
		// 'group'            to add a tab in group view
		// 'intervention'     to add a tab in intervention view
		// 'invoice'          to add a tab in customer invoice view
		// 'invoice_supplier' to add a tab in supplier invoice view
		// 'member'           to add a tab in fundation member view
		// 'opensurveypoll'	  to add a tab in opensurvey poll view
		// 'order'            to add a tab in customer order view
		// 'order_supplier'   to add a tab in supplier order view
		// 'payment'		  to add a tab in payment view
		// 'payment_supplier' to add a tab in supplier payment view
		// 'product'          to add a tab in product view
		// 'propal'           to add a tab in propal view
		// 'project'          to add a tab in project view
		// 'stock'            to add a tab in stock view
		// 'thirdparty'       to add a tab in third party view
		// 'user'             to add a tab in user view

		// Dictionaries
		if (! isset($conf->mymodule->enabled)) {
			$conf->mymodule=new stdClass();
			$conf->mymodule->enabled = 0;
		}
		$this->dictionaries = array();
		/* Example:
		  // This is to avoid warnings
		  if (! isset($conf->mymodule->enabled)) $conf->mymodule->enabled=0;
		  $this->dictionaries=array(
			  'langs'=>'mymodule@mymodule',
			  // List of tables we want to see into dictonnary editor
			  'tabname'=>array(
				  MAIN_DB_PREFIX."table1",
				  MAIN_DB_PREFIX."table2",
				  MAIN_DB_PREFIX."table3"
			  ),
			  // Label of tables
			  'tablib'=>array("Table1","Table2","Table3"),
			  // Request to select fields
			  'tabsql'=>array(
				  'SELECT f.rowid as rowid, f.code, f.label, f.active'
				  . ' FROM ' . MAIN_DB_PREFIX . 'table1 as f',
				  'SELECT f.rowid as rowid, f.code, f.label, f.active'
				  . ' FROM ' . MAIN_DB_PREFIX . 'table2 as f',
				  'SELECT f.rowid as rowid, f.code, f.label, f.active'
				  . ' FROM ' . MAIN_DB_PREFIX . 'table3 as f'
			  ),
			  // Sort order
			  'tabsqlsort'=>array("label ASC","label ASC","label ASC"),
			  // List of fields (result of select to show dictionary)
			  'tabfield'=>array("code,label","code,label","code,label"),
			  // List of fields (list of fields to edit a record)
			  'tabfieldvalue'=>array("code,label","code,label","code,label"),
			  // List of fields (list of fields for insert)
			  'tabfieldinsert'=>array("code,label","code,label","code,label"),
			  // Name of columns with primary key (try to always name it 'rowid')
			  'tabrowid'=>array("rowid","rowid","rowid"),
			  // Condition to show each dictionary
			  'tabcond'=>array(
				  $conf->mymodule->enabled,
				  $conf->mymodule->enabled,
				  $conf->mymodule->enabled
			  )
		  );
		 */

		// Boxes
		// Add here list of php file(s) stored in core/boxes that contains class to show a box.
		$this->boxes = array(); // Boxes list
		// Example:
		$this->boxes = array(
			0 => array(
				'file' => 'mybox@inventaire',
				'note' => '',
				'enabledbydefaulton' => 'Home'
			)
		);

		// Permissions
		$this->rights = array(); // Permission array used by this module
		$r = 0;

		// Add here list of permission defined by
		// an id, a label, a boolean and two constant strings.
		// Example:
		//// Permission id (must not be already used)
		//$this->rights[$r][0] = 2000;
		//// Permission label
		//$this->rights[$r][1] = 'Permision label';
		//// Permission by default for new user (0/1)
		//$this->rights[$r][3] = 1;
		//// In php code, permission will be checked by test
		//// if ($user->rights->permkey->level1->level2)
		//$this->rights[$r][4] = 'level1';
		//// In php code, permission will be checked by test
		//// if ($user->rights->permkey->level1->level2)
		//$this->rights[$r][5] = 'level2';
		//$r++;
		// Main menu entries

		// Add here entries to declare new menus
		//
		// Example to declare a new Top Menu entry and its Left menu entry:
		//$this->menu[]=array(
		//	// Put 0 if this is a top menu
		//	'fk_menu'=>0,
		//	// This is a Top menu entry
		//	'type'=>'top',
		// Menu's title. FIXME: use a translation key
		//	'titre'=>'MyModule top menu',
		// This menu's mainmenu ID
		//	'mainmenu'=>'mymodule',
		// This menu's leftmenu ID
		//	'leftmenu'=>'mymodule',
		//	'url'=>'/mymodule/pagetop.php',
		//	// Lang file to use (without .lang) by module.
		//	// File must be in langs/code_CODE/ directory.
		//	'langs'=>'mylangfile',
		//	'position'=>100,
		//	// Define condition to show or hide menu entry.
		//	// Use '$conf->mymodule->enabled' if entry must be visible if module is enabled.
		//	'enabled'=>'$conf->mymodule->enabled',
		//	// Use 'perms'=>'$user->rights->mymodule->level1->level2'
		//	// if you want your menu with a permission rules
		//	'perms'=>'1',
		//	'target'=>'',
		//	// 0=Menu for internal users, 1=external users, 2=both
		//	'user'=>2
		//);
		//$this->menu[]=array(
		//	// Use r=value where r is index key used for the parent menu entry
		//	// (higher parent must be a top menu entry)
		//	'fk_menu'=>'r=0',
		//	// This is a Left menu entry
		//	'type'=>'left',
		// Menu's title. FIXME: use a translation key
		//	'titre'=>'MyModule left menu',
		// This menu's mainmenu ID
		//	'mainmenu'=>'mymodule',
		// This menu's leftmenu ID
		//	'leftmenu'=>'mymodule',
		//	'url'=>'/mymodule/pagelevel1.php',
		//	// Lang file to use (without .lang) by module.
		//	// File must be in langs/code_CODE/ directory.
		//	'langs'=>'mylangfile',
		//	'position'=>100,
		//	// Define condition to show or hide menu entry.
		//	// Use '$conf->mymodule->enabled' if entry must be visible if module is enabled.
		//	'enabled'=>'$conf->mymodule->enabled',
		//	// Use 'perms'=>'$user->rights->mymodule->level1->level2'
		//	// if you want your menu with a permission rules
		//	'perms'=>'1',
		//	'target'=>'',
		//	// 0=Menu for internal users, 1=external users, 2=both
		//	'user'=>2
		//);
		//
		// Example to declare a Left Menu entry into an existing Top menu entry:
		//$this->menu[]=array(
		//	// Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy'
		//	'fk_menu'=>'fk_mainmenu=mainmenucode',
		//	// This is a Left menu entry
		//	'type'=>'left',
		// Menu's title. FIXME: use a translation key
		//	'titre'=>'MyModule left menu',
		// This menu's mainmenu ID
		//	'mainmenu'=>'mainmenucode',
		// This menu's leftmenu ID
		//	'leftmenu'=>'mymodule',
		//	'url'=>'/mymodule/pagelevel2.php',
		//	// Lang file to use (without .lang) by module.
		//	// File must be in langs/code_CODE/ directory.
		//	'langs'=>'mylangfile',
		//	'position'=>100,
		//	// Define condition to show or hide menu entry.
		//	// Use '$conf->mymodule->enabled' if entry must be visible if module is enabled.
		//	// Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
		//	'enabled'=>'$conf->mymodule->enabled',
		//	// Use 'perms'=>'$user->rights->mymodule->level1->level2'
		//	// if you want your menu with a permission rules
		//	'perms'=>'1',
		//	'target'=>'',
		//	// 0=Menu for internal users, 1=external users, 2=both
		//	'user'=>2
		//);
        // Main menu entries
		$this->menu = array();			// List of menus to add
		$r=0;
		$this->menu[$r]=array('fk_menu'=>'fk_mainmenu=products',
            'type'=>'left',
            'titre'=>'Inventaires',
            'mainmenu'=>'inventaire',
            'leftmenu'=>'inventaire',
            'url'=>'/inventaire/index.php?leftmenu=inventaire',
            'langs'=>'inventaire@inventaire',	// Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
            'position'=>102,
            'perms'=>'',
            'enabled'=>'',
            'target'=>'',
            'user'=>2);
		$r++;

		// Exports
		$r = 0;

		// Example:
        $r++;
		$this->export_code[$r]=$this->rights_class.'_'.$r;
		//// Translation key (used only if key ExportDataset_xxx_z not found)
		$this->export_label[$r]='InventaireExport';
		//// Condition to show export in list (ie: '$user->id==3').
		//// Set to 1 to always show when module is enabled.
		$this->export_enabled[$r]='1';
		$this->export_permission[$r]=array();
		$this->export_fields_array[$r]=array(
			'i.row_id'=>"Inventaire id",
			'i.ref'=>'Ref. invenataire',
			'i.date_created'=>'Date inventaire',
            'p.label'=>'Produit',
            'p.ref'=>'ref. produit',
            'idp.reel'=>'Stock physique',
            'idp.pmp'=>'PMP',

		);

        /* TYPE FIELD ENTREPOT*/
        $this->export_TypeFields_array[$r]=array(
            'i.row_id'=>"Numeric",
            'i.ref'=>'Text',
        );

		$this->export_entities_array[$r]=array(
            'i.row_id'=>"inventaire id",
			'i.ref'=>'Ref. inventaire',
            'i.date_created'=>'Date inventaire',
            'p.label'=>'produit',
            'p.ref'=>'ref. produit',
            'idp.reel'=>'Stock Physique',
            'idp.pmp'=>'PMP',


		);
		$this->export_sql_start[$r] = 'SELECT  ';
		$this->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'inventaire_name as i
		  INNER JOIN '.MAIN_DB_PREFIX.'inventaire_app as idp ON (idp.k_inventaire_id=i.row_id) ';
		$this->export_sql_end[$r] .= ' INNER JOIN  ' . MAIN_DB_PREFIX
            . 'product as p on (p.rowid = idp.k_product_id) ';
        $this->export_sql_end[$r] .= ' WHERE 1 ';
		$r++;

		// Can be enabled / disabled only in the main company when multi-company is in use
		// $this->core_enabled = 1;
	}

	/**
	 * Function called when module is enabled.
	 * The init function add constants, boxes, permissions and menus
	 * (defined in constructor) into Dolibarr database.
	 * It also creates data directories
	 *
	 * 	@param		string	$options	Options when enabling module ('', 'noboxes')
	 * 	@return		int					1 if OK, 0 if KO
	 */
	public function init($options = '')
	{
		$sql = array();

		$result = $this->loadTables();

		return $this->_init($sql, $options);
	}

	/**
	 * Function called when module is disabled.
	 * Remove from database constants, boxes and permissions from Dolibarr database.
	 * Data directories are not deleted
	 *
	 * 	@param		string	$options	Options when enabling module ('', 'noboxes')
	 * 	@return		int					1 if OK, 0 if KO
	 */
	public function remove($options = '')
	{
		$sql = array();

		return $this->_remove($sql, $options);
	}

	/**
	 * Create tables, keys and data required by module
	 * Files llx_table1.sql, llx_table1.key.sql llx_data.sql with create table, create keys
	 * and create data commands must be stored in directory /mymodule/sql/
	 * This function is called by this->init
	 *
	 * 	@return		int		<=0 if KO, >0 if OK
	 */
	private function loadTables()
	{
		return $this->_load_tables('/inventaire/sql/');
	}
}
