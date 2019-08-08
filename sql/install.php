<?php
/**
* 2007-2018 PrestaShop
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
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'apmarketplace_vendor` (
    `id_apmarketplace_vendor` int(11) NOT NULL AUTO_INCREMENT,
    `user_name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `first_name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `last_name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
    `phone` int(11) NOT NULL,
    `fb` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `tt` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `fax` int(11) NOT NULL,
    `ins` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `url_shop` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `pass_word` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `date_add` date NOT NULL,
    `active` int(1) NOT NULL,
    PRIMARY KEY  (`id_apmarketplace_vendor`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'apmarketplace_vendor_shop` (
    `id_apmarketplace_vendor` int(11) NOT NULL,
    `id_shop` int(11) NOT NULL,
    PRIMARY KEY  (`id_apmarketplace_vendor`, `id_shop`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'apmarketplace_product` (
    `id_apmarketplace_product` int(11) NOT NULL AUTO_INCREMENT,
    `id_product` int(11) NOT NULL,
    `id_apmarketplace_vendor` int(11) NOT NULL,
    PRIMARY KEY  (`id_apmarketplace_product`, `id_product`, `id_apmarketplace_vendor`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'apmarketplace_order` (
    `id_apmarketplace_order` int(11) NOT NULL AUTO_INCREMENT,
    `id_order` int(11) NOT NULL,
    `id_product` int(11) NOT NULL,
    `id_vendor` int(11) NOT NULL,
    `payment` tinyint(1) NOT NULL,
    PRIMARY KEY  (`id_apmarketplace_order`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'apmarketplace_answer` (
    `id_apmarketplace_answer` int(11) NOT NULL AUTO_INCREMENT,
    `id_customer` int(11) NOT NULL,
    `id_apmarketplace_question` int(11) NOT NULL,
    `answer` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `date_ans` datetime NOT NULL,
    `active_ans` tinyint(1) NOT NULL,
    PRIMARY KEY  (`id_apmarketplace_answer`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'apmarketplace_question` (
    `id_apmarketplace_question` int(11) NOT NULL AUTO_INCREMENT,
    `id_product` int(11) NOT NULL,
    `id_customer` int(11) NOT NULL,
    `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `date_ques` datetime NOT NULL,
    `active_ques` tinyint(1) NOT NULL,
    PRIMARY KEY  (`id_apmarketplace_question`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'apmarketplace_review` (
    `id_review` int(11) NOT NULL AUTO_INCREMENT,
    `id_product` int(11) NOT NULL,
    `id_customer` int(11) NOT NULL,
    `id_guest` int(11) NOT NULL,
    `title` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `customer_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `grade` float unsigned NOT NULL,
    `validate` tinyint(1) NOT NULL,
    `deleted` tinyint(1) NOT NULL,
    `date_add` datetime NOT NULL,
    PRIMARY KEY  (`id_review`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
