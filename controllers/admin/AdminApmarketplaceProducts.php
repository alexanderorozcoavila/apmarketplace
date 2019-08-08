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

require_once _PS_MODULE_DIR_ . 'apmarketplace/includer.php';

class AdminApmarketplaceProductsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->table = 'apmarketplace_product';
        $this->identifier = 'id_apmarketplace_product';
        $this->className = 'ApmarketplaceProduct';
        $this->lang = false;
        $this->bootstrap = true;
        parent::__construct();
        $this->addRowAction('edit');
        $this->_select = 'sa.user_name, p_l.name, p.active, p.date_add';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'apmarketplace_vendor` 
                sa ON (a.`id_apmarketplace_vendor` = sa.`id_apmarketplace_vendor`)';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'product` 
                p ON (a.`id_product` = p.`id_product`)';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'product_lang` 
                p_l ON (a.`id_product` = p_l.`id_product`)';
        $this->_where .= 'AND p_l.id_lang = ' .
        (int)Context::getContext()->language->id;
        $this->fields_list = array(
            'id_apmarketplace_product' => array('title' => $this->l('ID Product Vendor'), 'width' => 100),
            'name' => array('title' => $this->l('Product Name'), 'width' => 100),
            'user_name' => array('title' => $this->l('Vendor Name'), 'width' => 100),
            'active' => array(
                'title' => $this->l('Status'),
                'width' => 100,
                'type' => 'bool',
                'active' => 'status'
            ),
            'date_add' => array('title' => $this->l('Date Add'), 'width' => 100, 'type' => 'date'),
        );
    }

    public function renderView()
    {
        return $this->renderForm();
    }
    
    public function renderDetails()
    {
        return $this->renderForm();
    }

    public function renderForm()
    {
        if (Tools::getIsset('id_apmarketplace_product')) {
            $obj = new ApmarketplaceProduct(Tools::getValue('id_apmarketplace_product'));
            Tools::redirectAdmin(Dispatcher::getInstance()->createUrl(
                'AdminProducts',
                $this->context->language->id,
                array(
                    'id_product' => $obj->id_product,
                    'token' => Tools::getAdminTokenLite('AdminProducts'),
                ),
                false
            ));
        } else {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminProducts', true));
        }
    }
}
