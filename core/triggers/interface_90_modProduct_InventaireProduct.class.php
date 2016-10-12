<?php
/* Copyright (C) 2005-2014 Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2005-2014 Regis Houssin		<regis.houssin@capnetworks.com>
 * Copyright (C) 2014      Marcos García		<marcosgdf@gmail.com>
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
 *  \file       htdocs/prestashop/core/triggers/interface_99_modProduct_PrestashopProduct.class.php
 *  \ingroup    core
 *  \brief      Fichier de demo de personalisation des actions du workflow
 *  \remarks    Son propre fichier d'actions peut etre cree par recopie de celui-ci:
 *              - Le nom du fichier doit etre: interface_90_modProduct_Prestashop.class.php
 *				                           ou: interface_90_modProduct_Prestashop.class.php
 *              - Le fichier doit rester stocke dans prestashop/core/triggers
 *              - Le nom de la classe doit etre InterfacePrestashop
 *              - Le nom de la propriete name doit etre Mytrigger
 */
require_once DOL_DOCUMENT_ROOT . '/inventaire/headerfile.php';
require_once dirname(__FILE__) . '/../../vendor/autoload.php';

/**
 *  Class of triggers for demo module
 */
class InterfaceInventaireProduct extends InventaireTriggers
{

    public $family = 'InventaireProduct';
    public $picto = 'technic';
    public $description = "Triggers of this module are empty functions. They have no effect. They are provided for tutorial purpose only.";
    public $version = self::VERSION_DOLIBARR;

    /**
     * Function called when a Dolibarrr business event is done.
     * All functions "runTrigger" are triggered if file is inside directory htdocs/core/triggers or htdocs/module/code/triggers (and declared)
     *
     * @param string		$action		Event action code
     * @param Object		$object     Object
     * @param User		    $user       Object user
     * @param Translate 	$langs      Object langs
     * @param conf		    $conf       Object conf
     * @return int         				<0 if KO, 0 if no triggered ran, >0 if OK
     */
    public function runTrigger($action, $object, User $user, Translate $langs, Conf $conf)
    {
        global $db;


        try{
            switch ($action) {

                case 'PRODUCT_CREATE':
                    break;

                case 'PRODUCT_MODIFY':
                case 'PRODUCT_PRICE_MODIFY':

                    break;

                case 'PRODUCT_DELETE':

                    break;

                case 'STOCK_MOVEMENT':
                    $_product = new Product($db);
                    $_product->fetch($object->product_id);
                    //var_dump($_product);
                    //var_dump($object);
                    $_pmp= new Ry\app\ProductPmp($_product);
                    //sauvegarde dans le table llx_product_pmp current pmp
                    $garde= $_pmp->insertPmp();


                    break;
                case 'PRODUCT_VIRTUAL_CREATE':


                    break;

                default:
                    dol_syslog("Trigger '".$this->name."' for action '$action' launched by ".__FILE__.". id=".$object->id);
                    break;
            }

            return 0;
        }catch (Exception $e){
            $e->getTrace();
            echo $e->getMessage();
            return false;
        }
    }

}