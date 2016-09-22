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


$res = 0;
if (!$res && file_exists("../main.inc.php"))
    $res = @include("../main.inc.php");
if (!$res && file_exists("../../main.inc.php"))
    $res = @include("../../main.inc.php");
if (!$res && file_exists("../../../main.inc.php"))
    $res = @include("../../../main.inc.php");
if (!$res && file_exists("../../../../main.inc.php"))
    $res = @include("../../../../main.inc.php");
if (!$res && file_exists("../../../dolibarr/htdocs/main.inc.php"))
    $res = @include("../../../dolibarr/htdocs/main.inc.php");     // Used on dev env only
if (!$res && file_exists("../../../../dolibarr/htdocs/main.inc.php"))
    $res = @include("../../../../dolibarr/htdocs/main.inc.php");   // Used on dev env only
if (!$res && file_exists("../../../../../dolibarr/htdocs/main.inc.php"))
    $res = @include("../../../../../dolibarr/htdocs/main.inc.php");   // Used on dev env only
if (!$res)
    die("Include of main fails");
dol_include_once('/inventaire/class/inventaire.class.php');
dol_include_once('/inventaire/class/entrepot.inventaire.class.php');
require_once  'vendor/autoload.php';
$langs->load("inventaire@inventaire");
$Inventaire = new Inventaire($db);
$Entrepot =  new InventaireEntrepot($db);
/*
 * ACTION
 */

$entrepot=$Entrepot->listAll();

$resultat = array();
$sql = "SELECT * ";
$sql.= " FROM ".MAIN_DB_PREFIX."inventaire_name as i";
$sql.= " WHERE statut >= 0";
$sql.= " ORDER BY date_created DESC ";
$resql=$db->query($sql);
if ($resql)
{
    $num = $db->num_rows($resql);
    if ($num)
    {

        $i = 0;
        $var = True;
        while ($i < $num)
        {
            $var=!$var;
            $obj = $db->fetch_object($resql);

            $Inventaire->id = $obj->row_id;
            $Inventaire->ref = $obj->ref;
            $Inventaire->name = $obj->name;

            $obj->linkfiche = $Inventaire->getNomUrl(); //'<a href="'.dol_buildpath('/custom/inventaire/fiche.php?id='.$obj->row_id,1).'">'.$obj->name.'</a>';
            $obj->date_created = date("d-m-Y",$db->jdate($obj->date_created));
            if($obj->statut < 3)
                $obj->linkaction = ($obj->date_modified !='0000-00-00 00:00:00') ? date("d-m-Y",$db->jdate($obj->date_modified)) : '' ;
            else
                $obj->linkaction = ($obj->date_applied !='0000-00-00 00:00:00') ? date("d-m-Y",$db->jdate($obj->date_applied)) : '' ;


            $obj->entrepot_list='';
            foreach( $entrepot as $key=>$row)
                if(in_array($key, explode(',',$obj->entrepots)))
                    $obj->entrepot_list .=$row->label;


            if( empty($obj->entrepot_list) )
                $obj->entrepot_list = 'All';

            $resultat[] = $obj;
            $i++;
        }

    }
}

/* * *************************************************
 * VIEW
 *
 * Put here all code to build page
 * ************************************************** */

llxHeader('',$langs->trans("Sendings"),$helpurl);
print_fiche_titre($langs->trans("InventaireArea"));

dol_include_once('/inventaire/tpl/index.liste.tpl');

// End of page
llxFooter();