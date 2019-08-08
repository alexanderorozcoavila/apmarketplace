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

class ApmarketplacereviewModuleFrontController extends ModuleFrontController
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
        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'review') {
            $baseurl = $this->context->shop->getBaseURL(true, true);
            if ($this->context->cookie->__isset('cookie_vendor')) {
                $vars['baseurl'] = $baseurl;
                $vars['check'] = 1;
                $vendor = new ApmarketplaceVendors($this->id_vendor);
                if ($vendor->active == 0) {
                    $vars['check'] = 0;
                }
                $reviews = ApmarketplaceReview::getByValidate(0, false);
                $product = new ApmarketplaceProduct();
                if (!empty($reviews)) {
                    foreach ($reviews as $key_r => $val_r) {
                        $products = $product->getProductVendor($this->id_vendor, $val_r['id_product']);
                        if (empty($products)) {
                            unset($reviews[$key_r]);
                        }
                    }
                }

                $review_accept = ApmarketplaceReview::getByValidate(1, false);
                if (!empty($review_accept)) {
                    foreach ($review_accept as $key_a => $val_a) {
                        $products_a = $product->getProductVendor($this->id_vendor, $val_a['id_product']);
                        if (empty($products_a)) {
                            unset($review_accept[$key_a]);
                        }
                    }
                }

                $vars['reviews'] = $reviews;
                $vars['review_accepts'] = $review_accept;

                $this->setTemplate('module:apmarketplace/views/templates/front/review.tpl');
                $this->context->smarty->assign($vars);
                parent::initContent();
            } else {
                Tools::redirect($baseurl . 'vendorlogin?login=1');
            }
        }
    }
}
