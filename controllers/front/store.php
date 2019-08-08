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

use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Filter\CollectionFilter;
use PrestaShop\PrestaShop\Core\Filter\HashMapWhitelistFilter;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\Pagination;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchResult;
use PrestaShop\PrestaShop\Core\Product\Search\Facet;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchProviderInterface;
use PrestaShop\PrestaShop\Core\Product\Search\FacetsRendererInterface;

class ApmarketplacestoreModuleFrontController extends ModuleFrontController
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
        if (Tools::getIsset('controller') && Tools::getValue('controller') == 'store') {
            $baseurl = $this->context->shop->getBaseURL(true, true);
            $this->addCSS($baseurl.'modules/apmarketplace/views/css/front.css');
            $results = new ApmarketplaceProduct();
            $page = '';
            $total = $results->getProductByVendor(Tools::getValue('id'), $page);
            if (Tools::getIsset('page')) {
                $page = Tools::getValue('page');
            } else {
                $page = 1;
            }
            $results = $results->getProductByVendor(Tools::getValue('id'), $page);
            $id_products = '';
            if (!empty($results)) {
                foreach ($results as $row) {
                    $id_products .= ', ' . $row['id_product'];
                }
                $show_all = count($results);
            }
            $id_products = ltrim($id_products, ',');
            if ($id_products != '') {
                $where = 'WHERE  p.id_product IN  (' . pSQL($id_products) . ')';
                $products = $this->getProducts(
                    $where,
                    (int) Context::getContext()->language->id,
                    1,
                    $show_all,
                    null,
                    null
                );
                $products = $this->loadProductDetail($products);

                $query = new ProductSearchQuery();
                $query->setResultsPerPage(Configuration::get('PS_PRODUCTS_PER_PAGE'));

                $pagination = $this->getTemplateVarPagination(
                    $query,
                    $total
                );
                $listing = array(
                    'products' => $products,
                    'sort_orders' => array(),
                    'sort_selected' => '',
                    'pagination' => $pagination,
                    'rendered_facets' => '',
                    'rendered_active_filters' => '',
                    'js_enabled' => false,
                );
            } else {
                $products = array();
                $listing = array(
                    'products' => $products,
                    'sort_orders' => array(),
                    'sort_selected' => '',
                    'pagination' => array(),
                    'rendered_facets' => '',
                    'rendered_active_filters' => '',
                    'js_enabled' => false,
                );
            }
        } else {
            $products = array();
            $listing = array(
                'products' => $products,
                'sort_orders' => array(),
                'sort_selected' => '',
                'pagination' => array(),
                'rendered_facets' => '',
                'rendered_active_filters' => '',
                'js_enabled' => false,
            );
        }
        $obj = new ApmarketplaceVendors(Tools::getValue('id'));
        $vars = array(
            'baseurl' => $this->context->shop->getBaseURL(true, true),
            'vendor' => $obj,
            'products' => $products,
            'search_products' => $products,
            'listing' => $listing,
            'total_products' => count($products),
        );

        $this->context->smarty->assign($vars);
        $this->setTemplate('module:apmarketplace/views/templates/front/vendor_store.tpl');
        parent::initContent();
    }

    public function getTemplateVarPagination($query, $result)
    {
        $pagination = new Pagination();
        $pagination
            ->setPage($query->getPage())
            ->setPagesCount(
                ceil(count($result) / $query->getResultsPerPage())
            )
        ;

        $totalItems = count($result);
        $itemsShownFrom = ($query->getResultsPerPage() * ($query->getPage() - 1)) + 1;
        $itemsShownTo = $query->getResultsPerPage() * $query->getPage();
        return array(
            'total_items' => $totalItems,
            'items_shown_from' => $itemsShownFrom,
            'items_shown_to' => ($itemsShownTo <= $totalItems) ? $itemsShownTo : $totalItems,
            'pages' => array_map(function ($link) {
                $link['url'] = $this->updateQueryString(array(
                    'page' => $link['page'],
                ));

                return $link;
            }, $pagination->buildLinks()),
            // Compare to 3 because there are the next and previous links
            'should_be_displayed' => (count($pagination->buildLinks()) > 3)
        );
    }

    public function getProducts(
        $where,
        $id_lang,
        $p,
        $n,
        $order_by = null,
        $order_way = null,
        $get_total = false,
        $active = true,
        $random = false,
        $random_number_products = 1,
        $check_access = true,
        Context $context = null
    ) {
        # validate module
        unset($check_access);
        if (!$context) {
            $context = Context::getContext();
        }

        $front = true;
        if (!in_array($context->controller->controller_type, array('front', 'modulefront'))) {
            $front = false;
        }

        if ($p < 1) {
            $p = 1;
        }
        if (empty($order_by)) {
            $order_by = 'position';
        } else {
            /* Fix for all modules which are now using lowercase values for 'orderBy' parameter */
            $order_by = Tools::strtolower($order_by);
        }

        if (empty($order_way)) {
            $order_way = 'ASC';
        }
        if ($order_by == 'id_product' || $order_by == 'date_add' || $order_by == 'date_upd') {
            $order_by_prefix = 'p';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'pl';
        } elseif ($order_by == 'manufacturer') {
            $order_by_prefix = 'm';
            $order_by = 'name';
        } elseif ($order_by == 'position') {
            $order_by_prefix = 'cp';
        }

        if ($order_by == 'price') {
            $order_by = 'orderprice';
        }

        if (!Validate::isBool($active) || !Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)) {
            die(Tools::displayError());
        }

        $id_supplier = (int) Tools::getValue('id_supplier');

        /* Return only the number of products */
        if ($get_total) {
            $sql = 'SELECT COUNT(cp.`id_product`) AS total
                FROM `' . _DB_PREFIX_ . 'product` p
                ' . Shop::addSqlAssociation('product', 'p') . '
                LEFT JOIN `' . _DB_PREFIX_ . 'category_product` cp ON p.`id_product` = cp.`id_product`
                ' . $where . '
                ' . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') .
                    ($active ? ' AND product_shop.`active` = 1' : '') .
                    ($id_supplier ? 'AND p.id_supplier = ' . (int) $id_supplier : '');
            return (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
        }

        $sql = 'SELECT DISTINCT p.id_product, p.*, product_shop.*, stock.out_of_stock,
        IFNULL(stock.quantity, 0) as quantity, product_attribute_shop.`id_product_attribute`,
        product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity,
        pl.`description`, pl.`description_short`, pl.`available_now`,
        pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`,
        pl.`meta_keywords`, pl.`meta_title`, pl.`name`, image_shop.`id_image`,
        il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
        DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
        INTERVAL ' .
        (Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ?
            Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20) . '
                    DAY)) > 0 AS new, product_shop.price AS orderprice
            FROM `' . _DB_PREFIX_ . 'category_product` cp
            LEFT JOIN `' . _DB_PREFIX_ . 'product` p
                ON p.`id_product` = cp.`id_product`
            ' . Shop::addSqlAssociation('product', 'p') . '
            LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute` pa
            ON (p.`id_product` = pa.`id_product`)
            ' . Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1') . '
            ' . Product::sqlStock('p', 'product_attribute_shop', false, $context->shop) . '
            LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl
                ON (product_shop.`id_category_default` = cl.`id_category`
                AND cl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('cl') . ')
            LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl
                ON (p.`id_product` = pl.`id_product`
                AND pl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('pl') . ')
            LEFT JOIN `' . _DB_PREFIX_ . 'image` i
                ON (i.`id_product` = p.`id_product`)' .
                Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1') . '
            LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il
                ON (image_shop.`id_image` = il.`id_image`
                AND il.`id_lang` = ' . (int) $id_lang . ')
            LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m
                ON m.`id_manufacturer` = p.`id_manufacturer`
            ' . $where . '
            AND  product_shop.`id_shop` = ' . (int) $context->shop->id . '
            AND (pa.id_product_attribute IS NULL OR product_attribute_shop.id_shop=' . (int) $context->shop->id . ')
            AND (i.id_image IS NULL OR image_shop.id_shop=' . (int) $context->shop->id . ')
                ' . ($active ? ' AND product_shop.`active` = 1' : '')
                . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
                . ($id_supplier ? ' AND p.id_supplier = ' . (int) $id_supplier : '');
        if ($random === true) {
            $sql .= ' ORDER BY RAND()';
            $sql .= ' LIMIT 0, ' . (int) $random_number_products;
        } else {
            $sql .= ' ORDER BY ' . (isset($order_by_prefix) ? $order_by_prefix . '.' : '') .
            '`' . pSQL($order_by) . '` ' . pSQL($order_way) . '
                    LIMIT ' . (((int) $p - 1) * (int) $n) . ',' . (int) $n;
        }
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if ($order_by == 'orderprice') {
            Tools::orderbyPrice($result, $order_way);
        }

        if (!$result) {
            return array();
        }

        /* Modify SQL result */
        return Product::getProductsProperties($id_lang, $result);
    }


    public function loadProductDetail($products)
    {
        #1.7
        $assembler = new ProductAssembler(Context::getContext());

        $presenterFactory = new ProductPresenterFactory(Context::getContext());
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                Context::getContext()->link
            ),
            Context::getContext()->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            Context::getContext()->getTranslator()
        );


        $products_for_template = array();
        foreach ($products as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                Context::getContext()->language
            );
        }
        return $products_for_template;
    }
}
