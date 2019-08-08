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

{extends file="helpers/form/form.tpl"}
{block name="field"}
	{if $input.type == 'leo_vendor'}
		<div class="row leo_vendor">
			<div class="col-md-4">
				<div class="leo_product">
					<h3 class="title-product">{l s='Product' mod='apmarketplace'}</h3>
					<ul class="leo_content">
						<li>{l s='Total Product' mod='apmarketplace'} : {$input.array_vendor.product}</li>
						<li>{l s='Items Sold' mod='apmarketplace'} : {$input.array_vendor.item_sold}</li>
					</ul>
				</div>
			</div>

			<div class="col-md-4">
				<div class="leo_revenue">
					<h3 class="title-revenue">{l s='Revenue' mod='apmarketplace'}</h3>
					<ul class="leo_content">
						<li>{l s='Orders Processed' mod='apmarketplace'} : {$input.array_vendor.total_order}</li>
					</ul>
				</div>
			</div>

			<div class="col-md-4">
				<div class="leo_other">
					<h3 class="title-revenue">{l s='Other' mod='apmarketplace'}</h3>
					<ul class="leo_content">
						<li>{l s='Reviews' mod='apmarketplace'} : {$input.array_vendor.review}</li>
					</ul>
				</div>
			</div>
		</div>
		<hr>
	{else}
		{$smarty.block.parent}	
	{/if}
{/block}