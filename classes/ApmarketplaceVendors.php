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

class ApmarketplaceVendors extends ObjectModel
{
    public $id_apmarketplace_vendor;
    public $user_name;
    public $first_name;
    public $last_name;
    public $email;
    public $image;
    public $phone;
    public $freeplan;
    public $fb;
    public $tt;
    public $fax;
    public $ins;
    public $url_shop;
    public $pass_word;
    public $date_add;
    public $active;

    public static $definition = array(
        'table' => 'apmarketplace_vendor',
        'primary' => 'id_apmarketplace_vendor',
        'multilang' => false,
        'multilang_shop' => true,
        'fields' => array(
            'user_name' =>       array('type' => self::TYPE_STRING, 'validate'),
            'first_name' =>      array('type' => self::TYPE_STRING, 'validate'),
            'last_name' =>       array('type' => self::TYPE_STRING, 'validate'),
            'email' =>           array('type' => self::TYPE_STRING, 'validate'),
            'image' =>           array('type' => self::TYPE_STRING),
            'phone' =>           array('type' => self::TYPE_INT, 'validate'),
            'freeplan' =>           array('type' => self::TYPE_INT),
            'fb' =>              array('type' => self::TYPE_STRING),
            'tt' =>              array('type' => self::TYPE_STRING),
            'fax' =>             array('type' => self::TYPE_INT),
            'ins' =>             array('type' => self::TYPE_STRING),
            'url_shop' =>        array('type' => self::TYPE_STRING),
            'pass_word' =>       array('type' => self::TYPE_STRING,'validate'),
            'date_add' =>        array('type' => self::TYPE_DATE),
            'active' =>          array('type' => self::TYPE_INT),
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        # Insert multi_shop
        $this->def['table'] = self::$definition['table'];
        ShopCore::addTableAssociation($this->def['table'], array('type' => 'shop'));

        parent::__construct($id, $id_lang, $id_shop);
    }

    public function checkUserVendor($user_name)
    {
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'apmarketplace_vendor`
        WHERE `user_name` = "'.pSQL($user_name).'"';
        return Db::getInstance()->executeS($sql);
    }

    public function deleteVendor($id)
    {
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'apmarketplace_vendor`
            WHERE id_apmarketplace_vendor = ' . (int)$id);
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'apmarketplace_vendor_shop`
            WHERE id_apmarketplace_vendor = ' . (int)$id);
    }

    public function updateVendor($obj)
    {
        if ($obj->image != '') {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'apmarketplace_vendor`
                SET `image` = "'.pSQL($obj->image).'"
                WHERE id_apmarketplace_vendor = ' . (int)$obj->id_apmarketplace_vendor);
        }

        if ($obj->first_name != '') {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'apmarketplace_vendor`
                SET `first_name` = "'.pSQL($obj->first_name).'"
                WHERE id_apmarketplace_vendor = ' . (int)$obj->id_apmarketplace_vendor);
        }

        if ($obj->last_name != '') {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'apmarketplace_vendor`
                SET `last_name` = "'.pSQL($obj->last_name).'"
                WHERE id_apmarketplace_vendor = ' . (int)$obj->id_apmarketplace_vendor);
        }

        if ($obj->email != '') {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'apmarketplace_vendor`
                SET `email` = "'.pSQL($obj->email).'"
                WHERE id_apmarketplace_vendor = ' . (int)$obj->id_apmarketplace_vendor);
        }

        if ($obj->phone != '') {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'apmarketplace_vendor`
                SET `phone` = "'.pSQL($obj->phone).'"
                WHERE id_apmarketplace_vendor = ' . (int)$obj->id_apmarketplace_vendor);
        }

        if ($obj->freeplan != '') {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'apmarketplace_vendor`
                SET `freeplan` = "'.pSQL($obj->freeplan).'"
                WHERE id_apmarketplace_vendor = ' . (int)$obj->id_apmarketplace_vendor);
        }

        if ($obj->fb != '') {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'apmarketplace_vendor`
                SET `fb` = "'.pSQL($obj->fb).'"
                WHERE id_apmarketplace_vendor = ' . (int)$obj->id_apmarketplace_vendor);
        }

        if ($obj->tt != '') {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'apmarketplace_vendor`
                SET `tt` = "'.pSQL($obj->tt).'"
                WHERE id_apmarketplace_vendor = ' . (int)$obj->id_apmarketplace_vendor);
        }

        if ($obj->fax != '') {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'apmarketplace_vendor`
                SET `fax` = "'.pSQL($obj->fax).'"
                WHERE id_apmarketplace_vendor = ' . (int)$obj->id_apmarketplace_vendor);
        }

        if ($obj->ins != '') {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'apmarketplace_vendor`
                SET `ins` = "'.pSQL($obj->ins).'"
                WHERE id_apmarketplace_vendor = ' . (int)$obj->id_apmarketplace_vendor);
        }

        if ($obj->url_shop != '') {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'apmarketplace_vendor`
                SET `url_shop` = "'.pSQL($obj->url_shop).'"
                WHERE id_apmarketplace_vendor = ' . (int)$obj->id_apmarketplace_vendor);
        }

        if ($obj->pass_word != '') {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'apmarketplace_vendor`
                SET `pass_word` = "'.pSQL($obj->pass_word).'"
                WHERE id_apmarketplace_vendor = ' . (int)$obj->id_apmarketplace_vendor);
        }

        if ($obj->active != '') {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'apmarketplace_vendor`
                SET `active` = "'.pSQL($obj->active).'"
                WHERE id_apmarketplace_vendor = ' . (int)$obj->id_apmarketplace_vendor);
        }
    }

    public function getAllVendors()
    {
        $sql = 'SELECT `id_apmarketplace_vendor`, `first_name`, `last_name`, `email`, `image`, `phone`
        FROM `'._DB_PREFIX_.'apmarketplace_vendor`
        WHERE active = 1';
        return Db::getInstance()->executeS($sql);
    }

    public function checkLogin($user, $pass)
    {
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'apmarketplace_vendor`
        WHERE `user_name` = "'.pSQL($user).'" AND `pass_word` = "'.pSQL($pass).'"';
        return Db::getInstance()->executeS($sql);
    }
}
