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

class ApmarketplacediscountlistModuleFrontController extends ModuleFrontController
{
    public $php_self;
    protected $template_path = '';
    public $mod_product;

    public function __construct()
    {
        $this->id_lang = Context::getContext()->language->id;
        $this->id_shop = Context::getContext()->shop->id;
        $this->context = Context::getContext();
        $this->id_vendor = 0;
        if ($this->context->cookie->__isset('cookie_vendor')) {
            $this->id_vendor = $this->context->cookie->cookie_vendor;
        }
        parent::__construct();
    }

    public function initContent()
    {
        $vars = array();
        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'discountlist') {
            $baseurl = $this->context->shop->getBaseURL(true, true);
            if ($this->context->cookie->__isset('cookie_vendor')) {
                $this->addJS($baseurl.'modules/apmarketplace/views/js/datetimepicker.js');
                $this->addJS($baseurl.'modules/apmarketplace/views/js/discount.js');
                $vars['baseurl'] = $baseurl;
                $vars['check'] = 1;
                $vendor = new ApmarketplaceVendors($this->id_vendor);
                if ($vendor->active == 0) {
                    $vars['check'] = 0;
                }
                $_product = new ApmarketplaceProduct();
                $_product = $_product->getProductByIdVendor($this->id_vendor);
                $id_product = '';
                $where = '';
                if (!empty($_product)) {
                    foreach ($_product as $val_p) {
                        $id_product .= ',' . $val_p['id_product'];
                    }
                    $id_product = ltrim($id_product, ',');
                    $where .= ' AND cr.`gift_product` IN  (' . pSQL($id_product) . ') ';
                }

                if (Tools::getIsset('cart_ruleFilter_id_cart_rule') &&
                    Tools::getValue('cart_ruleFilter_id_cart_rule') != ''
                ) {
                    $vars['id_cart_rule'] = Tools::getValue('cart_ruleFilter_id_cart_rule');
                    $where .= ' AND cr.`id_cart_rule` = ' . Tools::getValue('cart_ruleFilter_id_cart_rule');
                }

                if (Tools::getIsset('cart_ruleFilter_name') && Tools::getValue('cart_ruleFilter_name') != '') {
                    $vars['name'] = Tools::getValue('cart_ruleFilter_name');
                    $where .= ' AND crl.`name` LIKE "%'.Tools::getValue('cart_ruleFilter_name').'%"';
                }

                if (Tools::getIsset('cart_ruleFilter_priority') && Tools::getValue('cart_ruleFilter_priority') != '') {
                    $vars['priority'] = Tools::getValue('cart_ruleFilter_priority');
                    $where .= ' AND cr.`priority` = ' . Tools::getValue('cart_ruleFilter_priority');
                }

                if (Tools::getIsset('cart_ruleFilter_code') && Tools::getValue('cart_ruleFilter_code') != '') {
                    $vars['code'] = Tools::getValue('cart_ruleFilter_code');
                    $where .= ' AND cr.`code` LIKE "%'.Tools::getValue('cart_ruleFilter_code').'%"';
                }

                if (Tools::getIsset('cart_ruleFilter_quantity') && Tools::getValue('cart_ruleFilter_quantity') != '') {
                    $vars['quantity'] = Tools::getValue('cart_ruleFilter_quantity');
                    $where .= ' AND cr.`quantity` = ' . Tools::getValue('cart_ruleFilter_quantity');
                }

                if (Tools::getIsset('cart_ruleFilter_active') && Tools::getValue('cart_ruleFilter_active') != '') {
                    $vars['active'] = Tools::getValue('cart_ruleFilter_active');
                    $where .= ' AND cr.`active` = ' . Tools::getValue('cart_ruleFilter_active');
                }

                $vars['discounts'] = $this->getDiscount($where);
                $vars['count'] = sizeof($vars['discounts']);
                $this->setTemplate('module:apmarketplace/views/templates/front/discount/discount.tpl');
                $this->context->smarty->assign($vars);
                parent::initContent();
            } else {
                Tools::redirect($baseurl . 'vendorlogin?login=1');
            }
        }
    }

    public function getDiscount($where)
    {
        $sql = 'SELECT cr.*, crl.*
                FROM ' . _DB_PREFIX_ . 'cart_rule cr
                LEFT JOIN ' . _DB_PREFIX_ . 'cart_rule_lang crl
                ON (cr.id_cart_rule = crl.id_cart_rule)
                WHERE crl.id_lang = '. (int)$this->id_lang .'' . pSQL($where);
        return Db::getInstance()->executeS($sql);
    }
}
