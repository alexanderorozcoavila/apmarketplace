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

class ApmarketplaceorderpayModuleFrontController extends ModuleFrontController
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
        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'orderpay') {
            $baseurl = $this->context->shop->getBaseURL(true, true);
            if ($this->context->cookie->__isset('cookie_vendor')) {
                $vars['baseurl'] = $baseurl;
                $vars['check'] = 1;
                $vendor = new ApmarketplaceVendors($this->id_vendor);
                if ($vendor->active == 0) {
                    $vars['check'] = 0;
                }
                $order = new ApmarketplaceOrder();
                $orders = $order->getByValidate(0);
                if (!empty($orders)) {
                    foreach ($orders as $key_o => $val_o) {
                        if ($val_o['id_vendor'] != $this->id_vendor) {
                            unset($orders[$key_o]);
                        }
                    }
                }
    
                $order_pay = $order->getByValidate(1);
                if (!empty($order_pay)) {
                    foreach ($order_pay as $key_p => $val_p) {
                        if ($val_p['id_vendor'] != $this->id_vendor) {
                            unset($order_pay[$key_p]);
                        }
                    }
                }
      
                $vars['orders'] = $orders;
                $vars['order_pays'] = $order_pay;

                $this->setTemplate('module:apmarketplace/views/templates/front/order/orderpay.tpl');
                $this->context->smarty->assign($vars);
                parent::initContent();
            } else {
                Tools::redirect($baseurl . 'vendorlogin?login=1');
            }
        }
    }
}
