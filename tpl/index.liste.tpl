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
global $langs, $resultat;
?>

<table class="noborder" width="100%">
    <tr class="liste_titre">
        <td><?php echo $langs->trans("InventaireId") ?></td>
        <td><?php echo $langs->trans("InventaireName") ?></td>
        <td><?php echo $langs->trans("InventaireForEntrepot") ?></td>
        <td><?php echo $langs->trans("InventaireCreated") ?></td>
        <td><?php echo $langs->trans("InventaireFixed") ?></td>
    </tr>

    <?php foreach($resultat as $row ):  ?>
    <tr <?php echo $bc[$var] ?>>
    <td nowrap="nowrap">
        <?php echo $row->row_id; ?>
    </td>
    <td>
        <?php echo $row->linkfiche ; ?>
    </td>
    <td>
        <?php echo $row->entrepot_list ; ?>
    </td>
    <td>
        <?php echo $row->date_created;  ?>
    <td>
        <?php echo $row->linkaction; ?>
    </td>
    </tr>
    <?php endforeach; ?>
</table>
<div class="tabsAction">
    <a href="fiche.php?action=add" class="butAction"><?php echo $langs->trans("InventaireInitButton") ?></a>
</div>
