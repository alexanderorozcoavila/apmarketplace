<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
* Do not edit or add to this file if you wish to upgrade Prestashop to newer
* versions in the future.
*
*  @author     Apollotheme
*  @copyright  Apollotheme
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require_once _PS_MODULE_DIR_ . 'apmarketplace/includer.php';

class ApmarketplaceProduct extends ObjectModel
{
    public $id_apmarketplace_product;
    public $id_product;
    public $id_apmarketplace_vendor;

    public static $definition = array(
        'table' => 'apmarketplace_product',
        'primary' => 'id_apmarketplace_product',
        'multilang' => false,
        'fields' => array(
            'id_product' =>                 array('type' => self::TYPE_INT),
            'id_apmarketplace_vendor' =>    array('type' => self::TYPE_INT),
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        $this->context = Context::getContext();
        $this->id_lang = Context::getContext()->language->id;
        parent::__construct($id, $id_lang, $id_shop);
    }

    public function add($autodate = true, $null_values = false)
    {
        return parent::add($autodate, $null_values);
    }

    public function deleteProduct($id_vendor, $id_product)
    {
        $sql = 'DELETE FROM `'._DB_PREFIX_.'apmarketplace_product`
        WHERE `id_product` = '.(int)$id_product.' AND `id_apmarketplace_vendor` = ' . (int)$id_vendor;
        Db::getInstance()->execute($sql);
    }

    public function getProductVendor($id_vendor, $id_product)
    {
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'apmarketplace_product`
        WHERE `id_product` = ' . (int)$id_product . ' AND `id_apmarketplace_vendor` = ' . (int)$id_vendor;
        return Db::getInstance()->executeS($sql);
    }

    public function getVendorId($id_product)
    {
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'apmarketplace_product`
        WHERE `id_product` = '. (int)$id_product;
        return Db::getInstance()->executeS($sql);
    }

    public function getProductByIdVendor($id_vendor)
    {
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'apmarketplace_product`
        WHERE `id_apmarketplace_vendor` = ' . (int)$id_vendor;
        return Db::getInstance()->executeS($sql);
    }
    
    public function getProductByVendor($id_apmarketplace_vendor, $page)
    {
        if ($page == '') {
            $sql = 'SELECT * FROM `'._DB_PREFIX_.'apmarketplace_product`
            WHERE `id_apmarketplace_vendor` = ' . (int)$id_apmarketplace_vendor;
        } else {
            $number = (int)Configuration::get('PS_PRODUCTS_PER_PAGE');
            $limit = ($page - 1) * $number;
            $sql = 'SELECT * FROM `'._DB_PREFIX_.'apmarketplace_product`
            WHERE `id_apmarketplace_vendor` = ' . (int)$id_apmarketplace_vendor . '
            LIMIT '. (int)$limit .', ' . (int)$number;
        }
        return Db::getInstance()->executeS($sql);
    }
}
