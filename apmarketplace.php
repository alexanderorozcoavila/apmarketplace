<?php
/**
* 2007-2018 PrestaShop
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
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'apmarketplace/includer.php';

class Apmarketplace extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'apmarketplace';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Apollotheme';
        $this->controllers = array(
            'store',
            'vendors',
            'vendorlogin',
            'dashboard',
            'productlist',
            'productadd',
            'productedit',
            'orderslist',
            'orderedit',
            'discountlist',
            'discountadd',
            'discountedit',
            'orderpay',
            'review',
            'setting'
        );
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Ap Market Place');
        $this->description = $this->l('This is great module for market place');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->context =  Context::getContext();
        $this->id_lang = Context::getContext()->language->id;
        $this->id_shop = Context::getContext()->shop->id;
        if ($this->context->cookie->__isset('cookie_vendor')) {
            $this->id_vendor = $this->context->cookie->cookie_vendor;
        }
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        $class = 'Admin'.Tools::ucfirst($this->name).'Management';
        $id_parent = Tab::getIdFromClassName('IMPROVE');
        $tab1 = new Tab();
        $tab1->class_name = $class;
        $tab1->module = $this->name;
        $tab1->id_parent = $id_parent;
        $langs = Language::getLanguages(false);
        foreach ($langs as $l) {
            $tab1->name[$l['id_lang']] = $this->l('Ap Market Place');
        }
        $tab1->add(true, false);

        Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'tab`
                SET `icon` = "shopping_cart"
                WHERE `id_tab` = "'.(int)$tab1->id.'"');

        $this->installModuleTab('Apmarketplace Vendors', 'Vendors', 'AdminApmarketplaceManagement');
        $this->installModuleTab(
            'Apmarketplace Product Vendors',
            'products',
            'AdminApmarketplaceManagement'
        );
        $this->installModuleTab('Apmarketplace Settings', 'settings', 'AdminApmarketplaceManagement');
        $this->installModuleTab('Apmarketplace Review', 'review', 'AdminApmarketplaceManagement');
        $this->installModuleTab('Apmarketplace Order', 'order', 'AdminApmarketplaceManagement');
        $this->installModuleTab('Apmarketplace Question', 'question', 'AdminApmarketplaceManagement');

        Configuration::updateValue('APMARKETPLACE_CONFIG_COMMISSION_VALUE', 10);
        Configuration::updateValue(
            'APMARKETPLACE_CONFIG_SUBIZ_CODE',
            "<script>
                (function(s, u, b, i, z){ u[i]=u[i]||function(){ u[i].t=+new Date();
                (u[i].q=u[i].q||[]).push(arguments); };
                z=s.createElement('script'); var zz=s.getElementsByTagName('script')[0]; z.async=1;
                z.src=b; z.id='subiz-script';
                zz.parentNode.insertBefore(z,zz); })(document, window,
                'https://widgetv4.subiz.com/static/js/app.js', 'subiz');
                subiz('setAccount', 'acqequtiirgvbtzdjfyw');
            </script>"
        );
        Configuration::updateValue('APMARKETPLACE_CONFIG_COLOR_SUBIZ', '#ff0000');
        include(dirname(__FILE__).'/sql/install.php');
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayProductListReviews') &&
            $this->registerHook('displayProductAdditionalInfo') &&
            $this->registerHook('displayFooterProduct') &&
            $this->registerHook('displayLeftColumn') &&
            $this->registerHook('displayNav2') &&
            $this->registerHook('dashboardData') &&
            $this->registerHook('actionProductSave') &&
            $this->registerHook('actionProductDelete') &&
            $this->registerHook('actionValidateOrder') &&
            $this->registerHook('displayWrapperTop') &&
            $this->registerHook('moduleRoutes') &&
            $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
        Configuration::deleteByName('APMARKETPLACE_CONFIG_COMMISSION_VALUE');
        Configuration::deleteByName('APMARKETPLACE_CONFIG_SUBIZ_CODE');
        Configuration::deleteByName('APMARKETPLACE_CONFIG_COLOR_SUBIZ');
        $this->uninstallModuleTab('management');
        $this->uninstallModuleTab('Vendors');
        $this->uninstallModuleTab('products');
        $this->uninstallModuleTab('settings');
        $this->uninstallModuleTab('review');
        $this->uninstallModuleTab('order');
        $this->uninstallModuleTab('question');

        return parent::uninstall();
    }

    public function installModuleTab($title, $class_sfx = '', $parent = '')
    {
        $class = 'Admin'.Tools::ucfirst($this->name).Tools::ucfirst($class_sfx);
        @copy(_PS_MODULE_DIR_.$this->name.'/logo.gif', _PS_IMG_DIR_.'t/'.$class.'.gif');
        if ($parent == '') {
            # validate module
            $position = Tab::getCurrentTabId();
        } else {
            # validate module
            $position = Tab::getIdFromClassName($parent);
        }
        $tab1 = new Tab();
        $tab1->class_name = $class;
        $tab1->module = $this->name;
        $tab1->id_parent = (int)$position;
        $langs = Language::getLanguages(false);
        foreach ($langs as $l) {
            $tab1->name[$l['id_lang']] = $title;
        }
        $tab1->add(true, false);
    }

    public function uninstallModuleTab($class_sfx = '')
    {
        $tab_class = 'Admin'.Tools::ucfirst($this->name).Tools::ucfirst($class_sfx);
        $id_tab = Tab::getIdFromClassName($tab_class);
        if ($id_tab != 0) {
            $tab = new Tab($id_tab);
            $tab->delete();
            return true;
        }
        return false;
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        // $this->registerHook('moduleRoutes');
        // die('iiiiiiiiiii');
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitApmarketplaceModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitApmarketplaceModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Config Commission Value %'),
                        'name' => 'APMARKETPLACE_CONFIG_COMMISSION_VALUE',
                        'desc' => $this->l('Config Commission Value'),
                        'hint' => $this->l(''),
                        'required' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Subiz Code'),
                        'name' => 'APMARKETPLACE_CONFIG_SUBIZ_CODE',
                        'desc' => $this->l('Subiz Code'),
                        'hint' => $this->l(''),
                        'required' => true,
                    ),
                    array(
                       'type' => 'color',
                       'label' => $this->l('Subiz Color'),
                       'hint' => $this->l(''),
                       'name' => 'APMARKETPLACE_CONFIG_COLOR_SUBIZ'
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'APMARKETPLACE_CONFIG_COMMISSION_VALUE' => Configuration::get(
                'APMARKETPLACE_CONFIG_COMMISSION_VALUE',
                null
            ),
            'APMARKETPLACE_CONFIG_SUBIZ_CODE' => Configuration::get('APMARKETPLACE_CONFIG_SUBIZ_CODE', null),
            'APMARKETPLACE_CONFIG_COLOR_SUBIZ' => Configuration::get('APMARKETPLACE_CONFIG_COLOR_SUBIZ', null),
        );
    }

    public function getDir()
    {
        return realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR;
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        Media::addJsDef(array(
            'baseurl' => $this->context->shop->getBaseURL(true, true) . 'modules/apmarketplace/ajax.php',
        ));
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        Media::addJsDef(array(
            'dashboard_ajax_url' => $this->context->shop->getBaseURL(true, true) . 'modules/apmarketplace/ajax.php',
            'dashboard_use_push' => Configuration::get('PS_DASHBOARD_USE_PUSH'),
            'baseurl' => $this->context->shop->getBaseURL(true, true),
            'cancel_rating_txt' => $this->l('Cancel Rating'),
            'color' => Configuration::get('APMARKETPLACE_CONFIG_COLOR_SUBIZ', null),
            'product' => $this->l('No products found'),
        ));
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addJS($this->_path.'/views/js/jquery.rating.pack.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
        $this->context->controller->addCSS($this->_path.'/views/css/font-awesome.min.css');

        if ($this->context->cookie->__isset('cookie_vendor') &&
            Tools::getIsset('controller') &&
            Tools::getValue('controller') == 'dashboard'
        ) {
            Media::addJsDef(array('currency' => array(
                'iso_code' => Context::getContext()->currency->iso_code,
                'sign' => Context::getContext()->currency->sign,
                'name' => Context::getContext()->currency->name,
                'format' => Context::getContext()->currency->format,
            )));

            $this->context->controller->addJS($this->_path.'/views/js/tools.js');
            $this->context->controller->addJS($this->_path.'/views/js/dashtrends.js');
            $this->context->controller->addJS($this->_path.'/views/js/d3.v3.min.js');
            $this->context->controller->addJS($this->_path.'/views/js/nv.d3.min.js');
            $this->context->controller->addJS($this->_path.'/views/js/dashboard.js');
            $this->context->controller->addCSS($this->_path.'/views/css/nv.d3.css');
        }
    }

    public function hookdisplayWrapperTop()
    {
        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'search') {
            $this->smarty->assign(array(
                'name' => Tools::getValue('s'),
            ));
        }
        return $this->display(__FILE__, 'views/templates/hook/search.tpl');
    }

    public function hookdisplayProductAdditionalInfo($params)
    {
        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'product') {
            $id_product = $params['product']['id_product'];
            $id_guest = (!$id_customer = $this->context->cookie->id_customer)
            ? $this->context->cookie->id_guest : false;
            unset($id_customer);
            $customerReview = ApmarketplaceReview::getByCustomer(
                $id_product,
                $this->context->cookie->id_customer,
                true,
                $id_guest
            );
            $average = ApmarketplaceReview::getAverageGrade($id_product);
            $this->smarty->assign(array(
                'nbReviews_product_extra' => ApmarketplaceReview::getReviewNumber($id_product),
                'averageTotal_extra' => round($average['grade']),
                'too_early_extra' => ($customerReview && (strtotime($customerReview['date_add']) + 30) > time()),
                'id_product_review_extra' => $id_product,
                'link_product_review_extra' => $params['product']['link'],
            ));
            return $this->display(__FILE__, 'views/templates/hook/review.tpl');
        }
    }

    public function renderModalReview($id_product, $is_logged)
    {
        $product = new Product(
            (int)$id_product,
            false,
            (int)$this->context->language->id,
            (int)$this->context->shop->id
        );
        $image = Product::getCover((int) $id_product);
        $cover_image = $this->context->link->getImageLink(
            $product->link_rewrite,
            $image['id_image'],
            ImageType::getFormattedName('medium')
        );
        $this->context->smarty->assign(array(
            'product_modal_review' => $product,
            'productcomment_cover_image' => $cover_image,
            'allow_guests' => 0,
            'is_logged' => $is_logged,
        ));

        $output = $this->fetch('module:apmarketplace/views/templates/hook/modal_review.tpl');

        return $output;
    }

    public function hookdisplayFooterProduct($params)
    {
        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'product') {
            $id_product = $params['product']['id_product'];
            $id_guest = (!$id_customer = $this->context->cookie->id_customer) ?
            $this->context->cookie->id_guest : false;

            if ($this->context->cookie->id_customer) {
                $this->context->smarty->assign(array(
                    'id_customer' => $this->context->cookie->id_customer,
                ));
            }

            $id_customer = $this->context->cookie->id_customer;
            $question = new ApmarketplaceQuestion();
            $questions = $question->getByIDProduct($id_product);
            if (!empty($questions)) {
                foreach ($questions as $key_q => $val_q) {
                    $customer =  new Customer((int)$val_q['id_customer']);
                    $questions[$key_q]['customer_name'] = $customer->firstname . ' ' . $customer->lastname;
                    $answer =  new ApmarketplaceAnswer;
                    $questions[$key_q]['answers'] = $answer->getAnswerByIDQuestion($val_q['id_apmarketplace_question']);
                }
            }
            unset($id_customer);
            $customerReview = ApmarketplaceReview::getByCustomer(
                $id_product,
                $this->context->cookie->id_customer,
                true,
                $id_guest
            );
            $this->context->smarty->assign(array(
                'reviews' => ApmarketplaceReview::getByProduct(
                    $id_product,
                    1,
                    null,
                    $this->context->cookie->id_customer
                ),
                'too_early' => ($customerReview && (strtotime($customerReview['date_add']) + 30) > time()),
                'id_product_tab_content' => $id_product,
                'link_product_tab_content' => $params['product']['link'],
                'questions' => $questions,
            ));
            return $this->display(__FILE__, 'views/templates/hook/review_tab.tpl');
        }
    }

    public function hookdisplayProductListReviews($params)
    {
        $id_product = $params['product']['id_product'];
        $obj = new ApmarketplaceProduct();
        $obj = $obj->getVendorId($id_product);
        if (!empty($obj)) {
            $obj = $obj[0];
            $obj =  new ApmarketplaceVendors($obj['id_apmarketplace_vendor']);
            $this->smarty->assign(array(
                'baseurl' => $this->context->shop->getBaseURL(true, true),
                'vendor' => $obj,
            ));
        } else {
            $this->smarty->assign(array(
                'vendor' => array(),
            ));
        }
        return $this->display(__FILE__, 'views/templates/hook/vendor_product.tpl');
    }

    public function hookdisplayLeftColumn($params)
    {
        if (Tools::getIsset('module') &&
            Tools::getValue('module') == 'apmarketplace' &&
            Tools::getIsset('controller') &&
            Tools::getValue('controller') == 'store'
        ) {
            $vendors = new ApmarketplaceVendors(Tools::getValue('id'));
            $this->smarty->assign(array(
                'baseurl' => $this->context->shop->getBaseURL(true, true),
                'vendors' => $vendors,
            ));
            return $this->display(__FILE__, 'views/templates/hook/email_vendor.tpl');
        }
    }

    public function hookdisplayNav2($params)
    {
        $baseurl = $this->context->shop->getBaseURL(true, true);
        $vars = array(
            'baseurl' => $baseurl,
        );
        if ($this->context->cookie->__isset('cookie_vendor')) {
            $cookie = $this->context->cookie->cookie_vendor;
            $vars['check'] = 1;
            $vars['vendors'] = new ApmarketplaceVendors($cookie);
        } else {
            $vars['check'] = 0;
        }

        $this->context->smarty->assign($vars);
        return $this->display(__FILE__, 'views/templates/hook/nav.tpl');
    }

    public function hookactionValidateOrder($params)
    {
        $id_cart = $params['cart']->id;
        $id_order = OrderCore::getIdByCartId((int)$id_cart);
        $cart = new Cart((int)$id_cart);
        $products = $cart->getProducts();
        if (!empty($products)) {
            foreach ($products as $product) {
                $vendor_p = new ApmarketplaceProduct();
                $vendors = $vendor_p->getVendorId((int)$product['id_product']);
                if (!empty($vendors)) {
                    $market_order = new ApmarketplaceOrder();
                    $market_order->id_order = (int)$id_order;
                    $market_order->id_product = (int)$product['id_product'];
                    $market_order->id_vendor = (int)$vendors[0]['id_apmarketplace_vendor'];
                    $market_order->payment = 0;
                    $market_order->add(true, false);
                }
            }
        }
    }

    public function hookactionProductDelete($params)
    {
        if (isset($params['id_product'])) {
            $id_product = $params['id_product'];
            $sql = 'DELETE FROM `'._DB_PREFIX_.'apmarketplace_product` WHERE id_product = ' . (int)$id_product;
            Db::getInstance()->execute($sql);
        }
    }

    public function hookdashboardData($params)
    {
        $this->currency = clone $this->context->currency;
        $tmp_data = $this->getData($params['date_from'], $params['date_to']);
        $this->dashboard_data = $this->refineData($params['date_from'], $params['date_to'], $tmp_data);
        $this->dashboard_data_sum = $this->addupData($this->dashboard_data);
        $sales_score = Tools::displayPrice($this->dashboard_data_sum['sales'], $this->currency).
                       $this->addTaxSuffix();
        $cart_value_score = Tools::displayPrice($this->dashboard_data_sum['average_cart_value'], $this->currency).
                            $this->addTaxSuffix();
        $net_profit_score = Tools::displayPrice($this->dashboard_data_sum['net_profits'], $this->currency).
                            $this->addTaxSuffix();
        return array(
            'data_value' => array(
                'sales_score' => $sales_score,
                'orders_score' => Tools::displayNumber($this->dashboard_data_sum['orders'], $this->currency),
                'cart_value_score' => $cart_value_score,
                'visits_score' => Tools::displayNumber($this->dashboard_data_sum['visits'], $this->currency),
                'conversion_rate_score' => round(100 * $this->dashboard_data_sum['conversion_rate'], 2).'%',
                'net_profits_score' => $net_profit_score,
            ),
            'data_trends' => $this->dashboard_data_sum,
            'data_chart' => array('dash_trends_chart1' => $this->getChartTrends()),
        );
    }

    public function getData($date_from, $date_to)
    {
        $tmp_data = array(
            'visits' => array(),
            'orders' => array(),
            'total_paid_tax_excl' => array(),
            'total_purchases' => array(),
            'total_expenses' => array()
        );
        $tmp_data['visits'] = AdminStatsController::getVisits(false, $date_from, $date_to, 'day');
        $tmp_data['orders'] = ApmarketplaceVendorStats::getOrders($date_from, $date_to, $this->id_vendor);
        $tmp_data['total_paid_tax_excl'] = ApmarketplaceVendorStats::getTotalSales(
            $date_from,
            $date_to,
            $this->id_vendor
        );
        $tmp_data['total_purchases'] = ApmarketplaceVendorStats::getPurchases($date_from, $date_to, $this->id_vendor);
        $tmp_data['total_expenses'] = ApmarketplaceVendorStats::getExpenses($date_from, $date_to, $this->id_vendor);
        return $tmp_data;
    }

    public function refineData($date_from, $date_to, $gross_data)
    {
        $refined_data = array(
            'sales' => array(),
            'orders' => array(),
            'average_cart_value' => array(),
            'visits' => array(),
            'conversion_rate' => array(),
            'net_profits' => array()
        );

        $from = strtotime($date_from.' 00:00:00');
        $to = min(time(), strtotime($date_to.' 23:59:59'));
        for ($date = $from; $date <= $to; $date = strtotime('+1 day', $date)) {
            $refined_data['sales'][$date] = 0;
            if (isset($gross_data['total_paid_tax_excl'][$date])) {
                $refined_data['sales'][$date] += $gross_data['total_paid_tax_excl'][$date];
            }

            $refined_data['orders'][$date] = isset($gross_data['orders'][$date]) ? $gross_data['orders'][$date] : 0;

            $refined_data['average_cart_value'][$date] =
            $refined_data['orders'][$date] ? $refined_data['sales'][$date] / $refined_data['orders'][$date] : 0;

            $refined_data['visits'][$date] = isset($gross_data['visits'][$date]) ? $gross_data['visits'][$date] : 0;

            $refined_data['conversion_rate'][$date] =
            $refined_data['visits'][$date] ? $refined_data['orders'][$date] / $refined_data['visits'][$date] : 0;

            $refined_data['net_profits'][$date] = 0;
            if (isset($gross_data['total_paid_tax_excl'][$date])) {
                $refined_data['net_profits'][$date] += $gross_data['total_paid_tax_excl'][$date];
            }
            if (isset($gross_data['total_purchases'][$date])) {
                $refined_data['net_profits'][$date] -= $gross_data['total_purchases'][$date];
            }
            if (isset($gross_data['total_expenses'][$date])) {
                $refined_data['net_profits'][$date] -= $gross_data['total_expenses'][$date];
            }
        }
        return $refined_data;
    }

    public function addupData($data)
    {
        $summing = array(
            'sales' => 0,
            'orders' => 0,
            'average_cart_value' => 0,
            'visits' => 0,
            'conversion_rate' => 0,
            'net_profits' => 0
        );

        $summing['sales'] = array_sum($data['sales']);
        $summing['orders'] = array_sum($data['orders']);
        $summing['average_cart_value'] = $summing['sales'] ? $summing['sales'] / $summing['orders'] : 0;
        $summing['visits'] = array_sum($data['visits']);
        $summing['conversion_rate'] = $summing['visits'] ? $summing['orders'] / $summing['visits'] : 0;
        $summing['net_profits'] = array_sum($data['net_profits']);

        return $summing;
    }

    public function addTaxSuffix()
    {
        return ' <small>'.$this->l('Tax excl.').'</small>';
    }
    public function getChartTrends()
    {
        $chart_data = array();
        $chart_data_compare = array();
        foreach (array_keys($this->dashboard_data) as $chart_key) {
            $chart_data[$chart_key] = $chart_data_compare[$chart_key] = array();
            if (!$count = count($this->dashboard_data[$chart_key])) {
                continue;
            }
            foreach ($this->dashboard_data[$chart_key] as $key => $value) {
                $chart_data[$chart_key][] = array($key, $value);
            }
        }
        $charts = array(
            'sales' => $this->l('Sales'),
            'orders' => $this->l('Orders'),
            'average_cart_value' => $this->l('Average Cart Value'),
            'visits' => $this->l('Visits'),
            'conversion_rate' => $this->l('Conversion Rate'),
            'net_profits' => $this->l('Net Profit'),
        );
        $gfx_color = array('#1777B6','#2CA121','#E61409','#FF7F00','#6B399C','#B3591F');
        $i = 0;
        $data = array(
            'chart_type' => 'line_chart_trends',
            'date_format' => $this->context->language->date_format_lite,
            'data' => array()
        );
        foreach ($charts as $key => $title) {
            $data['data'][] = array(
                'id' => $key,
                'key' => $title,
                'color' => $gfx_color[$i],
                'values' => $chart_data[$key],
                'disabled' => ($key == 'sales' ? false : true)
            );
            $i++;
        }
        return $data;
    }

    public function hookmoduleRoutes()
    {
        $routes = array();

        $routes['module-apmarketplace-store'] = array(
            'controller' => 'store',
            'rule' => 'store',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'apmarketplace'
            )
        );

        $routes['module-apmarketplace-vendors'] = array(
            'controller' => 'vendors',
            'rule' => 'vendors',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'apmarketplace'
            )
        );

        $routes['module-apmarketplace-vendorlogin'] = array(
            'controller' => 'vendorlogin',
            'rule' => 'vendorlogin',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'apmarketplace'
            )
        );

        $routes['module-apmarketplace-dashboard'] = array(
            'controller' => 'dashboard',
            'rule' => 'dashboard',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'apmarketplace'
            )
        );

        $routes['module-apmarketplace-productlist'] = array(
            'controller' => 'productlist',
            'rule' => 'productlist',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'apmarketplace'
            )
        );

        $routes['module-apmarketplace-productadd'] = array(
            'controller' => 'productadd',
            'rule' => 'productadd',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'apmarketplace',
            )
        );

        $routes['module-apmarketplace-productedit'] = array(
            'controller' => 'productedit',
            'rule' => 'productedit',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'apmarketplace',
            )
        );

        $routes['module-apmarketplace-orderslist'] = array(
            'controller' => 'orderslist',
            'rule' => 'orderslist',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'apmarketplace',
            )
        );

        $routes['module-apmarketplace-orderedit'] = array(
            'controller' => 'orderedit',
            'rule' => 'orderedit',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'apmarketplace',
            )
        );


        $routes['module-apmarketplace-discountlist'] = array(
            'controller' => 'discountlist',
            'rule' => 'discountlist',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'apmarketplace',
            )
        );

        $routes['module-apmarketplace-discountadd'] = array(
            'controller' => 'discountadd',
            'rule' => 'discountadd',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'apmarketplace',
            )
        );

        $routes['module-apmarketplace-discountedit'] = array(
            'controller' => 'discountedit',
            'rule' => 'discountedit',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'apmarketplace',
            )
        );

        $routes['module-apmarketplace-orderpay'] = array(
            'controller' => 'orderpay',
            'rule' => 'orderpay',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'apmarketplace',
            )
        );

        $routes['module-apmarketplace-review'] = array(
            'controller' => 'review',
            'rule' => 'review',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'apmarketplace',
            )
        );

        $routes['module-apmarketplace-setting'] = array(
            'controller' => 'setting',
            'rule' => 'setting',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'apmarketplace',
            )
        );

        return $routes;
    }

    public function uploadImageServiceVendor($file)
    {
        $path = $this->getDir().'views/img/vendor/';
        $tmp_name = $file['tmp_name'];
        $name = $file['name'];
        move_uploaded_file($tmp_name, $path.$name);
    }
}
