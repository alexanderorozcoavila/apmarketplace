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
 *  DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade Prestashop to newer
 * versions in the future.
 *
 *  @author     Apollotheme
 *  @copyright  Apollothem
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

include_once(dirname(__FILE__).'/../../config/config.inc.php');

include_once(dirname(__FILE__).'/../../init.php');

include_once(dirname(__FILE__).'/includer.php');

$context = Context::getContext();
$id_lang = $context->language->id;
$module = new Apmarketplace();

if (Tools::getValue('action') == 'add-new-review') {
    $array_result = array();
    $result = true;
    $id_guest = 0;

    $id_customer = $context->customer->id;
    if (!$id_customer) {
        $id_guest = $context->cookie->id_guest;
    }

    $id_product_review = Tools::getValue('id_product_review');
    $new_review_title = Tools::getValue('new_review_title');
    $new_review_content = Tools::getValue('new_review_content');
    $criterion = Tools::getValue('criterion');
    $errors = array();
    if (!Validate::isInt($id_product_review)) {
        $errors[] = $module->l('Product ID is incorrect', 'ajax');
    }
    if (!$new_review_title || !Validate::isGenericName($new_review_title)) {
        $errors[] = $module->l('Title is incorrect', 'ajax');
    }
    if (!$new_review_content || !Validate::isMessage($new_review_content)) {
        $errors[] = $module->l('Comment is incorrect', 'ajax');
    }
    if (!$criterion) {
        $errors[] = $module->l('You must give a rating', 'ajax');
    }

    $product = new Product($id_product_review);
    if (!$product->id) {
        $errors[] = $module->l('Product not found', 'ajax');
    }
    if (!count($errors)) {
        $review = new ApmarketplaceReview();
        $review->content = strip_tags($new_review_content);
        $review->id_product = (int) $id_product_review;
        $review->id_customer = (int) $id_customer;
        $review->id_guest = $id_guest;
        $review->customer_name = pSQL($context->customer->firstname . ' ' . $context->customer->lastname);
        $review->title = $new_review_title;
        $review->grade = $criterion;
        $review->validate = 0;
        $review->save();
    } else {
        $result = false;
    }

    $array_result['result'] = $result;
    $array_result['errors'] = $errors;
    if ($result) {
        $array_result['sucess_mess'] = $module->l('Your comment has been added. Thank you!', 'ajax');
    }
    die(Tools::jsonEncode($array_result));
}

if (Tools::getIsset('action') && Tools::getValue('action') == 'sendMail') {
    $subject = 'Contact Vendor';
    $body = '<table>';
    $body .= '<tr>';
    $body .= '<th>Name</th>';
    $body .= '<th>Phone</th>';
    $body .= '<th>Email</th>';
    $body .= '<th>Message</th>';
    $body .= '</tr>';
    $body .= '<tr>';
    $body .= '<td>'.Tools::getValue('name').'</td>';
    $body .= '<td>'.Tools::getValue('phone').'</td>';
    $body .= '<td>'.Tools::getValue('email').'</td>';
    $body .= '<td>'.Tools::getValue('message').'</td>';
    $body .= '</tr>';
    $body .= '</table>';
    $array = array(
      '{email}' => Configuration::get('PS_SHOP_EMAIL', null),
      '{message}' => $body
    );
    if (Mail::Send($id_lang, 'contact', $subject, $array, Tools::getValue('email_ven'), null, null, null)) {
        die('1');
    }
    die('2');
}

if (Tools::getValue('action') == 'render-modal-review') {
    $result = $module->renderModalReview(Tools::getValue('id_product'), Tools::getValue('is_logged'));
    die($result);
};

if (Tools::getIsset('action') && Tools::getValue('action') == 'refreshDashboard') {
    $id_module = null;
    if ($module = Tools::getValue('module')) {
        $module_obj = Module::getInstanceByName($module);
        if (Validate::isLoadedObject($module_obj)) {
            $id_module = $module_obj->id;
        }
    }
    if (Tools::getIsset('date_from') && Tools::getIsset('date_to')) {
        $date_from = Tools::getValue('date_from');
        $date_to = Tools::getValue('date_to');
    }
    $params = array(
        'date_from' => $date_from,
        'date_to' => $date_to,
        'dashboard_use_push' => (int)Tools::getValue('dashboard_use_push'),
        'extra' => (int)Tools::getValue('extra')
    );
    die(Tools::jsonEncode(Hook::exec(
        'dashboardData',
        $params,
        $id_module,
        true,
        true,
        (int)Tools::getValue('dashboard_use_push')
    )));
}

