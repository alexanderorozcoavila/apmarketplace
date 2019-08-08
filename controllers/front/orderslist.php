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

class ApmarketplaceorderslistModuleFrontController extends ModuleFrontController
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
        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'orderslist') {
            $baseurl = $this->context->shop->getBaseURL(true, true);
            if ($this->context->cookie->__isset('cookie_vendor')) {
                $this->addJS($baseurl.'modules/apmarketplace/views/js/product.js');
                $vars['baseurl'] = $baseurl;
                $vars['check'] = 1;
                $vendor = new ApmarketplaceVendors($this->id_vendor);
                if ($vendor->active == 0) {
                    $vars['check'] = 0;
                }
                $where = 'AND a.id_shop IN ('.$this->id_shop.')';
                if (Tools::getIsset('orderFilter_id_order') && Tools::getValue('orderFilter_id_order') != '') {
                    $where .= ' AND a.`id_order` = ' .Tools::getValue('orderFilter_id_order');
                    $vars['id_order'] = Tools::getValue('orderFilter_id_order');
                }
                if (Tools::getIsset('orderFilter_reference') && Tools::getValue('orderFilter_reference') != '') {
                    $where .= ' AND a.`reference` LIKE "%'.Tools::getValue('orderFilter_reference').'%"';
                    $vars['reference'] = Tools::getValue('orderFilter_reference');
                }
                if (Tools::getIsset('orderFilter_new') && Tools::getValue('orderFilter_new') != '') {
                    $vars['new'] = Tools::getValue('orderFilter_new');
                }
                if (Tools::getIsset('orderFilter_country') && Tools::getValue('orderFilter_country') != '') {
                    $where .= ' AND country_lang.`id_country` = ' . Tools::getValue('orderFilter_country');
                    $vars['country'] = Tools::getValue('orderFilter_country');
                }
                if (Tools::getIsset('orderFilter_customer') && Tools::getValue('orderFilter_customer') != '') {
                    $where .= 'AND c.`firstname` LIKE "%'.Tools::getValue('orderFilter_customer').'%"
                    OR c.`lastname` LIKE"%'.Tools::getValue('orderFilter_customer').'%"';
                    $vars['cus'] = Tools::getValue('orderFilter_customer');
                }
                if (Tools::getIsset('orderFilter_total_paid_tax_incl') &&
                    Tools::getValue('orderFilter_total_paid_tax_incl') != ''
                ) {
                    $where .= ' AND a.`total_paid_tax_incl` = ' . Tools::getValue('orderFilter_total_paid_tax_incl');
                    $vars['total'] = Tools::getValue('orderFilter_total_paid_tax_incl');
                }
                if (Tools::getIsset('orderFilter_payment') && Tools::getValue('orderFilter_payment') != '') {
                    $where .= ' AND a.`payment` LIKE "%'.Tools::getValue('orderFilter_payment').'%"';
                    $vars['payment'] = Tools::getValue('orderFilter_payment');
                }
                if (Tools::getIsset('orderFilter_os') && Tools::getValue('orderFilter_os') != '') {
                    $where .= ' AND a.`id_address_delivery` = ' . Tools::getValue('orderFilter_os');
                    $vars['address_delivery'] = Tools::getValue('orderFilter_os');
                }
                $statuses = OrderState::getOrderStates($this->id_lang);
                $vars['statuses'] = $statuses;
                $vars['delivery'] = $this->getDelivery();
                $vars['orders'] = $this->getOrder($where);
                $this->setTemplate('module:apmarketplace/views/templates/front/order/order.tpl');
                $this->context->smarty->assign($vars);
                parent::initContent();
            } else {
                Tools::redirect($baseurl . 'vendorlogin?login=1');
            }
        }
    }

    public function getDelivery()
    {
        $sql = 'SELECT DISTINCT c.id_country, cl.`name`
                FROM `'._DB_PREFIX_.'orders` o
                '.Shop::addSqlAssociation('orders', 'o').'
                INNER JOIN `'._DB_PREFIX_.'address` a ON
                a.id_address = o.id_address_delivery
                INNER JOIN `'._DB_PREFIX_.'country` c ON
                a.id_country = c.id_country
                INNER JOIN `'._DB_PREFIX_.'country_lang` cl ON
                (c.`id_country` = cl.`id_country` AND
                cl.`id_lang` = '.(int)$this->id_lang.')
                ORDER BY cl.name ASC';
        return Db::getInstance()->ExecuteS($sql);
    }

    public function getOrder($where)
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS a.`id_order`, a.`id_customer`, a.`id_cart`, `reference`,
                `total_paid_tax_incl`, `payment`, a.`date_add` AS `date_add`, 
                a.`id_currency`, a.`id_order` AS id_pdf,
                CONCAT(LEFT(c.`firstname`, 1), c.`lastname`) AS `customer`,
                osl.`name` AS `osname`, os.`color`,
                IF((SELECT so.id_order
                FROM `'._DB_PREFIX_.'orders` so
                WHERE so.id_customer = a.id_customer AND so.id_order < a.id_order LIMIT 1) > 0, 0, 1) as new,
                country_lang.name as cname,
                IF(a.valid, 1, 0) badge_success, shop.name as shop_name 
                FROM `'._DB_PREFIX_.'orders` a
                LEFT JOIN `'._DB_PREFIX_.'customer` c
                ON (c.`id_customer` = a.`id_customer`)
                INNER JOIN `'._DB_PREFIX_.'address` address
                ON address.id_address = a.id_address_delivery
                INNER JOIN `'._DB_PREFIX_.'country` country
                ON address.id_country = country.id_country
                INNER JOIN `'._DB_PREFIX_.'country_lang` country_lang
                ON (country.`id_country` = country_lang.`id_country`
                AND country_lang.`id_lang` = '.(int)$this->id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'order_state` os
                ON (os.`id_order_state` = a.`current_state`)
                LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl
                ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)$this->id_lang.') 
                LEFT JOIN `'._DB_PREFIX_.'shop` shop
                ON a.`id_shop` = shop.`id_shop`
                WHERE 1 '.$where.'
                ORDER BY a.`id_order` DESC';
        $orders = Db::getInstance()->ExecuteS($sql);

        if (!empty($orders)) {
            foreach ($orders as $key_o => $val_o) {
                $order = new Order((int)$val_o['id_order']);
                $products = $order->getProducts();
                $check = 0;
                $price = 0;
                if (!empty($products)) {
                    foreach ($products as $product) {
                        $product_vendor = new ApmarketplaceProduct();
                        $product_vendor = $product_vendor->getProductVendor($this->id_vendor, $product['product_id']);
                        if (!empty($product_vendor)) {
                            $price = $price + $product['total_price_tax_incl'];
                            $check = 1;
                            break;
                        }
                    }
                }
                if ($check == 0) {
                    unset($orders[$key_o]);
                } else {
                    $percent = Configuration::get('APMARKETPLACE_CONFIG_COMMISSION_VALUE', null);
                    $orders[$key_o]['after'] =
                    Tools::displayPrice((($price + $order->total_shipping) * (100 - $percent)) / 100);
                    $orders[$key_o]['total_paid_tax_incl'] = Tools::displayPrice($price + $order->total_shipping);
                }
                if (Tools::getIsset('orderFilter_new') && Tools::getValue('orderFilter_new') != '') {
                    if ($val_o['new'] != Tools::getValue('orderFilter_new')) {
                        unset($orders[$key_o]);
                    }
                }
            }
        }
        return $orders;
    }
}
