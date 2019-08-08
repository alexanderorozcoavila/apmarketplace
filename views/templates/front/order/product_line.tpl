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

{* Assign product price *}
{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
	{assign var=product_price value=($product['unit_price_tax_excl'] + $product['ecotax'])}
{else}
	{assign var=product_price value=$product['unit_price_tax_incl']}
{/if}
{if ($product['product_quantity'] > $product['customized_product_quantity'])}
	<tr class="product-line-row">
		<td>{if isset($product.image) && $product.image->id}{$product.image_tag nofilter}{/if}</td>
		<td>
			<span class="productName">{$product['product_name']}</span><br/>
			{if $product.product_reference}{l s='Reference number:' d='Admin.Orderscustomers.Feature'} {$product.product_reference}<br />{/if}
			{if $product.product_supplier_reference}{l s='Supplier reference:' d='Admin.Orderscustomers.Feature'} {$product.product_supplier_reference}{/if}
		</td>
		<td>
			<span class="product_price_show">{Tools::displayPrice($product_price)}</span>
		</td>
		<td class="productQuantity text-center">
			<span class="product_quantity_show{if (int)$product['product_quantity'] - (int)$product['customized_product_quantity'] > 1} badge{/if}">
				{(int)$product['product_quantity'] - (int)$product['customized_product_quantity']}
			</span>
		</td>
		{if ($order->hasBeenPaid())}
			<td class="productQuantity text-center">
				{if !empty($product['amount_refund'])}
					{l s='%quantity_refunded% (%amount_refunded% refund)' sprintf=['%quantity_refunded%' => $product['product_quantity_refunded'], '%amount_refunded%' => $product['amount_refund']] mod='apmarketplace'}
				{/if}
				<input type="hidden" value="{$product['quantity_refundable']}" class="partialRefundProductQuantity" />
				<input type="hidden" value="{(Tools::ps_round($product_price, 2) * ($product['product_quantity'] - $product['customizationQuantityTotal']))}" class="partialRefundProductAmount" />
				{if count($product['refund_history'])}
					<span class="tooltip">
						<span class="tooltip_label tooltip_button">+</span>
						<span class="tooltip_content">
						<span class="title">{l s='Refund history' mod='apmarketplace'}</span>
						{foreach $product['refund_history'] as $refund}
							{l s='%refund_date% - %refund_amount%' sprintf=['%refund_date%' => {dateFormat date=$refund.date_add}, '%refund_amount%' => {Tools::displayPrice($refund.amount_tax_incl)}] mod='apmarketplace'}<br />
						{/foreach}
						</span>
					</span>
				{/if}
			</td>
		{/if}
		{if $order->hasBeenDelivered() || $order->hasProductReturned()}
			<td class="productQuantity text-center">
				{$product['product_quantity_return']}
				{if count($product['return_history'])}
					<span class="tooltip">
						<span class="tooltip_label tooltip_button">+</span>
						<span class="tooltip_content">
						<span class="title">{l s='Return history' mod='apmarketplace'}</span>
						{foreach $product['return_history'] as $return}
							{l s='%return_date% - %return_quantity% - %return_state%' sprintf=['%return_date%' =>{dateFormat date=$return.date_add}, '%return_quantity%' => $return.product_quantity, '3return_state%' => $return.state] mod='apmarketplace'}<br />
						{/foreach}
						</span>
					</span>
				{/if}
			</td>
		{/if}
		<td class="productQuantity product_stock text-center">{$product['current_stock']}</td>
		<td class="total_product">
			{Tools::displayPrice(Tools::ps_round($product_price, 2) * ($product['product_quantity'] - $product['customizationQuantityTotal'])) }
		</td>
	</tr>
{/if}
