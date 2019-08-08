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

class ApmarketplaceAnswer extends ObjectModel
{
    public $id_apmarketplace_answer;
    public $id_apmarketplace_question;
    public $id_customer;
    public $answer;
    public $date_ans;
    public $active_ans;

    public static $definition = array(
        'table' => 'apmarketplace_answer',
        'primary' => 'id_apmarketplace_answer',
        'multilang' => false,
        'multilang_shop' => false,
        'fields' => array(
            'id_apmarketplace_question' =>                  array('type' => self::TYPE_INT),
            'id_customer' =>                                array('type' => self::TYPE_INT),
            'answer' =>                                     array('type' => self::TYPE_STRING),
            'date_ans' =>                                   array('type' => self::TYPE_DATE),
            'active_ans' =>                                 array('type' => self::TYPE_BOOL),
        ),
    );

    public function add($autodate = true, $null_values = false)
    {
        return parent::add($autodate, $null_values);
    }

    public function getAnswerByIDQuestion($id_apmarketplace_question)
    {
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'apmarketplace_answer`
        WHERE `id_apmarketplace_question` = ' .  (int)$id_apmarketplace_question;
        $resuilt = Db::getInstance()->executeS($sql);
        if (!empty($resuilt)) {
            foreach ($resuilt as $key_r => $val_r) {
                if ($val_r['id_customer'] == 0) {
                    $resuilt[$key_r]['customer'] = $this->trans('admin');
                } else {
                    $customer = new Customer((int)$val_r['id_customer']);
                    $resuilt[$key_r]['customer'] = $customer->firstname. ' ' .$customer->lastname;
                }
            }
        }
        return $resuilt;
    }

    public function validate($id_apmarketplace_question)
    {
        $success = (Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'apmarketplace_answer` SET
            `active_ans` = 1
            WHERE `id_apmarketplace_question` = '.(int)$id_apmarketplace_question)
        );
        return $success;
    }
}
