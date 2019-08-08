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

class ApmarketplaceVendorStats
{
    public function __construct()
    {
        $this->context = Context::getContext();
    }
    public function getCategories($category)
    {
        $range = '';
        $maxdepth = Configuration::get('BLOCK_CATEG_MAX_DEPTH');
        if (Validate::isLoadedObject($category)) {
            if ($maxdepth > 0) {
                $maxdepth += $category->level_depth;
            }
            $range = 'AND nleft >= '.(int)$category->nleft.' AND nright <= '.(int)$category->nright;
        }

        $resultIds = array();
        $resultParents = array();
        $result = Db::getInstance()->executeS('
            SELECT c.id_parent, c.id_category, cl.name, cl.description, cl.link_rewrite
            FROM `'._DB_PREFIX_.'category` c
            INNER JOIN `'._DB_PREFIX_.'category_lang` cl
            ON (c.`id_category` = cl.`id_category`
            AND cl.`id_lang` = '.(int)$this->context->language->id.Shop::addSqlRestrictionOnLang('cl').')
            INNER JOIN `'._DB_PREFIX_.'category_shop` cs
            ON (cs.`id_category` = c.`id_category`
            AND cs.`id_shop` = '.(int)$this->context->shop->id.')
            WHERE (c.`active` = 1
            OR c.`id_category` = '.(int)Configuration::get('PS_HOME_CATEGORY').')
            AND c.`id_category` != '.(int)Configuration::get('PS_ROOT_CATEGORY').'
            '.((int)$maxdepth != 0 ? ' AND `level_depth` <= '.(int)$maxdepth : '').'
            '.$range.'
            AND c.id_category IN (
                SELECT id_category
                FROM `'._DB_PREFIX_.'category_group`
                WHERE `id_group`
                IN ('.pSQL(implode(', ', Customer::getGroupsStatic((int)$this->context->customer->id))).')
            )
            ORDER BY `level_depth` ASC, '.(Configuration::get('BLOCK_CATEG_SORT') ?
                'cl.`name`' : 'cs.`position`').' '.
            (Configuration::get('BLOCK_CATEG_SORT_WAY') ? 'DESC' : 'ASC'));
        foreach ($result as &$row) {
            $resultParents[$row['id_parent']][] = &$row;
            $resultIds[$row['id_category']] = &$row;
        }
        return $this->getTree(
            $resultParents,
            $resultIds,
            $maxdepth,
            ($category ? $category->id : null)
        );
    }

    public function getTree(
        $resultParents,
        $resultIds,
        $maxDepth,
        $id_category = null,
        $currentDepth = 0
    ) {
        if (is_null($id_category)) {
            $id_category = $this->context->shop->getCategory();
        }

        $children = array();

        if (isset($resultParents[$id_category]) &&
            count($resultParents[$id_category]) &&
            ($maxDepth == 0 ||$currentDepth < $maxDepth)
        ) {
            foreach ($resultParents[$id_category] as $subcat) {
                $children[] = $this->getTree(
                    $resultParents,
                    $resultIds,
                    $maxDepth,
                    $subcat['id_category'],
                    $currentDepth + 1
                );
            }
        }

        if (isset($resultIds[$id_category])) {
            $link = $this->context->link->getCategoryLink($id_category, $resultIds[$id_category]['link_rewrite']);
            $name = $resultIds[$id_category]['name'];
            $desc = $resultIds[$id_category]['description'];
        } else {
            $link = $name = $desc = '';
        }

        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'productedit') {
            $product_cate = Product::getProductCategoriesFull((int)Tools::getValue('id'), (int)$this->context->language->id);
            if (!empty($product_cate)) {
                $check = 0;
                foreach ($product_cate as $val_cate) {
                    if ($id_category == $val_cate['id_category']) {
                        $check = 1;
                        break;
                    }
                }
                if ($check == 1) {
                    return array(
                        'id' => $id_category,
                        'check' => 1,
                        'link' => $link,
                        'name' => $name,
                        'desc'=> $desc,
                        'children' => $children
                    );
                }
            }
        }
        return array(
            'id' => $id_category,
            'link' => $link,
            'name' => $name,
            'desc'=> $desc,
            'children' => $children
        );
    }

    public static function getOrders($date_from, $date_to, $id_vendor)
    {
        $orders = array();
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS(
            '
            SELECT `id_cart`, LEFT(`invoice_date`, 10) AS date, COUNT(*) AS orders
            FROM `'._DB_PREFIX_.'orders` o
            LEFT JOIN `'._DB_PREFIX_.'order_state` os ON o.current_state = os.id_order_state
            WHERE `invoice_date` BETWEEN "'.pSQL($date_from).' 00:00:00"
            AND "'.pSQL($date_to).' 23:59:59" AND os.logable = 1
            '.Shop::addSqlRestriction(false, 'o').'
            GROUP BY LEFT(`invoice_date`, 10)'
        );

        if (!empty($result)) {
            $orders = ApmarketplaceVendorStats::builData($result, $orders, $id_vendor, 'orders');
        }
        return $orders;
    }

    public static function getTotalSales($date_from, $date_to, $id_vendor)
    {
        $sales = array();
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS(
            '
            SELECT `id_cart`, LEFT(`invoice_date`, 10) AS date, SUM(total_products / o.conversion_rate) AS sales
            FROM `'._DB_PREFIX_.'orders` o
            LEFT JOIN `'._DB_PREFIX_.'order_state` os ON o.current_state = os.id_order_state
            WHERE `invoice_date` BETWEEN "'.pSQL($date_from).' 00:00:00"
            AND "'.pSQL($date_to).' 23:59:59" AND os.logable = 1
            '.Shop::addSqlRestriction(false, 'o').'
            GROUP BY LEFT(`invoice_date`, 10)'
        );
        if (!empty($result)) {
            $sales = ApmarketplaceVendorStats::builData($result, $sales, $id_vendor, 'sales');
        }
        return $sales;
    }

    public static function getPurchases($date_from, $date_to, $id_vendor)
    {
        $purchases = array();
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS(
            '
            SELECT
            `id_cart`,
            LEFT(`invoice_date`, 10) as date,
            SUM(od.`product_quantity` * IF(
            od.`purchase_supplier_price` > 0,
            od.`purchase_supplier_price` / `conversion_rate`,
            od.`original_product_price` * '.(int) Configuration::get('CONF_AVERAGE_PRODUCT_MARGIN').' / 100
            )) as total_purchase_price
            FROM `'._DB_PREFIX_.'orders` o
            LEFT JOIN `'._DB_PREFIX_.'order_detail` od ON o.id_order = od.id_order
            LEFT JOIN `'._DB_PREFIX_.'order_state` os ON o.current_state = os.id_order_state
            WHERE `invoice_date` BETWEEN "'.pSQL($date_from).' 00:00:00"
            AND "'.pSQL($date_to).' 23:59:59" AND os.logable = 1
            '.Shop::addSqlRestriction(false, 'o').'
            GROUP BY LEFT(`invoice_date`, 10)'
        );
        if (!empty($result)) {
            $purchases = ApmarketplaceVendorStats::builData($result, $purchases, $id_vendor, 'total_purchase_price');
        }
        return $purchases;
    }

    public static function getExpenses($date_from, $date_to, $id_vendor)
    {
        $expenses = array();
        $orders = Db::getInstance()->ExecuteS(
            '
        SELECT
            `id_cart`,
            LEFT(`invoice_date`, 10) AS date,
            total_paid_tax_incl / o.conversion_rate AS total_paid_tax_incl,
            total_shipping_tax_excl / o.conversion_rate AS total_shipping_tax_excl,
            o.module,
            a.id_country,
            o.id_currency,
            c.id_reference AS carrier_reference
        FROM `'._DB_PREFIX_.'orders` o
        LEFT JOIN `'._DB_PREFIX_.'address` a ON o.id_address_delivery = a.id_address
        LEFT JOIN `'._DB_PREFIX_.'carrier` c ON o.id_carrier = c.id_carrier
        LEFT JOIN `'._DB_PREFIX_.'order_state` os ON o.current_state = os.id_order_state
        WHERE `invoice_date` BETWEEN "'.pSQL($date_from).' 00:00:00"
        AND "'.pSQL($date_to).' 23:59:59" AND os.logable = 1
        '.Shop::addSqlRestriction(false, 'o')
        );

        if (!empty($orders)) {
            $expenses = ApmarketplaceVendorStats::builData($orders, $expenses, $id_vendor, 'expenses');
        }
        return $expenses;
    }


    public static function builData($result, $data, $id_vendor, $key)
    {
        foreach ($result as $key_r => $val_r) {
            $array_id_product = ApmarketplaceVendorStats::getIdProductCart($val_r['id_cart']);
            $check = 0;
            if (!empty($array_id_product)) {
                foreach ($array_id_product as $val_id) {
                    $product = new ApmarketplaceProduct();
                    $product = $product->getProductVendor($id_vendor, $val_id['id_product']);
                    if (!empty($product)) {
                        $check = 1;
                        break;
                    }
                }
            }
            if ($check == 0) {
                unset($result[$key_r]);
            } else {
                if ($key == 'expenses') {
                    $flat_fees = Configuration::get('CONF_ORDER_FIXED') + (
                        $val_r['id_currency'] == Configuration::get('PS_CURRENCY_DEFAULT')
                        ? Configuration::get('CONF_'.Tools::strtoupper($val_r['module']).'_FIXED')
                        : Configuration::get('CONF_'.Tools::strtoupper($val_r['module']).'_FIXED_FOREIGN')
                    );
                    $var_fees = $val_r['total_paid_tax_incl'] * (
                        $val_r['id_currency'] == Configuration::get('PS_CURRENCY_DEFAULT')
                        ? Configuration::get('CONF_'.Tools::strtoupper($val_r['module']).'_VAR')
                        : Configuration::get('CONF_'.Tools::strtoupper($val_r['module']).'_VAR_FOREIGN')
                    ) / 100;
                    $shipping_fees = $val_r['total_shipping_tax_excl'] * (
                        $val_r['id_country'] == Configuration::get('PS_COUNTRY_DEFAULT')
                        ? Configuration::get('CONF_'.Tools::strtoupper($val_r['carrier_reference']).'_SHIP')
                        : Configuration::get('CONF_'.Tools::strtoupper($val_r['carrier_reference']).'_SHIP_OVERSEAS')
                    ) / 100;
                    $data[strtotime($val_r['date'])] += $flat_fees + $var_fees + $shipping_fees;
                } else {
                    $data[strtotime($val_r['date'])] = $val_r[$key];
                }
            }
        }
        return $data;
    }

    public static function getIdProductCart($id_cart)
    {
        $sql = 'SELECT `id_product` FROM `'._DB_PREFIX_.'cart_product` WHERE `id_cart` = ' . (int)$id_cart;
        return Db::getInstance()->executeS($sql);
    }

    public static function updateProductCategories($id_product, $categories)
    {
        Db::getInstance()->execute('
            DELETE FROM `'._DB_PREFIX_.'category_product` WHERE `id_product` =
        ' . (int)$id_product);
        $position = 1;
        $product_cats = array();
        foreach ($categories as $new_id_categ) {
            $product_cats[] = array(
                'id_category' => (int)$new_id_categ,
                'id_product' => $id_product,
                'position' => $position,
            );
            $position = $position + 1;
        }
        Db::getInstance()->insert('category_product', $product_cats);
    }

    public static function searchProducts($search)
    {
        $products = Product::searchByName(Context::getContext()->language->id, $search);
        $id_vendor = Context::getContext()->cookie->cookie_vendor;
        $array_p = new ApmarketplaceProduct();
        $array_p = $array_p->getProductByIdVendor($id_vendor);

        if (!empty($array_p)) {
            if (!empty($products)) {
                foreach ($products as $key_p => $product) {
                    $combinations = array();
                    $productObj = new Product(
                        (int)$product['id_product'],
                        false,
                        (int)Context::getContext()->language->id
                    );
                    $attributes = $productObj->getAttributesGroups((int)Context::getContext()->language->id);
                    $product['formatted_price'] = Tools::displayPrice(
                        Tools::convertPrice($product['price_tax_incl'], Context::getContext()->currency),
                        Context::getContext()->currency
                    );

                    foreach ($attributes as $attribute) {
                        if (!isset($combinations[$attribute['id_product_attribute']]['attributes'])) {
                            $combinations[$attribute['id_product_attribute']]['attributes'] = '';
                        }
                        $combinations[$attribute['id_product_attribute']]['attributes'] .=
                        $attribute['attribute_name'].' - ';
                        $combinations[$attribute['id_product_attribute']]['id_product_attribute'] =
                        $attribute['id_product_attribute'];
                        $combinations[$attribute['id_product_attribute']]['default_on'] =
                        $attribute['default_on'];
                        if (!isset($combinations[$attribute['id_product_attribute']]['price'])) {
                            $price_tax_incl = Product::getPriceStatic(
                                (int)$product['id_product'],
                                true,
                                $attribute['id_product_attribute']
                            );
                            $combinations[$attribute['id_product_attribute']]['formatted_price'] = Tools::displayPrice(
                                Tools::convertPrice($price_tax_incl, Context::getContext()->currency),
                                Context::getContext()->currency
                            );
                        }
                    }

                    foreach ($combinations as &$combination) {
                        $combination['attributes'] = rtrim($combination['attributes'], ' - ');
                    }
                    $products[$key_p]['combinations'] = $combinations;
                    $check = 0;
                    foreach ($array_p as $val_p) {
                        if ($val_p['id_product'] == $product['id_product']) {
                            $check = 1;
                            break;
                        }
                    }
                    if ($check == 0) {
                        unset($products[$key_p]);
                    }
                }
                if (!empty($products)) {
                    return array(
                        'products' => $products,
                        'found' => 1
                    );
                } else {
                    return array('found' => 2);
                }
            } else {
                return array('found' => 2);
            }
        } else {
            return array('found' => 2);
        }
    }

    public function addImageProduct($id_product, $files, $position)
    {
        $product = new Product($id_product);
        $upload = new UploaderCore();
        $file = $upload->upload($files, null);

        $image = new Image();
        $image->id_product = (int)($product->id);
        $image->position = $position;
        if ($position == 1) {
            $image->cover = 1;
        } else {
            $image->cover = 0;
        }
        
        if (($validate = $image->validateFieldsLang(false, true)) !== true) {
            $file['error'] = $validate;
        }

        if (isset($file['error']) && (!is_numeric($file['error']) || $file['error'] != 0)) {
            return;
        }

        if (!$image->add()) {
            $file['error'] = $this->l('Error while creating additional image');
        } else {
            if (!$new_path = $image->getPathForCreation()) {
                $file['error'] = $this->l('An error occurred while attempting to create a new folder.');
                return;
            }

            $error = 0;

            if (!ImageManager::resize(
                $file['save_path'],
                $new_path.'.'.$image->image_format,
                null,
                null,
                'jpg',
                false,
                $error
            )) {
                switch ($error) {
                    case ImageManager::ERROR_FILE_NOT_EXIST:
                        $file['error'] = $this->l(
                            'An error occurred while copying image, the file does not exist anymore.'
                        );
                        break;

                    case ImageManager::ERROR_FILE_WIDTH:
                        $file['error'] = $this->l('An error occurred while copying image, the file width is 0px.');
                        break;

                    case ImageManager::ERROR_MEMORY_LIMIT:
                        $file['error'] = $this->l('An error occurred while copying image');
                        break;

                    default:
                        $file['error'] = $this->l('An error occurred while copying the image.');
                        break;
                }
                return;
            } else {
                $imagesTypes = ImageType::getImagesTypes('products');
                $generate_hight_dpi_images = (bool)Configuration::get('PS_HIGHT_DPI');

                foreach ($imagesTypes as $imageType) {
                    if (!ImageManager::resize(
                        $file['save_path'],
                        $new_path.'-'.Tools::stripslashes($imageType['name']).'.'.$image->image_format,
                        $imageType['width'],
                        $imageType['height'],
                        $image->image_format
                    )) {
                        $file['error'] = $this->l('An error occurred while copying this image:')
                        .' '.Tools::stripslashes($imageType['name']);
                        return;
                    }

                    if ($generate_hight_dpi_images) {
                        if (!ImageManager::resize(
                            $file['save_path'],
                            $new_path.'-'.Tools::stripslashes($imageType['name']).'2x.'.$image->image_format,
                            (int)$imageType['width']*2,
                            (int)$imageType['height']*2,
                            $image->image_format
                        )) {
                            $file['error'] = $this->l(
                                'An error occurred while copying this image:'
                            )
                            .' '.Tools::stripslashes($imageType['name']);
                            return;
                        }
                    }
                }
            }

            unlink($file['save_path']);
                //Necesary to prevent hacking
            unset($file['save_path']);
            Hook::exec('actionWatermark', array('id_image' => $image->id, 'id_product' => $product->id));

            if (!$image->update()) {
                $file['error'] = $this->l('Error while updating the status.');
                return;
            }

                // Associate image to shop from context
            $shops = Shop::getContextListShopID();
            $image->associateTo($shops);
            $json_shops = array();

            foreach ($shops as $id_shop) {
                $json_shops[$id_shop] = true;
            }

            $file['status']   = 'ok';
            $file['id']       = $image->id;
            $file['position'] = $image->position;
            $file['cover']    = $image->cover;
            $file['legend']   = $image->legend;
            $file['path']     = $image->getExistingImgPath();
            $file['shops']    = $json_shops;

            @unlink(_PS_TMP_IMG_DIR_.'product_'.(int)$id_product.'.jpg');
            @unlink(_PS_TMP_IMG_DIR_.'product_mini_'.$id_product.'_'.Context::getContext()->shop->id.'.jpg');
        }
    }
}
