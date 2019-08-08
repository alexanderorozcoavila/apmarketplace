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

class ApmarketplacesettingModuleFrontController extends ModuleFrontController
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
        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'setting') {
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
                $vendor = new ApmarketplaceVendors($this->id_vendor);
                $vars['vendor'] = $vendor;
                if (Tools::isSubmit('submit_edit')) {
                    if ($_FILES['leo_image']['size'] != 0) {
                        $target_file = basename($_FILES["leo_image"]["name"]);
                        $imageFileType = Tools::strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                        $new_name = rand(100000000, 999999999) . '_' .
                        rand(1000000000, 9999999999) . '.' . $imageFileType;
                        $_FILES['leo_image']['name'] = $new_name;
                        $link = $baseurl . "modules/apmarketplace/views/img/vendor/" . $vendor->image;
                        if (file_exists($link)) {
                            unlink($link);
                        }
                        $this->module->uploadImageServiceVendor($_FILES['leo_image']);
                        $vendor->image = $new_name;
                    }
                    $vendor->first_name = Tools::getValue('first_name');
                    $vendor->last_name = Tools::getValue('last_name');
                    $vendor->email = Tools::getValue('email');
                    $vendor->phone = Tools::getValue('phone');
                    $vendor->fb = Tools::getValue('fb');
                    $vendor->tt = Tools::getValue('tt');
                    $vendor->fax = Tools::getValue('fax');
                    $vendor->ins = Tools::getValue('ins');
                    $vendor->url_shop = Tools::getValue('url_shop');
                    $vendor->updateVendor($vendor);
                }
                $this->setTemplate('module:apmarketplace/views/templates/front/edit_vendor.tpl');
                $this->context->smarty->assign($vars);
                parent::initContent();
            } else {
                Tools::redirect($baseurl . 'vendorlogin?login=1');
            }
        }
    }
}
