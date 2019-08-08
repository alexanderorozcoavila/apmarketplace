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

if (!defined('_PS_VERSION_')) {
    # module validation
    exit;
}

class ApmarketplaceReview extends ObjectModel
{
    public $id;

    /** @var integer Product's id */
    public $id_product;

    /** @var integer Customer's id */
    public $id_customer;

    /** @var integer Guest's id */
    public $id_guest;

    /** @var integer Customer name */
    public $customer_name;

    /** @var string Title */
    public $title;

    /** @var string Content */
    public $content;

    /** @var integer Grade */
    public $grade;

    /** @var boolean Validate */
    public $validate = 0;

    public $deleted = 0;

    /** @var string Object creation date */
    public $date_add;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'apmarketplace_review',
        'primary' => 'id_review',
        'fields' => array(
            'id_product' =>     array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_customer' =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_guest' =>       array('type' => self::TYPE_INT),
            'customer_name' =>  array('type' => self::TYPE_STRING),
            'title' =>          array('type' => self::TYPE_STRING),
            'content' =>        array('type' => self::TYPE_STRING,
                                    'validate' => 'isMessage',
                                    'size' => 65535,
                                    'required' => true
                                ),
            'grade' =>          array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'validate' =>       array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'deleted' =>        array('type' => self::TYPE_BOOL),
            'date_add' =>       array('type' => self::TYPE_DATE),
        )
    );

    /**
     * Get reviews by IdProduct
     *
     * @return array Reviews
     */
    public static function getByProduct($id_product, $p = 1, $n = null, $id_customer = null)
    {
        if (!Validate::isUnsignedId($id_product)) {
            return false;
        }
        $validate = 1;
        $p = (int)$p;
        $n = (int)$n;
        if ($p <= 1) {
            $p = 1;
        }
        if ($n != null && $n <= 0) {
            $n = 5;
        }

        $cache_id = 'ApmarketplaceReview::getByProduct_'.
                    (int)$id_product.'-'.(int)$p.'-'.(int)$n.
                    '-'.(int)$id_customer.'-'.(bool)$validate;
        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT pc.`id_review`, pc.`customer_name`, pc.`content`, pc.`grade`, pc.`date_add`, pc.`title`
            FROM `'._DB_PREFIX_.'apmarketplace_review` pc
            WHERE pc.`id_product` = '.(int)($id_product).($validate == '1' ? ' AND pc.`validate` = 1' : '').'
            ORDER BY pc.`date_add` DESC
            '.($n ? 'LIMIT '.(int)(($p - 1) * $n).', '.(int)($n) : ''));
            Cache::store($cache_id, $result);
        }
        return Cache::retrieve($cache_id);
    }

    /**
     * Return customer's review
     *
     * @return arrayReviews
     */
    public static function getByCustomer($id_product, $id_customer, $get_last = false, $id_guest = false)
    {
        $cache_id = 'ProductReview::getByCustomer_'.(int)$id_product.'-'.
                    (int)$id_customer.'-'.(bool)$get_last.'-'.(int)$id_guest;
        if (!Cache::isStored($cache_id)) {
            $results = Db::getInstance()->executeS('SELECT *
                FROM `'._DB_PREFIX_.'apmarketplace_review` pc
                WHERE pc.`id_product` = '.(int)$id_product.'
                AND '.(!$id_guest ? 'pc.`id_customer` = '.(int)$id_customer : 'pc.`id_guest` = '.(int)$id_guest).'
                ORDER BY pc.`date_add` DESC '
                .($get_last ? 'LIMIT 1' : ''));

            if ($get_last && count($results)) {
                $results = array_shift($results);
            }

            Cache::store($cache_id, $results);
        }
        return Cache::retrieve($cache_id);
    }

    public static function getAverageGrade($id_product)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
        SELECT (SUM(pc.`grade`) / COUNT(pc.`grade`)) AS grade
        FROM `'._DB_PREFIX_.'apmarketplace_review` pc
        WHERE pc.`id_product` = '.(int)$id_product.'
        AND pc.`deleted` = 0 AND pc.`validate` = 1');
    }

    /**
     * Return number of reviews and average grade by products
     *
     * @return array Info
     */
    public static function getReviewNumber($id_product)
    {
        $result = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
            SELECT COUNT(`id_review`) AS "nbr"
            FROM `'._DB_PREFIX_.'apmarketplace_review` pc
            WHERE `id_product` = '.(int)($id_product). ' AND `validate` = 1');
        return $result;
    }

    /**
     * Get reviews by Validation
     *
     * @return array Reviews
     */
    public static function getByValidate($validate = '0', $deleted = false)
    {
        $sql  = '
            SELECT  pc.`id_review`,
                    pc.`id_product`,
                    if (c.id_customer, CONCAT(c.`firstname`, \' \',  c.`lastname`), pc.customer_name) customer_name,
                    pc.`title`,
                    pc.`content`,
                    pc.`grade`,
                    pc.`date_add`,
                    pl.`name`
            FROM `'._DB_PREFIX_.'apmarketplace_review` pc
            LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = pc.`id_customer`)
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
            ON (pl.`id_product` = pc.`id_product`
            AND pl.`id_lang` = '.(int)Context::getContext()->language->id.Shop::addSqlRestrictionOnLang('pl').')
            WHERE pc.`validate` = '.(int)$validate;

        $sql .= ' ORDER BY pc.`date_add` DESC';
        
        // validate module
        unset($deleted);

        return (Db::getInstance()->executeS($sql));
    }

    /**
     * Validate a comment
     *
     * @return boolean succeed
     */
    public function validate($validate = '1')
    {
        if (!Validate::isUnsignedId($this->id)) {
            return false;
        }

        $success = (Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'apmarketplace_review` SET
            `validate` = '.(int)$validate.'
            WHERE `id_review` = '.(int)$this->id));

        Hook::exec('actionObjectProductReviewValidateAfter', array('object' => $this));
        return $success;
    }

    /**
     * Delete a comment, grade and report data
     *
     * @return boolean succeed
     */
    public function delete()
    {
        parent::delete();
    }
}
