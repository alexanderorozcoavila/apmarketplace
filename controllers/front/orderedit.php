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

require_once _PS_MODULE_DIR_ . 'apmarketplace/controllers/front/orderslist.php';

class ApmarketplaceordereditModuleFrontController extends ModuleFrontController
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
        parent::__construct();
    }

    public function initContent()
    {
        $vars = array();
        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'orderedit') {
            $baseurl = $this->context->shop->getBaseURL(true, true);
            if ($this->context->cookie->__isset('cookie_vendor')) {
                $this->addJS($baseurl.'modules/apmarketplace/views/js/product.js');
                $this->addCSS($baseurl.'modules/apmarketplace/views/css/front.css');
                $vars['baseurl'] = $baseurl;
                $vars['check'] = 1;
                $id_apmarketplace_vendor = $this->id_vendor = $this->context->cookie->cookie_vendor;
                $vendor = new ApmarketplaceVendors($id_apmarketplace_vendor);
                if ($vendor->active == 0) {
                    $vars['check'] = 0;
                }
                $where = 'AND a.`id_order` = ' . (int)Tools::getValue('id');
                $orders = new ApmarketplaceorderslistModuleFrontController();
                $orders = $orders->getOrder($where);
                if (!empty($orders)) {
                    $orders = $orders[0];
                    $vars['customer_thread_message'] = count(
                        CustomerThread::getCustomerMessages(
                            $orders['id_customer'],
                            null,
                            Tools::getValue('id')
                        )
                    );
                    $vars['orders'] = $orders;
                    $order = new Order(Tools::getValue('id'));
                    $vars['order'] = $order;
                    $product = $order->getProducts();
                    $vars['products'] = $product;
                    $history = $this->getHistory($order);
                    $vars['history'] = $history;
                    $statuses = OrderState::getOrderStates($this->id_lang);
                    $vars['states'] = $statuses;
                    $vars['currentState'] = $order->getCurrentOrderState();
                    $vars['recalculate_shipping_cost'] = (int)Configuration::get('PS_ORDER_RECALCULATE_SHIPPING');
                    $vars['carrier_list'] = $this->getCarrierList($order);
                    $vars['current_id_lang'] = $this->id_lang;
                    $payment_methods = array();
                    foreach (PaymentModule::getInstalledPaymentModules() as $payment) {
                        $module = Module::getInstanceByName($payment['name']);
                        if (Validate::isLoadedObject($module) && $module->active) {
                            $payment_methods[] = $module->displayName;
                        }
                    }
                    $vars['payment_methods'] = $payment_methods;
                    $vars['currencies'] = Currency::getCurrenciesByIdShop($order->id_shop);
                    $leo_customer = new Customer($order->id_customer);
                    $vars['leo_customer'] = $leo_customer;
                    $vars['leo_customerStats'] = $leo_customer->getStats();
                    $vars['customer_addresses'] = $leo_customer->getAddresses($this->id_lang);
                    $addressInvoice = new Address($order->id_address_invoice, $this->id_lang);
                    if (Validate::isLoadedObject($addressInvoice) && $addressInvoice->id_state) {
                        $invoiceState = new State((int)$addressInvoice->id_state);
                    }
                    if ($order->id_address_invoice == $order->id_address_delivery) {
                        $addressDelivery = $addressInvoice;
                        if (isset($invoiceState)) {
                            $deliveryState = $invoiceState;
                        }
                    } else {
                        $addressDelivery = new Address($order->id_address_delivery, $this->id_lang);
                        if (Validate::isLoadedObject($addressDelivery) && $addressDelivery->id_state) {
                            $deliveryState = new State((int)($addressDelivery->id_state));
                        }
                    }
                    $addresses = array(
                        'delivery' => $addressDelivery,
                        'deliveryState' => isset($deliveryState) ? $deliveryState : null,
                        'invoice' => $addressInvoice,
                        'invoiceState' => isset($invoiceState) ? $invoiceState : null
                    );
                    $vars['addresses'] = $addresses;
                    $products = $this->getProducts($order);
                    $order_product_price = 0;
                    $total_taxes = 0;
                    foreach ($products as $product) {
                        $order_product_price = $order_product_price + $product['product_price'];
                        $total_taxes = $total_taxes +
                        ($product['total_price_tax_incl'] -
                        $product['total_price_tax_excl']);
                    }
                    $total = $order_product_price - $order->total_discounts_tax_excl + $order->total_wrapping_tax_excl +
                            $order->total_shipping_tax_excl + $total_taxes;
                    $total_commission = $total *
                    (100 - Configuration::get('APMARKETPLACE_CONFIG_COMMISSION_VALUE', null)) / 100;
                    $vars['order_product_price'] = Tools::displayPrice($order_product_price);
                    $vars['order_discount_price'] = Tools::displayPrice($order->total_discounts_tax_excl);
                    $vars['order_wrapping_price'] = Tools::displayPrice($order->total_wrapping_tax_excl);
                    $vars['order_shipping_price'] = Tools::displayPrice($order->total_shipping_tax_excl);
                    $vars['total_taxes'] = Tools::displayPrice($total_taxes);
                    $vars['total'] = Tools::displayPrice($total);
                    $vars['total_commission'] = Tools::displayPrice($total_commission);
                    $vars['products'] = $products;
                    $vars['currency'] = new Currency($order->id_currency);
                    $vars['percent'] = Configuration::get('APMARKETPLACE_CONFIG_COMMISSION_VALUE', null);
                    $vars['order_documents'] = count($order->getDocuments());
                    $vars['order_shipping'] = count($order->getShipping());
                    $vars['order_returns'] = count($order->getReturn());

                    if (Tools::isSubmit('submitState')) {
                        $this->updateStatus($order);
                    }
                    if (Tools::isSubmit('submitAddressShipping')) {
                        $this->updateAddressShipping($order);
                    }
                    if (Tools::isSubmit('submitAddressInvoice')) {
                        $this->updateAddressInvoice($order);
                    }
                    if (Tools::getIsset('submitAction')) {
                        if (Tools::getValue('submitAction') == 'generateInvoicePDF') {
                            $this->generateInvoicePDFByIdOrderInvoice(Tools::getValue('id_order_invoice'));
                        }
                        if (Tools::getValue('submitAction') == 'generateDeliverySlipPDF') {
                            $this->generateDeliverySlipPDFByIdOrderInvoice(Tools::getValue('id_order_invoice'));
                        }
                    }
                }

                $this->setTemplate('module:apmarketplace/views/templates/front/order/orderedit.tpl');
                $this->context->smarty->assign($vars);
                parent::initContent();
            } else {
                Tools::redirect($baseurl . 'vendorlogin?login=1');
            }
        }
    }

    public function getProducts($order)
    {
        $shop = $this->context->shop;
        $url = '';
        if (Tools::usingSecureMode() == false) {
            $url = 'http://' . $shop->domain;
        } else {
            $url = 'https://' . $shop->domain;
        }
        $currency = Currency::getCurrenciesByIdShop((int)$order->id_shop);
        $products = $order->getProducts();

        foreach ($products as $key_p => $val_p) {
            $product_vendor = new ApmarketplaceProduct();
            $product_vendor = $product_vendor->getProductVendor((int)$this->id_vendor, (int)$val_p['product_id']);
            if (empty($product_vendor)) {
                unset($products[$key_p]);
            }
        }

        foreach ($products as &$product) {
            if ($product['image'] != null) {
                $name = 'product_mini_'.(int)$product['product_id'].
                (isset($product['product_attribute_id']) ? '_'.(int)$product['product_attribute_id'] : '').'.jpg';
                // generate image cache, only for back office
                $product['image_tag'] = ImageManager::thumbnail(
                    _PS_IMG_DIR_.'p/'.$product['image']->getExistingImgPath().'.jpg',
                    $name,
                    45,
                    'jpg'
                );
                $product['image_tag'] = str_replace('img src="', 'img src="'.$url.'', $product['image_tag']);
                if (file_exists(_PS_TMP_IMG_DIR_.$name)) {
                    $product['image_size'] = getimagesize(_PS_TMP_IMG_DIR_.$name);
                } else {
                    $product['image_size'] = false;
                }
            }
        }

        foreach ($products as &$product) {
            $customized_product_quantity = 0;
            if (is_array($product['customizedDatas'])) {
                foreach ($product['customizedDatas'] as $customizationPerAddress) {
                    foreach ($customizationPerAddress as $customization) {
                        $customized_product_quantity += (int)$customization['quantity'];
                    }
                }
            }
            $product['customized_product_quantity'] = $customized_product_quantity;
            $product['current_stock'] = StockAvailable::getQuantityAvailableByProduct(
                $product['product_id'],
                $product['product_attribute_id'],
                $product['id_shop']
            );
            $resume = OrderSlip::getProductSlipResume($product['id_order_detail']);
            $product['quantity_refundable'] = $product['product_quantity'] - $resume['product_quantity'];
            $product['amount_refundable'] = $product['total_price_tax_excl'] - $resume['amount_tax_excl'];
            $product['amount_refundable_tax_incl'] = $product['total_price_tax_incl'] - $resume['amount_tax_incl'];
            $product['amount_refund'] = $order->getTaxCalculationMethod() ?
            Tools::displayPrice($resume['amount_tax_excl'], $currency) :
            Tools::displayPrice($resume['amount_tax_incl'], $currency);
            $product['refund_history'] = OrderSlip::getProductSlipDetail($product['id_order_detail']);
            $product['return_history'] = OrderReturn::getProductReturnDetail($product['id_order_detail']);

            // if the current stock requires a warning
            if ($product['current_stock'] <= 0) {
                $this->displayWarning(
                    $this->l('This product is out of stock: ').' '.$product['product_name']
                );
            }
            if ($product['id_warehouse'] != 0) {
                $warehouse = new Warehouse((int)$product['id_warehouse']);
                $product['warehouse_name'] = $warehouse->name;
                $warehouse_location = WarehouseProductLocation::getProductLocation(
                    $product['product_id'],
                    $product['product_attribute_id'],
                    $product['id_warehouse']
                );
                if (!empty($warehouse_location)) {
                    $product['warehouse_location'] = $warehouse_location;
                } else {
                    $product['warehouse_location'] = false;
                }
            } else {
                $product['warehouse_name'] = '--';
                $product['warehouse_location'] = false;
            }
        }

        foreach ($products as &$product) {
            $pack_items = $product['cache_is_pack'] ?
            Pack::getItemTable($product['id_product'], $this->context->language->id, true) : array();
            foreach ($pack_items as &$pack_item) {
                $pack_item['current_stock'] = StockAvailable::getQuantityAvailableByProduct(
                    $pack_item['id_product'],
                    $pack_item['id_product_attribute'],
                    $pack_item['id_shop']
                );
                // if the current stock requires a warning
                if ($product['current_stock'] <= 0) {
                    $this->displayWarning(
                        $this->l('This product, included in package ('.$product['product_name'].') is out of stock: ')
                        .' '.$pack_item['product_name']
                    );
                }
                $this->setProductImageInformations($pack_item);
                if ($pack_item['image'] != null) {
                    $name = 'product_mini_'.(int)$pack_item['id_product'].
                    (isset($pack_item['id_product_attribute']) ?
                    '_'.(int)$pack_item['id_product_attribute'] : '').'.jpg';
                    $pack_item['image_tag'] = ImageManager::thumbnail(
                        _PS_IMG_DIR_.'p/'.$pack_item['image']->getExistingImgPath().'.jpg',
                        $name,
                        45,
                        'jpg'
                    );
                    if (file_exists(_PS_TMP_IMG_DIR_.$name)) {
                        $pack_item['image_size'] = getimagesize(_PS_TMP_IMG_DIR_.$name);
                    } else {
                        $pack_item['image_size'] = false;
                    }
                }
            }
            $product['pack_items'] = $pack_items;
        }

        return $products;
    }

    public function getCarrierList($order)
    {
        unset($order);
        $cart = $this->context->cart;
        $address = new Address((int) $cart->id_address_delivery);
        return Carrier::getCarriersForOrder(Address::getZoneById((int)$address->id), null, $cart);
    }

    public function getHistory($order)
    {
        $history = $order->getHistory($this->id_lang);
        foreach ($history as &$order_state) {
            $order_state['text-color'] = Tools::getBrightness($order_state['color']) < 128 ? 'white' : 'black';
        }
        return $history;
    }

    public function updateAddressShipping($order)
    {
        $address = new Address(Tools::getValue('id_address'));
        $order->id_address_delivery = $address->id;
        $order->update();
        $order->refreshShippingCost();
        Tools::redirect($this->context->shop->getBaseURL(true, true) . 'orderedit?id=' . (int)$order->id);
    }

    public function updateAddressInvoice($order)
    {
        $address = new Address(Tools::getValue('id_address_invoice'));
        $order->id_address_delivery = $address->id;
        $order->update();
        $order->refreshShippingCost();
        Tools::redirect($this->context->shop->getBaseURL(true, true) . 'orderedit?id=' . (int)$order->id);
    }

    public function generateInvoicePDFByIdOrderInvoice($id_order_invoice)
    {
        $order_invoice = new OrderInvoice((int)$id_order_invoice);
        $this->generatePDF($order_invoice, PDF::TEMPLATE_INVOICE);
    }

    public function generateDeliverySlipPDFByIdOrderInvoice($id_order_invoice)
    {
        $order_invoice = new OrderInvoice((int)$id_order_invoice);
        $this->generatePDF($order_invoice, PDF::TEMPLATE_DELIVERY_SLIP);
    }

    public function generatePDF($object, $template)
    {
        $pdf = new PDF($object, $template, Context::getContext()->smarty);
        $pdf->render();
    }

    public function updateStatus($order)
    {
        $order_state = new OrderState(Tools::getValue('id_order_state'));
        if (Validate::isLoadedObject($order_state)) {
            $current_order_state = $order->getCurrentOrderState();
            if ($current_order_state->id != $order_state->id) {
                $history = new OrderHistory();
                $history->id_order = $order->id;
                $history->id_employee = 0;
                $use_existings_payment = false;
                if (!$order->hasInvoice()) {
                    $use_existings_payment = true;
                }
                $history->changeIdOrderState((int)$order_state->id, $order, $use_existings_payment);
                $history->changeIdOrderState((int)$order_state->id, $order, $use_existings_payment);

                $carrier = new Carrier($order->id_carrier, $order->id_lang);
                $templateVars = array();
                if ($history->id_order_state == Configuration::get('PS_OS_SHIPPING') && $order->shipping_number) {
                    $templateVars = array('{followup}' => str_replace('@', $order->shipping_number, $carrier->url));
                }
                if ($history->addWithemail(true, $templateVars)) {
                    if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                        foreach ($order->getProducts() as $product) {
                            if (StockAvailable::dependsOnStock($product['product_id'])) {
                                StockAvailable::synchronize($product['product_id'], (int)$product['id_shop']);
                            }
                        }
                    }
                }
            }
        }
        Tools::redirect($this->context->shop->getBaseURL(true, true) . 'orderedit?id=' . $order->id);
    }
}
