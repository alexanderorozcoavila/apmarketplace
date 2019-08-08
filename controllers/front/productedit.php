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

require_once _PS_MODULE_DIR_ . 'apmarketplace/controllers/front/store.php';

class ApmarketplaceproducteditModuleFrontController extends ModuleFrontController
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
        $this->id_product = Tools::getValue('id');
        parent::__construct();
    }

    public function initContent()
    {
        $vars = array();
        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'productedit') {
            $baseurl = $this->context->shop->getBaseURL(true, true);
            if ($this->context->cookie->__isset('cookie_vendor')) {
                if (Tools::isSubmit('edit_product')) {
                    $this->updateProduct();
                    $vars['notification'] = $this->l('You have successfully submitted the product');
                }
                $this->addJS($baseurl.'modules/apmarketplace/views/js/editor.js');
                $this->addJS($baseurl.'modules/apmarketplace/views/js/theme.js');
                $this->addJS($baseurl.'modules/apmarketplace/views/js/product.js');

                $this->addCSS($baseurl.'modules/apmarketplace/views/css/front.css');
                $vars['baseurl'] = $baseurl;
                $vars['check'] = 1;
                $id_apmarketplace_vendor = $this->context->cookie->cookie_vendor;
                $check = new ApmarketplaceProduct();
                $check = $check->getProductVendor($id_apmarketplace_vendor, $this->id_product);
                if (empty($check)) {
                    $vars['permission'] = 0;
                }
                $vendor = new ApmarketplaceVendors($id_apmarketplace_vendor);
                if ($vendor->active == 0) {
                    $vars['check'] = 0;
                }
                $vars['langs'] = $this->lang;
                $vars['id_lang'] = $this->id_lang;
                $category = new Category((int)Configuration::get('PS_HOME_CATEGORY'), (int)$this->id_lang);
                $categories = new ApmarketplaceVendorStats();
                $vars['categories'] = $categories->getCategories($category);
                $product = new ApmarketplacestoreModuleFrontController();
                $product = $product->getProducts(
                    'WHERE  p.id_product IN  (' . $this->id_product . ')',
                    $this->id_lang,
                    1,
                    1,
                    null,
                    null
                );
                if (!empty($product)) {
                    $product = $product[0];
                    $product = new Product($this->id_product);
                    $image = $product->getImages($this->id_lang, null);
                    if (!empty($image)) {
                        foreach ($image as $key_i => $val_i) {
                            $link = new Link();
                            $imagePath = $link->getImageLink(
                                $product->link_rewrite[$this->id_lang],
                                $val_i['id_image'],
                                ImageType::getFormattedName('home')
                            );
                            if (Tools::usingSecureMode() == false) {
                                $imagePath = 'http://' . $imagePath;
                            } else {
                                $imagePath = 'https://' . $imagePath;
                            }
                            $image[$key_i]['src'] = $imagePath;
                        }
                    }
                }
                $product->arr_image = $image;
                $id_stock_available = StockAvailableCore::getStockAvailableIdByProductId($this->id_product, 0, $this->id_shop);
                $product->quantity = (new StockAvailable($id_stock_available))->quantity;
                $vars['product'] = $product;
                $vars['id_product'] = $this->id_product;
                $this->setTemplate('module:apmarketplace/views/templates/front/product/product_edit.tpl');
                $this->context->smarty->assign($vars);
                parent::initContent();
            } else {
                Tools::redirect($baseurl . 'vendorlogin?login=1');
            }
        }
    }

    public function updateProduct()
    {
        $address = null;
        $objproduct = new Product($this->id_product);
        $objproduct->tax_name = 'deprecated';
        $objproduct->tax_rate = $objproduct->getTaxesRate(new Address($address));

        $name = Tools::getValue('product_name');
        $description = Tools::getValue('product_des');
        $description_short = Tools::getValue('product_short');

        foreach ($this->lang as $lang) {
            $objproduct->name[$lang['id_lang']] = $name[$lang['id_lang']];
            $objproduct->description[$lang['id_lang']] = $description[$lang['id_lang']];
            $objproduct->description_short[$lang['id_lang']] = $description_short[$lang['id_lang']];
            $objproduct->link_rewrite[$lang['id_lang']] = str_replace(' ', '-', $name[$lang['id_lang']]);
        }

        $objproduct->quantity = Tools::getValue('product_quantity');
        $objproduct->minimal_quantity = 1;
        $objproduct->price = Tools::getValue('product_price');
        $objproduct->reference = Tools::getValue('product_reference');
        $objproduct->id_category_default = Tools::getValue('category');
        $objproduct->redirect_type = '404';
        $objproduct->active = 1;
        $objproduct->update(false);
        $objproduct->updateWs(false);
        $categories = Tools::getValue('categories');
        ApmarketplaceVendorStats::updateProductCategories($this->id_product, $categories);
        $files = $_FILES;
        unset($files['leo_media']);
        if (!empty($files)) {
            $sql = 'SELECT MAX(`position`)
            FROM `' . _DB_PREFIX_ . 'image` WHERE `id_product` = ' . $this->id_product;
            $position = Db::getInstance()->executeS($sql);
            if (!empty($position)) {
                $position = $position[0]['MAX(`position`)'] + 1;
            } else {
                $position = 1;
            }
            foreach ($files as $val_f) {
                if ($val_f['size'] != 0) {
                    $vendor_stats = new ApmarketplaceVendorStats();
                    $vendor_stats->addImageProduct($this->id_product, $val_f, $position);
                    $position = $position + 1;
                }
            }
        }
        $id_stock_available = StockAvailableCore::getStockAvailableIdByProductId($this->id_product, 0, $this->id_shop);
        $stockAvailable = new StockAvailable($id_stock_available);
        $stockAvailable->quantity = Tools::getValue('product_quantity');
        $stockAvailable->update(false);
    }
}
