<?php
/*
 * Copyright (C) 2013		 		Oscim					       <dom@oscim.fr>
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
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
global $langs, $resultat, $object, $entrepot, $Entrepot;

// print_r($object); exit;

$list = explode(',',$object->entrepots);
$i=0;

?>
<div class="tabBar">
    <table class="border" width="100%">
        <tr>
            <td><?php echo $langs->trans("InventaireRef") ?></td>
            <td><?php echo $object->ref ?></td>
            <td><?php echo $langs->trans("InventaireId") ?></td>
            <td><?php echo $object->id ?></td>
        </tr>
        <tr>
            <td><?php echo $langs->trans("InventaireName") ?></td>
            <td><?php echo $object->name ?></td>
            <td><?php echo $langs->trans("Inventairedate") ?></td>
            <td><?php echo date("d-m-Y",$object->date_created) ?></td>
        </tr>
        <?php

		foreach( $entrepot as $key=>$row)
        if(in_array($key, $list)) :
        $Entrepot->id = $key;
        $Entrepot->libelle = $row->label;
        ?>
        <tr>
            <?php if($i==0) : ?>
            <td rowspan="<?php echo count($list) ?>"><?php echo $langs->trans("InventaireEntrepot") ?></td>
            <?php endif; ?>
            <td colspan="3"><?php echo $Entrepot->getNomUrl() ?></td>
        </tr>
        <?php $i++; endif; ?>

    </table>

</div>