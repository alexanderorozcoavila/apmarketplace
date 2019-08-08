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

require_once _PS_MODULE_DIR_ . 'apmarketplace/controllers/admin/AdminApmarketplaceVendors.php';

class ApmarketplacevendorloginModuleFrontController extends ModuleFrontController
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
        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'vendorlogin') {
            $baseurl = $this->context->shop->getBaseURL(true, true);
            if (Tools::getIsset('out') && Tools::getValue('out') == 1) {
                $this->context->cookie->__unset('cookie_vendor');
                Tools::redirect($baseurl . 'vendorlogin?login=1');
            } else {
                if ($this->context->cookie->__isset('cookie_vendor')) {
                    Tools::redirect($baseurl . 'dashboard');
                }
            }
            $vars = array(
                'baseurl' => $baseurl,
            );
            $vars['check'] = 1;
            if (Tools::getIsset('login') && Tools::getValue('login') == 1) {
                if (Tools::isSubmit('submit_login')) {
                    $check = new ApmarketplaceVendors();
                    $check = $check->checkLogin(Tools::getValue('user'), Tools::getValue('password'));
                    if (count($check) > 0) {
                        $check = $check[0];
                        $this->context->cookie->__set('cookie_vendor', $check['id_apmarketplace_vendor']);
                        Tools::redirect($baseurl . 'dashboard');
                    } else {
                        $vars['check'] = 0;
                    }
                }
                $this->setTemplate('module:apmarketplace/views/templates/front/login.tpl');
            }

            if (Tools::getIsset('create_account') && Tools::getValue('create_account') == 1) {
                $exit = 0;
                if (Tools::isSubmit('submit_login')) {
                    $vendor =  new ApmarketplaceVendors();
                    $check = $vendor->checkUserVendor(Tools::getValue('user_name'));
                    if (count($check) > 0) {
                        $exit = 1;
                    } else {
                        if (!preg_match('/^[A-Za-z-_]{1}[A-Za-z0-9-_]{1,31}$/', $username)) {
                            $vars['user_name'] = Tools::getValue('user_name');
                            $vars['first_name'] = Tools::getValue('first_name');
                            $vars['last_name'] = Tools::getValue('last_name');
                            $vars['email'] = Tools::getValue('email');
                            $vars['phone'] = Tools::getValue('phone');
                            $vars['fb'] = Tools::getValue('fb');
                            $vars['tt'] = Tools::getValue('tt');
                            $vars['fax'] = Tools::getValue('fax');
                            $vars['fax'] = Tools::getValue('fax');
                            $vars['ins'] = Tools::getValue('ins');
                            $vars['url_shop'] = Tools::getValue('url_shop');
                            $vars['error'] = '1';
                        } else {
                            if ($_FILES['leo_image']['size'] != 0) {
                                $target_file = basename($_FILES["leo_image"]["name"]);
                                $imageFileType = Tools::strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                                $new_name = rand(100000000, 999999999) . '_' .
                                rand(1000000000, 9999999999) . '.' . $imageFileType;
                                $_FILES['leo_image']['name'] = $new_name;
                                $this->module->uploadImageServiceVendor($_FILES['leo_image']);
                                $vendor->image = $new_name;
                            }
                            $vendor->user_name = $username = Tools::getValue('user_name');
                            $vendor->first_name = Tools::getValue('first_name');
                            $vendor->last_name = Tools::getValue('last_name');
                            $vendor->email = Tools::getValue('email');
                            $vendor->phone = Tools::getValue('phone');
                            $vendor->fb = Tools::getValue('fb');
                            $vendor->tt = Tools::getValue('tt');
                            $vendor->fax = Tools::getValue('fax');
                            $vendor->ins = Tools::getValue('ins');
                            $vendor->url_shop = Tools::getValue('url_shop');
                            $vendor->pass_word = Tools::getValue('pass_word');
                            $vendor->active = 0;
                            $vendor->add();  
                            $array_vendor = $vendor->checkUserVendor(Tools::getValue('user_name'));
                            $id_apmarketplace_vendor = $array_vendor[0]['id_apmarketplace_vendor'];
                            $this->context->cookie->__set('cookie_vendor', $id_apmarketplace_vendor);
                            Tools::redirect($baseurl . 'dashboard');
                        }  
                    }
                }
                $vars['exit'] = $exit;
                $this->setTemplate('module:apmarketplace/views/templates/front/create_vendor.tpl');
            }
            $this->context->smarty->assign($vars);
            parent::initContent();
        }
    }
}
