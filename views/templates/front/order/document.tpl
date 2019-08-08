{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
<div class="table-responsive leo-table">
	<table class="table" id="documents_table">
		<thead>
			<tr>
				<th>
					<span class="title_box ">{l s='Date' mod='apmarketplace'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Document' mod='apmarketplace'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Number' mod='apmarketplace'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Amount' mod='apmarketplace'}</span>
				</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$order->getDocuments() item=document}
				{if get_class($document) eq 'OrderInvoice'}
					{if isset($document->is_delivery)}
						<tr id="delivery_{$document->id}">
					{else}
						<tr id="invoice_{$document->id}">
					{/if}
				{elseif get_class($document) eq 'OrderSlip'}
					<tr id="orderslip_{$document->id}">
				{/if}
					<td>{dateFormat date=$document->date_add}</td>
					<td>
						{if get_class($document) eq 'OrderInvoice'}
							{if isset($document->is_delivery)}
								{l s='Delivery slip' mod='apmarketplace'}
							{else}
								{l s='Invoice' mod='apmarketplace'}
							{/if}
						{elseif get_class($document) eq 'OrderSlip'}
							{l s='Credit Slip' mod='apmarketplace'}
						{/if}
					</td>
					<td>
						{if get_class($document) eq 'OrderInvoice'}
							{if isset($document->is_delivery)}
								<a class="_blank" href="{$baseurl}orderedit?id={$order->id}&amp;submitAction=generateDeliverySlipPDF&amp;id_order_invoice={$document->id}">
							{else}
								<a class="_blank" href="{$baseurl}orderedit?id={$order->id}&amp;submitAction=generateInvoicePDF&amp;id_order_invoice={$document->id}">
						   {/if}
						{elseif get_class($document) eq 'OrderSlip'}
							<a class="_blank" href="{$baseurl}orderedit?id={$order->id}&amp;submitAction=generateOrderSlipPDF&amp;id_order_slip={$document->id}">
						{/if}
						{if get_class($document) eq 'OrderInvoice'}
							{if isset($document->is_delivery)}
								{Configuration::get('PS_DELIVERY_PREFIX', $current_id_lang, null, $order->id_shop)}{'%06d'|sprintf:$document->delivery_number}
							{else}
								{$document->getInvoiceNumberFormatted($current_id_lang, $order->id_shop)}
							{/if}
						{elseif get_class($document) eq 'OrderSlip'}
							{Configuration::get('PS_CREDIT_SLIP_PREFIX', $current_id_lang)}{'%06d'|sprintf:$document->id}
						{/if}
						</a>
					</td>
					<td>
						{if get_class($document) eq 'OrderInvoice'}
							{if isset($document->is_delivery)}
								--
							{else}
								{Tools::displayPrice($document->total_paid_tax_incl)}
								{if $document->getTotalPaid()}
									<span>
									{if $document->getRestPaid() > 0}
										({Tools::displayPrice($document->getRestPaid())} {l s='not paid' mod='apmarketplace'})
									{elseif $document->getRestPaid() < 0}
										({Tools::displayPrice(-$document->getRestPaid())} {l s='overpaid' mod='apmarketplace'})
									{/if}
									</span>
								{/if}
							{/if}
						{elseif get_class($document) eq 'OrderSlip'}
							{Tools::displayPrice($document->total_products_tax_incl+$document->total_shipping_tax_incl)}
						{/if}
					</td>
					<td class="text-right document_action">
						{if get_class($document) eq 'OrderInvoice'}
							{if !isset($document->is_delivery)}
								{if $document->getRestPaid()}
									<a href="#formAddPaymentPanel" class="js-set-payment btn btn-default anchor" data-amount="{$document->getRestPaid()}" data-id-invoice="{$document->id}" title="{l s='Set payment form' mod='apmarketplace'}">
										<i class="icon-money"></i>
										{l s='Enter payment' mod='apmarketplace'}
									</a>
								{/if}
							{/if}
						{/if}
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="5" class="list-empty">
						<div class="list-empty-msg">
							<i class="icon-warning-sign list-empty-icon"></i>
							{l s='There is no available document' mod='apmarketplace'}
						</div>
						{if isset($invoice_management_active) && $invoice_management_active}
							<a class="btn btn-default" href="">
								<i class="icon-repeat"></i>
								{l s='Generate invoice' mod='apmarketplace'}
							</a>
						{/if}
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</div>
