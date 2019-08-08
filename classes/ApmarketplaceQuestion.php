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

class ApmarketplaceQuestion extends ObjectModel
{
    public $id_apmarketplace_question;
    public $id_product;
    public $id_customer;
    public $content;
    public $date_ques;
    public $active_ques;

    public static $definition = array(
        'table' => 'apmarketplace_question',
        'primary' => 'id_apmarketplace_question',
        'multilang' => false,
        'multilang_shop' => false,
        'fields' => array(
            'id_product' =>                  array('type' => self::TYPE_INT),
            'id_customer' =>                 array('type' => self::TYPE_INT),
            'content' =>                     array('type' => self::TYPE_STRING),
            'date_ques' =>                   array('type' => self::TYPE_DATE),
            'active_ques' =>                 array('type' => self::TYPE_BOOL),
        ),
    );

    public function add($autodate = true, $null_values = false)
    {
        return parent::add($autodate, $null_values);
    }

    public function getByIDQuestion($id_apmarketplace_question)
    {
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'apmarketplace_question`
        WHERE `id_apmarketplace_question` = ' . $id_apmarketplace_question;
        $resuilt = Db::getInstance()->executeS($sql);
        if (!empty($resuilt)) {
            foreach ($resuilt as $key_r => $val_r) {
                $customer = new Customer((int)$val_r['id_customer']);
                $resuilt[$key_r]['customer'] = $customer->firstname. ' ' .$customer->lastname;
            }
        }
        return $resuilt;
    }

    public function getByIDProduct($id_product)
    {
        $sql = 'SELECT *
                FROM `'._DB_PREFIX_.'apmarketplace_question`
                WHERE `id_product` = ' . (int)$id_product;
        return  Db::getInstance()->executeS($sql);
    }

    public function getByValidate($validate)
    {
        if ($validate == 0) {
            $and = 'OR';
        } else {
            $and = 'AND';
        }
        $sql = 'SELECT DISTINCT q.*,CONCAT(LEFT(c.`firstname`, 1), c.`lastname`) AS `customer`, p_l.`name`
        FROM `'._DB_PREFIX_.'apmarketplace_question` q
        LEFT JOIN `'._DB_PREFIX_.'apmarketplace_answer` a
        ON q.`id_apmarketplace_question` = a.`id_apmarketplace_question`
        LEFT JOIN `'._DB_PREFIX_.'customer` c
        ON q.`id_customer` = c.`id_customer`
        LEFT JOIN `'._DB_PREFIX_.'product_lang` p_l
        ON q.`id_product` = p_l.`id_product`
        WHERE `active_ques` = '.(int)$validate.' '.$and.' `active_ans` = '.(int)$validate.'
        AND p_l.`id_lang` = ' . (int) Context::getContext()->language->id;
        return Db::getInstance()->executeS($sql);
    }

    public function validate()
    {
        $success = (Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'apmarketplace_question` SET
            `active_ques` = 1
            WHERE `id_apmarketplace_question` = '.(int)$this->id_apmarketplace_question)
        );
        return $success;
    }
}
