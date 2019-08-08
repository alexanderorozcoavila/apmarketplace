{*
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
*}

{if !empty($vendor)}
	<label class="leo_vendor">{$vendor->last_name} {$vendor->first_name}</label>

	<!-- <label class="leo_vendor">{l s='Vendors' mod='apmarketplace'} : {$vendor->last_name} {$vendor->first_name}</label> -->
	<!-- <a href="{$baseurl}store?id={$vendor->id_apmarketplace_vendor}">
		<img style="width:20px;height:20px;border-radius:50%;" src="{$baseurl}modules/apmarketplace/views/img/vendor/{$vendor->image}" title="{$vendor->last_name} {$vendor->first_name}" alt="{$vendor->last_name} {$vendor->first_name}">
	</a> -->
	<!-- <label class="leo_vendor">{l s='Phone' mod='apmarketplace'} : {$vendor->phone}</label> -->

{else}
	<label class="leo_vendor">{l s='Vendors' mod='apmarketplace'} : {l s='Admin' mod='apmarketplace'}</label>
{/if}
