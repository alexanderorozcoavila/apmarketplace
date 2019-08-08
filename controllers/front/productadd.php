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

class ApmarketplaceproductaddModuleFrontController extends ModuleFrontController
{
    public $php_self;
    protected $template_path = '';
    public $mod_product;

    public function __construct()
    {
        $this->id_lang = Context::getContext()->language->id;
        $this->id_shop = Context::getContext()->shop->id;
        $this->context = Context::getContext();
        $this->lang = Language::getLanguages(false);
        parent::__construct();
    }

    public function initContent()
    {
        $vars = array();

        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'productadd') {
            $baseurl = $this->context->shop->getBaseURL(true, true);
            if ($this->context->cookie->__isset('cookie_vendor')) {
                $this->addJS($baseurl.'modules/apmarketplace/views/js/editor.js');
                $this->addJS($baseurl.'modules/apmarketplace/views/js/theme.js');
                $this->addJS($baseurl.'modules/apmarketplace/views/js/product.js');

                $vars['baseurl'] = $baseurl;
                $vars['check'] = 1;
                $id_apmarketplace_vendor = $this->context->cookie->cookie_vendor;
                $vendor = new ApmarketplaceVendors($id_apmarketplace_vendor);
                $products = new ApmarketplaceProduct();
                $product = $products->getProductByIdVendor((int)$id_apmarketplace_vendor);
                if (count($product) >= $vendor->fax){
                  $vars['notification'] = $this->l('Ha superado el maximo numero de productos de su plan');
                }
                if ($vendor->active == 0) {
                    $vars['check'] = 0;
                }
                $langs = Language::getLanguages(false);
                $vars['langs'] = $this->lang;
                $vars['id_lang'] = $this->id_lang;
                if (Tools::getIsset('controller') && Tools::getValue('controller') == 'productadd') {
                    if (Tools::isSubmit('add_product')) {
                        $this->addProduct();
                        $vars['notification'] = $this->l('You have successfully submitted the product');
                    }
                    $category = new Category((int)Configuration::get('PS_HOME_CATEGORY'), $this->context->language->id);
                    $categories = new ApmarketplaceVendorStats();
                    $vars['categories'] = $categories->getCategories($category);
                    $this->setTemplate('module:apmarketplace/views/templates/front/product/product_add.tpl');
                }
                $this->context->smarty->assign($vars);
                parent::initContent();
            } else {
                Tools::redirect($baseurl . 'vendorlogin?login=1');
            }
        }
    }

    public function addProduct()
    {
        $address = null;
        $objproduct = new Product();
        $objproduct->tax_name = 'deprecated';
        $objproduct->tax_rate = $objproduct->getTaxesRate(new Address($address));

        $name = Tools::getValue('product_name');
        $description = Tools::getValue('product_des');
        $description_short = Tools::getValue('product_short');

        $name_lang = array();
        $description_lang = array();
        $description_short_lang = array();
        $link_rewrite_lang = array();

        foreach ($this->lang as $lang) {
            $name_lang[$lang['id_lang']] = $name[$lang['id_lang']];
            $description_lang[$lang['id_lang']] = $description[$lang['id_lang']];
            $description_short_lang[$lang['id_lang']] = $description_short[$lang['id_lang']];
            $link_rewrite_lang[$lang['id_lang']] = str_replace(' ', '-', $name[$lang['id_lang']]);
        }

        $objproduct->name = $name_lang;
        $objproduct->description = $description_lang;
        $objproduct->description_short = $description_short_lang;
        $objproduct->link_rewrite = $link_rewrite_lang;
        $objproduct->quantity = Tools::getValue('product_quantity');
        $objproduct->minimal_quantity = 1;
        $objproduct->price = Tools::getValue('product_price');
        $objproduct->reference = Tools::getValue('product_reference');
        $objproduct->id_category_default = Tools::getValue('category');
        $objproduct->redirect_type = '404';
        $objproduct->active = 1;

        $id_apmarketplace_vendor = $this->context->cookie->cookie_vendor;
        $vendor = new ApmarketplaceVendors($id_apmarketplace_vendor);
        $products = new ApmarketplaceProduct();
        $product = $products->getProductByIdVendor((int)$id_apmarketplace_vendor);

        if (count($product) >= $vendor->fax){
            $vars['notification'] = $this->l('Ha superado el numero de productos que puede publicar.');
        }else{
            $objproduct->add(true, false);
            $id_product = $objproduct->id;

            $product = new ApmarketplaceProduct();
            $product->id_product = $id_product;
            $product->id_apmarketplace_vendor = $this->context->cookie->cookie_vendor;
            $product->add(true, false);
            $categories = Tools::getValue('categories');
            ApmarketplaceVendorStats::updateProductCategories($id_product, $categories);
            $files = $_FILES;
            unset($files['leo_media']);
            if (!empty($files)) {
                $position = 1;
                foreach ($files as $val_f) {
                    if ($val_f['size'] != 0) {
                        $vendor_stats = new ApmarketplaceVendorStats();
                        $vendor_stats->addImageProduct($id_product, $val_f, $position);
                        $position = $position + 1;
                    }
                }
            }
            $products = new Product($id_product);
            $products->addWs(true, false);
            $id_stock_available = StockAvailableCore::getStockAvailableIdByProductId($id_product, 0, $this->id_shop);
            $stockAvailable = new StockAvailable($id_stock_available);
            $stockAvailable->quantity = Tools::getValue('product_quantity');
            $stockAvailable->update(false);
        }

        //Tools::redirect($this->context->shop->getBaseURL(true, true) . 'productlist');
    }
}
