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

class ApmarketplaceOrder extends ObjectModel
{
    public $id_apmarketplace_order;
    public $id_order;
    public $id_product;
    public $id_vendor;
    public $payment;

    public static $definition = array(
        'table' => 'apmarketplace_order',
        'primary' => 'id_apmarketplace_order',
        'multilang' => false,
        'multilang_shop' => false,
        'fields' => array(
            'id_order' =>                   array('type' => self::TYPE_INT),
            'id_product' =>                 array('type' => self::TYPE_INT),
            'id_vendor' =>                  array('type' => self::TYPE_INT),
            'payment' =>                    array('type' => self::TYPE_BOOL),
        ),
    );

    public function add($autodate = true, $null_values = false)
    {
        return parent::add($autodate, $null_values);
    }

    public function getByValidate($validate)
    {
        $sql = 'SELECT o.*, p.`name`, v.`first_name`, v.`last_name`, v.`email`, o_d.`total_price_tax_incl`,
                (o_d.`total_price_tax_incl` *
                (100 - '.Configuration::get('APMARKETPLACE_CONFIG_COMMISSION_VALUE', null).') / 100)
                as total
                FROM `'._DB_PREFIX_.'apmarketplace_order` o
                LEFT JOIN `'._DB_PREFIX_.'product_lang` p
                ON o.`id_product` = p.`id_product`
                LEFT JOIN `'._DB_PREFIX_.'apmarketplace_vendor` v
                ON o.`id_vendor` = v.`id_apmarketplace_vendor`
                LEFT JOIN `'._DB_PREFIX_.'order_detail` o_d
                ON o.`id_order` = o_d.`id_order`
                AND o.`id_product` = o_d.`product_id` 
                WHERE o.`payment` = '.(int)$validate.' AND p.`id_lang` = ' . (int)Context::getContext()->language->id;
        $orders = Db::getInstance()->executeS($sql);
        return $orders;
    }

    public function getIdProductByIdVendor($id_vendor, $id_product)
    {
        $sql = 'SELECT DISTINCT `id_product`
                FROM '._DB_PREFIX_.'apmarketplace_order
                WHERE `id_vendor` = '.(int)$id_vendor.' AND `id_product` = ' . (int)$id_product;
        return Db::getInstance()->executeS($sql);
    }

    public function getOrderByIdVendor($id_vendor, $id_product)
    {
        $sql = 'SELECT DISTINCT `id_order`
                FROM '._DB_PREFIX_.'apmarketplace_order
                WHERE `id_vendor` = '.(int)$id_vendor.' AND `id_product` = ' . (int)$id_product;
        return Db::getInstance()->executeS($sql);
    }

    public function validate()
    {
        if (!Validate::isUnsignedId($this->id_apmarketplace_order)) {
            return false;
        }
        $success = (Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'apmarketplace_order` SET
            `payment` = 1
            WHERE `id_apmarketplace_order` = '.(int)$this->id_apmarketplace_order)
        );

        Hook::exec('actionObjectOrderValidateAfter', array('object' => $this));
        return $success;
    }
}
