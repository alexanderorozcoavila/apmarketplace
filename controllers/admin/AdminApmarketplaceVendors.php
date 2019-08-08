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

class AdminApmarketplaceVendorsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->table = 'apmarketplace_vendor';
        $this->identifier = 'id_apmarketplace_vendor';
        $this->className = 'ApmarketplaceVendors';
        $this->lang = false;
        $this->bootstrap = true;

        $this->addRowAction('add');
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        parent::__construct();
        $this->fields_list = array(
            'id_apmarketplace_vendor' => array('title' => $this->l('ID Vendor'), 'width' => 100),
            'email' => array('title' => $this->l('Email'), 'width' => 100),
            'phone' => array('title' => $this->l('Phone Number'), 'width' => 100),
            'image' => array(
                'title' => $this->l('Image'),
                'width' => 100,
                'type' => 'image',
                'filter' => false,
                'search' => false
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'width' => 100,
                'type' => 'bool',
                'active' => 'status'
            ),
            'date_add' => array('title' => $this->l('Date Register'), 'width' => 100),
        );
    }

    public function renderForm()
    {
        if (Tools::getIsset('id_apmarketplace_vendor')) {
            $id_vendor = Tools::getValue('id_apmarketplace_vendor');
            $array_vendor = array();
            $products = new ApmarketplaceProduct();
            $product = $products->getProductByIdVendor((int)$id_vendor);
            $item_sold = 0;
            $total_order = 0;
            $review = 0;

            if (!empty($product)) {
                foreach ($product as $val_p) {
                    $vendor_orders = new ApmarketplaceOrder();
                    $vendor_order = $vendor_orders->getIdProductByIdVendor($id_vendor, $val_p['id_product']);
                    if (!empty($vendor_order)) {
                        $item_sold = $item_sold + 1;
                    }
                    $order = $vendor_orders->getOrderByIdVendor($id_vendor, $val_p['id_product']);
                    if (!empty($order)) {
                        $total_order = $total_order + 1;
                    }
                    $review = $review + ApmarketplaceReview::getReviewNumber($val_p['id_product']);
                }
            }

            $vendors = new ApmarketplaceVendors($id_vendor);
            $array_vendor['product'] = count($product);
            $array_vendor['item_sold'] = $item_sold;
            $array_vendor['total_order'] = $total_order;
            $array_vendor['review'] = $review;
            $this->fields_form = array(
                'legend' => array(
                    'title' => $this->l('Apmarketplace Vendors'),
                    'icon' => 'icon-folder-close'
                ),
                'input' => array(
                    array(
                        'type' => 'leo_vendor',
                        'name' => 'leo_vendor',
                        'array_vendor' => $array_vendor,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('First Name'),
                        'name' => 'first_name',
                        'required' => true,
                        'hint' => $this->l('First Name of Vendor'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Last Name'),
                        'name' => 'last_name',
                        'required' => true,
                        'hint' => $this->l('Last Name of Vendor'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Email'),
                        'name' => 'email',
                        'required' => true,
                        'hint' => $this->l('Email of Vendor'),
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Image'),
                        'name' => 'leo_image',
                        'hint' => $this->l('Upload Image Vendor'),
                        'thumb' => $this->context->shop->getBaseURL(true, true).'modules/apmarketplace/views/img/vendor/' . $vendors->image,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Phone Number'),
                        'name' => 'phone',
                        'required' => true,
                        'hint' => $this->l('Phone of Vendor'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Facebook'),
                        'name' => 'fb',
                        'desc' => $this->l('https://www.facebook.com'),
                        'hint' => $this->l('Facebook of Vendor'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Twitter'),
                        'name' => 'tt',
                        'desc' => $this->l('https://twitter.com'),
                        'hint' => $this->l('Twitter of Vendor'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Free Plan'),
                        'name' => 'fax',
                        'hint' => $this->l('Cantidad de Productos permitidos'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Instagram'),
                        'name' => 'ins',
                        'desc' => $this->l('https://www.instagram.com/'),
                        'hint' => $this->l('Instagram of Vendor'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Shop Url'),
                        'name' => 'shop_url',
                        'desc' => $this->l('https://example.com'),
                        'hint' => $this->l('Shop Url of Vendor'),
                    ),
                    array(
                        'type' => 'password',
                        'label' => $this->l('Password'),
                        'name' => 'pass_word',
                        'required' => true,
                        'hint' => $this->l('Password')
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Active Vendors'),
                        'name' => 'active',
                        'is_bool' => true,
                        'required' => true,
                        'hint' => $this->l('Active Vendors'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'buttons' => array(
                    'save-and-stay' => array(
                        'title' => $this->l('Save and Stay'),
                        'name' => 'submitAdd'.$this->table.'AndStay',
                        'type' => 'submit',
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-save'
                    )
                )
            );
        } else {
            $this->fields_form = array(
                'legend' => array(
                    'title' => $this->l('Apmarketplace Vendors'),
                    'icon' => 'icon-folder-close'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('User Name'),
                        'name' => 'user_name',
                        'required' => true,
                        'hint' => $this->l('User Name of Vendor'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('First Name'),
                        'name' => 'first_name',
                        'required' => true,
                        'hint' => $this->l('First Name of Vendor'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Last Name'),
                        'name' => 'last_name',
                        'required' => true,
                        'hint' => $this->l('Last Name of Vendor'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Email'),
                        'name' => 'email',
                        'required' => true,
                        'hint' => $this->l('Email of Vendor'),
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Image'),
                        'name' => 'leo_image',
                        'hint' => $this->l('Upload Image Vendor')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Phone Number'),
                        'name' => 'phone',
                        'required' => true,
                        'hint' => $this->l('Phone of Vendor'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Facebook'),
                        'name' => 'fb',
                        'desc' => $this->l('https://www.facebook.com'),
                        'hint' => $this->l('Facebook of Vendor'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Twitter'),
                        'name' => 'tt',
                        'desc' => $this->l('https://twitter.com'),
                        'hint' => $this->l('Twitter of Vendor'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Free Plan'),
                        'name' => 'fax',
                        'hint' => $this->l('Cantidad de Productos Permitidos'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Instagram'),
                        'name' => 'ins',
                        'desc' => $this->l('https://www.instagram.com/'),
                        'hint' => $this->l('Instagram of Vendor'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Shop Url'),
                        'name' => 'shop_url',
                        'desc' => $this->l('https://example.com'),
                        'hint' => $this->l('Shop Url of Vendor'),
                    ),
                    array(
                        'type' => 'password',
                        'label' => $this->l('Password'),
                        'name' => 'pass_word',
                        'required' => true,
                        'hint' => $this->l('Password')
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Active Vendors'),
                        'name' => 'active',
                        'is_bool' => true,
                        'required' => true,
                        'hint' => $this->l('Active Vendors'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'buttons' => array(
                    'save-and-stay' => array(
                        'title' => $this->l('Save and Stay'),
                        'name' => 'submitAdd'.$this->table.'AndStay',
                        'type' => 'submit',
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-save'
                    )
                )
            );
        }

        return parent::renderForm();
    }

    public function processAdd()
    {
        $obj =  new ApmarketplaceVendors();
        $check = $obj->checkUserVendor(Tools::getValue('user_name'));
        if (count($check) > 0) {
            Tools::displayError('Account already exists !!');
        } else {
            $target_file = basename($_FILES["leo_image"]["name"]);
            $imageFileType = Tools::strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $new_name = rand((int)100000000, (int)999999999) . '_' . rand((int)1000000000, (int)9999999999) . '.' . $imageFileType;
            $_FILES['leo_image']['name'] = $new_name;
            $obj->image = $_FILES['leo_image']['name'];
            $obj->date_add = date("Y-m-d");
            $list_obj = $obj;
            $this->module->uploadImageServiceVendor($_FILES['leo_image']);
            parent::validateRules();
            if (count($this->errors) <= 0) {
                $this->copyFromPost($list_obj, 'vendors');
                if (!$list_obj->add()) {
                    $this->errors[] = Tools::displayError('An error occurred while creating an object.')
                    .' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
                }

                if (Tools::getValue('save_and_add') === '' || ToolsCore::getValue('save_and_add')) {
                    $this->redirect_after = self::$currentIndex.'&conf=3&add'.$this->table.'&token='.$this->token;
                }
            }
            $this->errors = array_unique($this->errors);
            if (!empty($this->errors)) {
                $this->display = 'edit';
                return false;
            }
            $this->display = 'list';
            if (empty($this->errors)) {
                $this->confirmations[] = $this->_conf[3];
            }
        }
    }

    public function processUpdate()
    {
        if (Tools::getIsset('id_apmarketplace_vendor') && Tools::getValue('id_apmarketplace_vendor') != '') {
            $obj = new ApmarketplaceVendors();
            $obj->id_apmarketplace_vendor = Tools::getValue('id_apmarketplace_vendor');
            $obj->first_name = Tools::getValue('first_name');
            $obj->last_name = Tools::getValue('last_name');
            $obj->email = Tools::getValue('first_name');
            $obj->phone = Tools::getValue('phone');
            // $obj->freeplan = Tools::getValue('freeplan');
            $obj->fb = Tools::getValue('fb');
            $obj->tt = Tools::getValue('tt');
            $obj->fax = Tools::getValue('fax');
            $obj->ins = Tools::getValue('ins');
            $obj->shop_url = Tools::getValue('shop_url');
            $obj->pass_word = Tools::getValue('pass_word');
            $obj->active = Tools::getValue('active');

            if ($_FILES['leo_image']['size'] != 0) {
                $target_file = basename($_FILES["leo_image"]["name"]);
                $imageFileType = Tools::strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $new_name = rand((int)100000000, (int)999999999) . '_' . rand((int)1000000000, (int)9999999999) . '.' . $imageFileType;
                $_FILES['leo_image']['name'] = $new_name;
                $obj->image = $new_name;
                $vendor = new ApmarketplaceVendors(Tools::getValue('id_apmarketplace_vendor'));
                $link = $this->module->getDir().'views/img/vendor/' . $vendor->image;

                if (file_exists($link)) {
                    unlink($link);
                }

                $this->module->uploadImageServiceVendor($_FILES['leo_image']);
            }

            $obj->updateVendor($obj);
        }
    }

    public function processDelete()
    {
        $object = $this->loadObject();
        if ($object) {
            $link = $this->module->getDir().'views/img/vendor/' . $object->image;
            if (file_exists($link)) {
                unlink($link);
            }
            $object->deleteVendor($object->id);
        }
    }
}
