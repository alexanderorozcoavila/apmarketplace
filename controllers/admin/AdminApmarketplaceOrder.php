<?php
/**
 * 2007-2015 Leotheme
 *
 * NOTICE OF LICENSE
 *
 * Leo feature for prestashop 1.7: ajax cart, review, compare, wishlist at product list
 *
 * DISCLAIMER
 *
 *  @author    leotheme <leotheme@gmail.com>
 *  @copyright 2007-2015 Leotheme
 *  @license   http://leotheme.com - prestashop template provider
 */

require_once(_PS_MODULE_DIR_.'apmarketplace/classes/ApmarketplaceOrder.php');

class AdminApmarketplaceOrderController extends ModuleAdminController
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->bootstrap = true;
        $this->table = 'apmarketplace_order';
        $this->identifier = 'id_apmarketplace_order';
        $this->className = 'ApmarketplaceOrder';
        parent::__construct();
    }
    
    public function renderList()
    {
        $this->toolbar_title = $this->l('Order');
        $return = null;
        $return .= $this->renderModerateOrder();
        $return .= parent::renderList();
        $return .= $this->renderReviewsList();
        return $return;
    }

    public function postProcess()
    {
        if (count($this->errors) > 0) {
            if ($this->ajax) {
                $array = array('hasError' => true, 'errors' => $this->errors[0]);
                die(Tools::jsonEncode($array));
            }
            return;
        }

        if (Tools::isSubmit('payorder') && Tools::getValue('id_apmarketplace_order')) {
            $id_apmarketplace_order = Tools::getValue('id_apmarketplace_order');
            $order = new ApmarketplaceOrder((int)$id_apmarketplace_order);
            $order->validate();
            $this->redirect_after = self::$currentIndex.'&token='.$this->token;
        }
    }
    
    public function renderModerateOrder()
    {
        $return = null;
        $order = new ApmarketplaceOrder();
        $orders = $order->getByValidate(0);

        if (count($orders) > 0) {
            $fields_list = $this->getStandardFieldList();
            $actions = array('approve');
            $helper = new HelperList();
            $helper->shopLinkType = '';
            $helper->simple_header = true;
            $helper->actions = $actions;
            $helper->show_toolbar = false;
            $helper->module = $this->module;
            $helper->listTotal = count($orders);
            $helper->identifier = 'id_apmarketplace_order';
            $helper->title = $this->l('Orders waiting for pay');
            $helper->table = 'apmarketplace_order';
            $helper->token = $this->token;
            $helper->currentIndex = self::$currentIndex;
            $helper->no_link = true;
            $return .= $helper->generateList($orders, $fields_list);
        }
        return $return;
    }
    
    public function renderReviewsList()
    {
        $order = new ApmarketplaceOrder();
        $orders = $order->getByValidate(1);

        $fields_list = $this->getStandardFieldList();
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->actions = array('delete');
        $helper->show_toolbar = false;
        $helper->module = $this->module;
        $helper->listTotal = count($orders);
        $helper->identifier = 'id_apmarketplace_order';
        $helper->title = $this->l('Orders has been paid');
        $helper->table = 'apmarketplace_order';
        $helper->token = $this->token;
        $helper->currentIndex = self::$currentIndex;
        $helper->no_link = true;

        return $helper->generateList($orders, $fields_list);
    }
    
    public function displayApproveLink($token, $id, $name = null)
    {
        unset($token);
        unset($name);
        $template = $this->createTemplate('list_action_approve.tpl');
        $template->assign(array(
            'href' => $this->context->link->getAdminLink('AdminApmarketplaceOrder').
            '&payorder&id_apmarketplace_order='.(int)$id,
            'action' => $this->l('Pay Order'),
        ));
        return $template->fetch();
    }
    
    public function getStandardFieldList()
    {
        return array(
            'id_apmarketplace_order' => array(
                'title' => $this->l('ID'),
                'type' => 'text',
            ),
            'id_product' => array(
                'title' => $this->l('ID Product'),
                'type' => 'text',
            ),
            'id_vendor' => array(
                'title' => $this->l('ID Vendor'),
                'type' => 'text',
            ),
            'name' => array(
                'title' => $this->l('Product Name'),
                'type' => 'text',
            ),
            'first_name' => array(
                'title' => $this->l('First Name'),
                'type' => 'text',
            ),
            'last_name' => array(
                'title' => $this->l('Last Name'),
                'type' => 'text',
            ),
            'email' => array(
                'title' => $this->l('Email'),
                'type' => 'text',
            ),
            'total_price_tax_incl' => array(
                'title' => $this->l('Total'),
                'type' => 'price',
            ),
            'total' => array(
                'title' => $this->l('Total After Commission'),
                'type' => 'price',
            ),
        );
    }
}
