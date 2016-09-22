<?php
/* Copyright (C) 2014		 Oscim       <oscim@users.sourceforge.net>
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
 * or see http://www.gnu.org/
 */

/**
 *	\file       htdocs/core/lib/inventaire.lib.php
 *	\brief      Ensemble de fonctions de base pour le module message
 *	\ingroup    message
 */




function InventairePrepareHead() {
	global $langs, $conf, $object,$entrepot, $Entrepot ;

	$langs->load("inventaire@inventaire");

	
	$h = 0;
	$head = array();

	$head[$h][0] = dol_buildpath("/inventaire/fiche.php?id=".$object->id, 1);
	$head[$h][1] = $langs->trans("Card");
	$head[$h][2] = 'card';
	$h++;

	$list = explode(',',$object->entrepots);
	foreach($entrepot as $key=>$row) 
		if(in_array($key, $list)){
	
	$head[$h][0] = dol_buildpath("/inventaire/products.php?id=".$object->id.'&entrepotid='.$key, 1);
	$head[$h][1] = $row->label;
	$head[$h][2] = 'products'.$key;
	$h++;
	}
	
	

	
// 	complete_head_from_modules($conf, $langs, $object, $head, $h, 'inventaire');

// 	complete_head_from_modules($conf, $langs, $object, $head, $h, 'inventaire', 'remove');

	return $head;
}




?>
