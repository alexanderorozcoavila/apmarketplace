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

class ApmarketplaceproductlistModuleFrontController extends ModuleFrontController
{
    public $php_self;
    protected $template_path = '';
    public $mod_product;

    public function __construct()
    {
        $this->id_lang = Context::getContext()->language->id;
        $this->id_shop = Context::getContext()->shop->id;
        $this->context = Context::getContext();
        parent::__construct();
    }

    public function initContent()
    {
        $vars = array();
        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'productlist') {
            $baseurl = $this->context->shop->getBaseURL(true, true);
            if ($this->context->cookie->__isset('cookie_vendor')) {
                $this->addJS($baseurl.'modules/apmarketplace/views/js/product.js');
                $this->addCSS($baseurl.'modules/apmarketplace/views/css/front.css');
                $vars['baseurl'] = $baseurl;
                $vars['check'] = 1;
                $id_apmarketplace_vendor = $this->context->cookie->cookie_vendor;
                $vendor = new ApmarketplaceVendors($id_apmarketplace_vendor);
                if ($vendor->active == 0) {
                    $vars['check'] = 0;
                }
                if (Tools::getIsset('controller') && Tools::getValue('controller') == 'productlist') {
                    $results = new ApmarketplaceProduct();
                    $results = $results->getProductByIdVendor($id_apmarketplace_vendor);
                    $id_products = '';
                    if (!empty($results)) {
                        foreach ($results as $row) {
                            $id_products .= ', ' . $row['id_product'];
                        }
                        $show_all = count($results);
                    }
                    $id_products = ltrim($id_products, ',');
                    $product = array();
                    if (!empty($results)) {
                        $where = 'WHERE  p.id_product IN  (' . pSQL($id_products) . ')';
                        if (Tools::getIsset('filter_column_name') && Tools::getValue('filter_column_name') != '') {
                            $where .= ' AND pl.`name` LIKE "%'.Tools::getValue('filter_column_name').'%"';
                            $vars['filter_column_name'] = Tools::getValue('filter_column_name');
                        }
                        if (Tools::getIsset('filter_column_reference') &&
                            Tools::getValue('filter_column_reference') != ''
                        ) {
                            $where .= ' AND p.`reference` LIKE "%'.Tools::getValue('filter_column_reference').'%"';
                            $vars['filter_column_reference'] = Tools::getValue('filter_column_reference');
                        }
                        if (Tools::getIsset('filter_column_active') && Tools::getValue('filter_column_active') != '') {
                            $where .= ' AND p.`active` = ' . Tools::getValue('filter_column_active');
                            $vars['filter_column_active'] = Tools::getValue('filter_column_active');
                        }
                        $product = new ApmarketplacestoreModuleFrontController();
                        $product = $product->getProducts(
                            $where,
                            $this->id_lang,
                            1,
                            $show_all,
                            null,
                            null
                        );
                        $product = $this->searchProduct($product);
                    }
                    if (Tools::getIsset('categories') && Tools::getValue('categories') != '') {
                        $vars['id_category'] = Tools::getValue('categories');
                        $cate = new Category(Tools::getValue('categories'), true, $this->id_lang);
                        $vars['cate_name'] = $cate->name;
                    }
                    if (Tools::getIsset('filter_column_price_min') &&
                        Tools::getValue('filter_column_price_min') != ''
                    ) {
                        $vars['filter_column_price_min'] = Tools::getValue('filter_column_price_min');
                    }
                    if (Tools::getIsset('filter_column_price_max') &&
                        Tools::getValue('filter_column_price_max') != ''
                    ) {
                        $vars['filter_column_price_max'] = Tools::getValue('filter_column_price_max');
                    }
                    if (Tools::getIsset('filter_column_quantity_min') &&
                        Tools::getValue('filter_column_quantity_min') != ''
                    ) {
                        $vars['filter_column_quantity_min'] = Tools::getValue('filter_column_quantity_min');
                    }
                    if (Tools::getIsset('filter_column_quantity_max') &&
                        Tools::getValue('filter_column_quantity_max') != ''
                    ) {
                        $vars['filter_column_quantity_max'] = Tools::getValue('filter_column_quantity_max');
                    }
                    $vars['products'] = $product;
                    $category = new Category((int)Configuration::get('PS_HOME_CATEGORY'), $this->context->language->id);
                    $categories = new ApmarketplaceVendorStats();
                    $vars['categories'] = $categories->getCategories($category);
                    $this->setTemplate('module:apmarketplace/views/templates/front/product/product.tpl');
                }
                $this->context->smarty->assign($vars);
                parent::initContent();
            } else {
                Tools::redirect($baseurl . 'vendorlogin?login=1');
            }
        }
    }

    public function searchProduct($product)
    {
        foreach ($product as $key_p => $val_p) {
            $image = Image::getCover($val_p['id_product']);
            $imagePath = '';
            if (!empty($image)) {
                $link = new Link();
                $imagePath = $link->getImageLink(
                    $val_p['link_rewrite'],
                    $image['id_image'],
                    ImageType::getFormattedName('home')
                );
                if (Tools::usingSecureMode() == false) {
                    $imagePath = 'http://' . $imagePath;
                } else {
                    $imagePath = 'https://' . $imagePath;
                }
            } else {
                $code = Language::getIsoById($this->id_lang);
                $imagePath = $this->context->shop->getBaseURL(true, true) . 'img/p/'.$code.'.jpg';
            }

            $product[$key_p]['image_url'] = $imagePath;
            if (Tools::getIsset('categories') && Tools::getValue('categories') != '') {
                $id_category = Tools::getValue('categories');
                $categories = Product::getProductCategories($val_p['id_product']);
                $check = 1;
                if (!empty($categories)) {
                    foreach ($categories as $val_cate) {
                        if ($val_cate == $id_category) {
                            $check = 2;
                            break;
                        }
                    }
                }
                if ($check == 1) {
                    unset($product[$key_p]);
                }
            }
            if (Tools::getIsset('filter_column_price_min') && Tools::getValue('filter_column_price_min') != '') {
                if ($val_p['price'] < Tools::getValue('filter_column_price_min')) {
                    unset($product[$key_p]);
                }
            }
            if (Tools::getIsset('filter_column_price_max') && Tools::getValue('filter_column_price_max') != '') {
                if ($val_p['price'] > Tools::getValue('filter_column_price_max')) {
                    unset($product[$key_p]);
                }
            }
            if (Tools::getIsset('filter_column_quantity_min') && Tools::getValue('filter_column_quantity_min') != '') {
                if ($val_p['quantity_all_versions'] < Tools::getValue('filter_column_quantity_min')) {
                    unset($product[$key_p]);
                }
            }
            if (Tools::getIsset('filter_column_quantity_max') && Tools::getValue('filter_column_quantity_max') != '') {
                if ($val_p['quantity_all_versions'] > Tools::getValue('filter_column_quantity_max')) {
                    unset($product[$key_p]);
                }
            }
        }
        return $product;
    }
}