if (Tools::getIsset('action') && Tools::getValue('action') == 'deleteProduct') {
    $id_product = Tools::getValue('id_product');
    $id_vendor = $context->cookie->cookie_vendor;
    $product = new ApmarketplaceProduct();
    $array_product = $product->getProductVendor($id_vendor, $id_product);
    if (empty($array_product)) {
        die('2');
    }
    $products = new Product($id_product);
    $products->delete();
    $product->deleteProduct($id_vendor, $id_product);
    die('1');
}

if (Tools::getIsset('action') && Tools::getValue('action') == 'deleteImage') {
    $id_product = Tools::getValue('id_product');
    $id_vendor = $context->cookie->cookie_vendor;
    $product = new ApmarketplaceProduct();
    $product = $product->getProductVendor($id_vendor, $id_product);
    if (empty($product)) {
        die('2');
    }
    $image = new Image(Tools::getValue('id_image'));
    $image->delete();
    die('1');
}

if (Tools::getIsset('action') && Tools::getValue('action') == 'deleteCartRule') {
    $id_cart_rule = Tools::getValue('id');
    $cart_rule = new CartRule($id_cart_rule);
    $id_product = $cart_rule->gift_product;
    $id_vendor = $context->cookie->cookie_vendor;
    $product = new ApmarketplaceProduct();
    $product = $product->getProductVendor($id_vendor, $id_product);
    if (empty($product)) {
        die('2');
    }
    $cart_rule->delete();
    die('1');
}

if (Tools::getIsset('action') && Tools::getValue('action') == 'searchProduct') {
    $name = Tools::getValue('value');
    $products = ApmarketplaceVendorStats::searchProducts($name);
    die(Tools::jsonEncode($products));
}

if (Tools::getIsset('action') && Tools::getValue('action') == 'searchProductHome') {
    $name = Tools::getValue('name');
    $products = Product::searchByName($id_lang, $name);
    if (!empty($products)) {
        foreach ($products as $key_p => $val_p) {
            $product = new Product((int)$val_p['id_product'], true, $id_lang);
            $products[$key_p]['link'] = $context->link->getProductLink($product);
            $image = Image::getCover((int)$val_p['id_product']);
            $link = new Link();
            $imagePath = $link->getImageLink(
                $product->link_rewrite,
                $image['id_image'],
                ImageType::getFormattedName('home')
            );
            if (Tools::usingSecureMode() == false) {
                $imagePath = 'http://' . $imagePath;
            } else {
                $imagePath = 'https://' . $imagePath;
            }
            $products[$key_p]['image'] = $imagePath;
            $products[$key_p]['image'] = $imagePath;
            $products[$key_p]['price_tax_incl'] = Tools::displayPrice($val_p['price_tax_incl']);
            $products[$key_p]['price_tax_excl'] = Tools::displayPrice($val_p['price_tax_excl']);
            
        }
        die(Tools::jsonEncode($products));
    } else {
        die('1');
    }
}

if (Tools::getIsset('action') && Tools::getValue('action') == 'get-new-question') {
    $questions = new ApmarketplaceQuestion();
    $questions = $questions->getByValidate(0);
    die(Tools::jsonEncode(array(
        'questions' => count($questions)
    )));
}

if (Tools::getIsset('action') && Tools::getValue('action') == 'get-new-review') {
    $reviews = ApmarketplaceReview::getByValidate(0, false);
    die(Tools::jsonEncode(array(
        'number_review' => count($reviews)
    )));
}

if (Tools::getIsset('action') && Tools::getValue('action') == 'get-new-pay-order') {
    $order = new ApmarketplaceOrder();
    $orders = $order->getByValidate(0);
    die(Tools::jsonEncode(array(
        'orders' => count($orders)
    )));
}

if (Tools::getIsset('action') && Tools::getValue('action') == 'ajaxQuestionAnswer') {
    $id_question = Tools::getValue('id_question');
    if ($id_question == 0) {
        $question = new ApmarketplaceQuestion();
        $question->id_product = Tools::getValue('id_product');
        $question->id_customer = Tools::getValue('id_customer');
        $question->content = Tools::getValue('content');
        $question->active_ques = 0;
        $question->add(true, false);
    } else {
        $answer = new ApmarketplaceAnswer();
        $answer->id_apmarketplace_question = $id_question;
        $answer->id_customer = Tools::getValue('id_customer');
        $answer->answer = Tools::getValue('content');
        $answer->active_ans = 0;
        $answer->add(true, false);
    }
    die('1');
}
