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

class ApmarketplacedashboardModuleFrontController extends ModuleFrontController
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
        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'dashboard') {
            $baseurl = $this->context->shop->getBaseURL(true, true);
            if ($this->context->cookie->__isset('cookie_vendor')) {
                $vars['baseurl'] = $baseurl;
                $vars['check'] = 1;
                $id_apmarketplace_vendor = $this->context->cookie->cookie_vendor;
                $vendor = new ApmarketplaceVendors($id_apmarketplace_vendor);
                if ($vendor->active == 0) {
                    $vars['check'] = 0;
                }
                $date = new DateTime('now');
                $date->modify('last day of this month');
                $lastday = $date->format('Y-m-d');
                if (Tools::getIsset('apmarketplace_to')) {
                    $lastday = Tools::getValue('apmarketplace_to');
                }
                $date = new DateTime('now');
                $date->modify('first day of this month');
                $firstday = $date->format('Y-m-d');
                if (Tools::getIsset('apmarketplace_from')) {
                    $firstday = Tools::getValue('apmarketplace_from');
                }
                $vars['leo_currency'] = $this->context->currency;
                $vars['firstday'] = $firstday;
                $vars['lastday'] = $lastday;
                $this->setTemplate('module:apmarketplace/views/templates/front/dashboard.tpl');
                $this->context->smarty->assign($vars);
                parent::initContent();
            } else {
                Tools::redirect($baseurl . 'vendorlogin?login=1');
            }
        }
    }
}
