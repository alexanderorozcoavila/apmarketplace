{**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<div class="table-responsive leo-table">
	<table class="table" id="shipping_table">
		<thead>
			<tr>
				<th>
					<span class="title_box ">{l s='Date' mod='apmarketplace'}</span>
				</th>
				<th>
					<span class="title_box ">&nbsp;</span>
				</th>
				<th>
					<span class="title_box ">{l s='Carrier' mod='apmarketplace'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Weight' mod='apmarketplace'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Shipping cost' mod='apmarketplace'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Tracking number' mod='apmarketplace'}</span>
				</th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$order->getShipping() item=line}
			<tr>
				<td>{dateFormat date=$line.date_add full=true}</td>
				<td>&nbsp;</td>
				<td>{$line.carrier_name}</td>
				<td class="weight">{$line.weight|string_format:"%.3f"} {Configuration::get('PS_WEIGHT_UNIT')}</td>
				<td class="price_carrier_{$line.id_carrier|intval}" class="center">
					<span>
					{if $order->getTaxCalculationMethod() == $smarty.const.PS_TAX_INC}
						{Tools::displayPrice($line.shipping_cost_tax_inc)}
					{else}
						{Tools::displayPrice($line.shipping_cost_tax_excl)}
					{/if}
					</span>
				</td>
				<td>
					<span class="shipping_number_show">{if $line.url && $line.tracking_number}<a class="_blank" href="{$line.url|replace:'@':$line.tracking_number}">{$line.tracking_number}</a>{else}{$line.tracking_number}{/if}</span>
				</td>
			</tr>
			{/foreach}
		</tbody>
	</table>

	<!-- shipping update modal -->
	<div class="modal fade" id="modal-shipping">
		<div class="modal-dialog">
			<form method="post" action="{$link->getAdminLink('AdminOrders')|escape:'html':'UTF-8'}&amp;vieworder&amp;id_order={$order->id|intval}">
				<input type="hidden" name="submitShippingNumber" id="submitShippingNumber" value="1" />
				<input type="hidden" name="id_order_carrier" id="id_order_carrier" />
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="{l s='Close' d='Admin.Actions'}"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">{l s='Edit shipping details' mod='apmarketplace'}</h4>
					</div>
					<div class="modal-body">
						<div class="container-fluid">
							{if !$recalculate_shipping_cost}
							<div class="alert alert-info">
							{l s='Please note that carrier change will not recalculate your shipping costs, if you want to change this please visit Shop Parameters > Order Settings' mod='apmarketplace'}
							</div>
							{/if}
							<div class="form-group">
								<div class="col-lg-5">{l s='Tracking number' mod='apmarketplace'}</div>
								<div class="col-lg-7"><input type="text" name="shipping_tracking_number" id="shipping_tracking_number" /></div>
							</div>
							<div class="form-group">
								<div class="col-lg-5">{l s='Carrier' mod='apmarketplace'}</div>
								<div class="col-lg-7">
									<select name="shipping_carrier" id="shipping_carrier">
										{foreach from=$carrier_list item=carrier}
											<option value="{$carrier.id_carrier|intval}">{$carrier.name|escape:'html':'UTF-8'} {if isset($carrier.delay)}({$carrier.delay|escape:'html':'UTF-8'}){/if}</option>
										{/foreach}
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">{l s='Cancel' mod='apmarketplace'}</button>
						<button type="submit" class="btn btn-primary">{l s='Update' mod='apmarketplace'}</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- END shipping update modal -->
</div>
