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

class ApmarketplacediscounteditModuleFrontController extends ModuleFrontController
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
        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'discountedit') {
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
                $vars['currencies'] = Currency::getCurrencies(false, true, true);
                $id_cart_rule = Tools::getValue('id');
                $obj = new CartRule($id_cart_rule, $this->id_lang);
                if ($obj->id != '' && $obj->gift_product != '') {
                    $product = new ApmarketplaceProduct();
                    $check_discount = $product->getProductVendor($this->id_vendor, $obj->gift_product);
                } else {
                    $check_discount = array();
                }
                if (!empty($check_discount)) {
                    $vars['permis'] = 1;
                } else {
                    $vars['permis'] = 0;
                }
                $vars['discounts'] = $obj;
                $product = new Product($obj->gift_product, false, $this->id_lang);
          
                $gift_product_filter = (!empty($product->reference) ? $product->reference : $product->name);
                $product->leo_price = Tools::displayPrice($product->price);
                $vars['gift_product_filter'] = $gift_product_filter;
                $vars['product'] = $product;
                if (Tools::isSubmit('edit_distcount')) {
                    $this->updateDiscount($id_cart_rule);
                    $vars['notification'] = $this->l('You have successfully submitted the discount');
                }
                $this->setTemplate('module:apmarketplace/views/templates/front/discount/discountedit.tpl');
                $this->context->smarty->assign($vars);
                parent::initContent();
            } else {
                Tools::redirect($baseurl . 'vendorlogin?login=1');
            }
        }
    }

    public function updateDiscount($id_cart_rule)
    {
        $cart_rule = new CartRule($id_cart_rule);
        $cart_rule->name = Tools::getValue('name');
        $cart_rule->date_from = Tools::getValue('date_from');
        $cart_rule->date_to = Tools::getValue('date_to');
        $cart_rule->description = Tools::getValue('description');
        $cart_rule->quantity = Tools::getValue('quantity');
        $cart_rule->quantity_per_user = Tools::getValue('quantity_per_user');
        $cart_rule->priority = Tools::getValue('priority');
        $cart_rule->partial_use = Tools::getValue('partial_use');
        $cart_rule->code = Tools::getValue('code');
        $cart_rule->minimum_amount = Tools::getValue('minimum_amount');
        $cart_rule->minimum_amount_tax = Tools::getValue('minimum_amount_tax');
        $cart_rule->minimum_amount_currency = Tools::getValue('minimum_amount_currency');
        $cart_rule->minimum_amount_shipping = Tools::getValue('minimum_amount_shipping');
        $cart_rule->free_shipping = Tools::getValue('free_shipping');
        $cart_rule->reduction_percent = Tools::getValue('reduction_percent');
        $cart_rule->reduction_amount = Tools::getValue('reduction_amount');
        $cart_rule->reduction_tax = Tools::getValue('reduction_tax');
        $cart_rule->reduction_currency = Tools::getValue('reduction_currency');
        $cart_rule->reduction_product = Tools::getValue('reduction_product');
        $cart_rule->reduction_exclude_special = Tools::getValue('reduction_exclude_special');
        if (Tools::getValue('gift_product') != '') {
            $cart_rule->gift_product = Tools::getValue('gift_product');
        } else {
            $product = new ApmarketplaceProduct();
            $product = $product->getProductByIdVendor($this->id_vendor);
            if (!empty($product)) {
                $cart_rule->gift_product = $product[0]['id_product'];
            } else {
                return false;
            }
        }
        // $cart_rule->gift_product_attribute = Tools::getValue('name');
        $cart_rule->highlight = Tools::getValue('highlight');
        $cart_rule->active = Tools::getValue('active');
        $cart_rule->update(false);
    }
}
