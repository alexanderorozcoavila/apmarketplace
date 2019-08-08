{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
{extends file="module:apmarketplace/views/templates/front/layout/layout-full-width.tpl"}

{block name='right-column'}
	<div class="orders-catalog">
		<div class="content container-fluid">
			<div class="row align-items-start">
				<div class="col-md-12">
					{if empty($orders)}
						<h2>{l s='You Can Not View This Order' mod='apmarketplace'}</h2>
					{else}
						<div class="kpi-container">
							<div class="row">
								<div class="col-md-3 col-sm-6 col-xs-12 box-stats color3">
									<div class="kpi-content">
										<span class="icon text-warning"><i class="material-icons">date_range</i></span>
										<span class="title  text-dark">{l s='Date' mod='apmarketplace'}</span>
										<span class="value text-warning">{dateFormat date=$orders.date_add full=false}</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6 col-xs-12 box-stats color4">
									<div class="kpi-content">
										<span class="icon">
											<i class="material-icons text-success">attach_money</i>
										</span>
										<span class="title  text-dark">{l s='Total' mod='apmarketplace'}</span>
										<span class="value text-success">{$orders.total_paid_tax_incl}</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6 col-xs-12 box-stats color2">
									<div class="kpi-content">
										<i class="material-icons text-danger">message</i>
										<span class="title  text-dark">{l s='Messages' mod='apmarketplace'}</span>
										<span class="value text-danger">{$customer_thread_message}</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6 col-xs-12 box-stats color1">
									<a href="#start_products">
										<div class="kpi-content">
											<i class="material-icons text-primary">book</i>
											<span class="title">
												{l s='Products' mod='apmarketplace'}
											</span>
											<span class="value text-primary">{sizeof($products)}</span>
										</div>
									</a>
								</div>
							</div>
						</div>
						<div class="row small-grid">
							<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
								<div class="leo-panel">
									<div class="panel-heading">
										<i class="material-icons">credit_card</i>
										<span class="title">
											{l s='Orders' mod='apmarketplace'}
										</span>
										<span class="badge">{$orders.reference}</span>
										<span class="badge">{$orders.id_order}</span>
									</div>
									<div class="panel-body">
										<ul class="nav nav-tabs" id="tabOrder">
											<li class="nav-item">
												<a class="active nav-link" href="#status" data-toggle="tab">
													<i class="material-icons">access_time</i>
													<span class="text-icon-inline">{l s='Status' mod='apmarketplace'}</span>
													<span class="badge">{$history|@count}</span>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="#documents" data-toggle="tab">
													<i class="material-icons">library_books</i>
													<span class="text-icon-inline">{l s='Documents' mod='apmarketplace'}</span>
													<span class="badge">{$order_documents}</span>
												</a>
											</li>
										</ul>
										<div class="tab-content panel">
											<div class="tab-pane active" id="status">
												{include file="module:apmarketplace/views/templates/front/order/history.tpl"}
											</div>
											<div class="tab-pane" id="documents">
												{include file="module:apmarketplace/views/templates/front/order/document.tpl"}
											</div>
										</div>
										<hr>
										<ul class="nav nav-tabs" id="myTab">
											<li class="nav-item">
												<a class="active nav-link" href="#shipping" data-toggle="tab">
													<i class="material-icons">local_shipping</i>
													<span class="text-icon-inline">{l s='Shipping' mod='apmarketplace'}</span>
													<span class="badge">{$order_shipping}</span>
												</a>
											</li>
											<li  class="nav-item">
												<a class="nav-link" href="#returns" data-toggle="tab">	
													<i class="material-icons">undo</i>
													<span class="text-icon-inline">
														{l s='Merchandise Returns' mod='apmarketplace'}
													</span>
													<span class="badge">{$order_return|@count}</span>
												</a>
											</li>
										</ul>
										<div class="tab-content panel">
											<!-- Tab shipping -->
											<div class="tab-pane active" id="shipping">
												{if !$order->isVirtual()}
												<div class="form-horizontal">
													{if $order->gift_message}
														<div class="form-group">
															<label class="control-label col-lg-3">{l s='Message' mod='apmarketplace'}</label>
															<div class="col-lg-9">
																<p class="form-control-static">{$order->gift_message|nl2br}</p>
															</div>
														</div>
													{/if}
													{include file='module:apmarketplace/views/templates/front/order/shipping.tpl'}
													<hr />
													{if $order->recyclable}
														<span class="label leo-label-status label-success">
															<i class="material-icons">done</i>
															<span>{l s='Recycled packaging' mod='apmarketplace'}</span>
														</span>
													{else}
														<span class="label leo-label-status label-inactive">
															<i class="material-icons">close</i>
															<span>{l s='Recycled packaging' mod='apmarketplace'}</span>
														</span>
													{/if}

													{if $order->gift}
														<span class="label leo-label-status label-success">
															<i class="material-icons">done</i>
															<span>{l s='Gift wrapping' mod='apmarketplace'}</span>
														</span>
													{else}
														<span class="label leo-label-status label-inactive">
															<i class="material-icons">close</i>
															<span>{l s='Gift wrapping' mod='apmarketplace'}</span>
														</span>
													{/if}
												</div>
												{/if}
											</div>
											<div class="tab-pane" id="returns">
												{if !$order->isVirtual() && $order_return|count > 0}
													<div class="table-responsive">
														<table class="table">
															<thead>
																<tr>
																	<th>
																		<span class="title_box ">
																			{l s='Date' mod='apmarketplace'}
																		</span>
																	</th>
																	<th>
																		<span class="title_box ">
																			{l s='Type' mod='apmarketplace'}
																		</span>
																	</th>
																	<th>
																		<span class="title_box ">
																			{l s='Carrier' mod='apmarketplace'}
																		</span>
																	</th>
																	<th>
																		<span class="title_box ">
																			{l s='Tracking number' mod='apmarketplace'}
																		</span>
																	</th>
																</tr>
															</thead>
															<tbody>
																{foreach from=$order_returns item=line}
																	<tr>
																		<td>{$line.date_add}</td>
																		<td>{$line.type}</td>
																		<td>{$line.state_name}</td>
																		<td class="actions">
																			<span class="shipping_number_show">{if isset($line.url) && isset($line.tracking_number)}<a href="{$line.url|replace:'@':$line.tracking_number|escape:'html':'UTF-8'}">{$line.tracking_number}</a>{elseif isset($line.tracking_number)}{$line.tracking_number}{/if}</span>
																			{if $line.can_edit}
																			<form method="post" action="{$link->getAdminLink('AdminOrders')|escape:'html':'UTF-8'}&amp;vieworder&amp;id_order={$order->id|intval}&amp;id_order_invoice={if $line.id_order_invoice}{$line.id_order_invoice|intval}{else}0{/if}&amp;id_carrier={if $line.id_carrier}{$line.id_carrier|escape:'html':'UTF-8'}{else}0{/if}">
																				<span class="shipping_number_edit" style="display:none;">
																					<button type="button" name="tracking_number">
																						{$line.tracking_number|htmlentities}
																					</button>
																					<button type="submit" class="btn btn-default" name="submitShippingNumber">
																						{l s='Update' d='Admin.Actions'}
																					</button>
																				</span>
																				<button href="#" class="edit_shipping_number_link">
																					<i class="icon-pencil"></i>
																					{l s='Edit' d='Admin.Actions'}
																				</button>
																				<button href="#" class="cancel_shipping_number_link" style="display: none;">
																					<i class="icon-remove"></i>
																					{l s='Cancel' d='Admin.Actions'}
																				</button>
																			</form>
																			{/if}
																		</td>
																	</tr>
																{/foreach}
															</tbody>
														</table>
													</div>
												{/if}
											</div>
										</div>
									</div>
								</div>
								<div id="formAddPaymentPanel" class="leo-panel">
									<div class="panel-heading">
										<i class="material-icons">attach_money</i>
										<span class="text-icon-inline">{l s="Payment" mod='apmarketplace'}</span>
									</div>
									<div class="table-responsive leo-table">
										<table class="table">
											<thead>
												<tr>
													<th>
														<span class="title_box ">
															{l s='Date' mod='apmarketplace'}
														</span>
													</th>
													<th>
														<span class="title_box ">
															{l s='Payment method' mod='apmarketplace'}
														</span>
													</th>
													<th>
														<span class="title_box ">
															{l s='Transaction ID' mod='apmarketplace'}
														</span>
													</th>
													<th>
														<span class="title_box ">
															{l s='Amount' mod='apmarketplace'}
														</span>
													</th>
													<th>
														<span class="title_box ">
															{l s='Amount After Commission' mod='apmarketplace'}
														</span>
													</th>
													<th>
														<span class="title_box ">
															{l s='Invoice' mod='apmarketplace'}
														</span>
													</th>
												</tr>
											</thead>
											<tbody>
												{foreach from=$order->getOrderPaymentCollection() item=payment}
												<tr>
													<td>{dateFormat date=$payment->date_add full=true}</td>
													<td>{$payment->payment_method|escape:'html':'UTF-8'}</td>
													<td>{$payment->transaction_id|escape:'html':'UTF-8'}</td>
													<td>
														{Tools::displayPrice($payment->amount)}
													</td>
													<td>
														{Tools::displayPrice(($payment->amount * (100 - $percent))/100) }
													</td>
													<td>
														{if $invoice = $payment->getOrderInvoice($order->id)}
														{$invoice->getInvoiceNumberFormatted($current_id_lang, $order->id_shop)}
														{else}
														{/if}
													</td>
													<td class="actions">
														<button class="btn btn-default open_payment_information">
															<i class="material-icons">search</i>
															{l s='Details' mod='apmarketplace'}
														</button>
													</td>
												</tr>
												<tr class="payment_information" style="display: none;">
													<td colspan="5">
														<p>
															<b>{l s='Card Number' mod='apmarketplace'}</b>&nbsp;
															{if $payment->card_number}
															{$payment->card_number}
															{else}
															<i>{l s='Not defined' mod='apmarketplace'}</i>
															{/if}
														</p>
														<p>
															<b>{l s='Card Brand' mod='apmarketplace'}</b>&nbsp;
															{if $payment->card_brand}
															{$payment->card_brand}
															{else}
															<i>{l s='Not defined' mod='apmarketplace'}</i>
															{/if}
														</p>
														<p>
															<b>{l s='Card Expiration' d='Admin.Orderscustomers.Feature'}</b>&nbsp;
															{if $payment->card_expiration}
															{$payment->card_expiration}
															{else}
															<i>{l s='Not defined' mod='apmarketplace'}</i>
															{/if}
														</p>
														<p>
															<b>{l s='Card Holder' mod='apmarketplace'}</b>&nbsp;
															{if $payment->card_holder}
															{$payment->card_holder}
															{else}
															<i>{l s='Not defined' mod='apmarketplace'}</i>
															{/if}
														</p>
													</td>
												</tr>
												{foreachelse}
													<tr>
														<td class="list-empty hidden-print" colspan="6">
															<div class="list-empty-msg">
																<i class="icon-warning-sign list-empty-icon"></i>
																{l s='No payment methods are available' mod='apmarketplace'}
															</div>
														</td>
													</tr>
												{/foreach}
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
								<div class="leo-panel">
									{if $leo_customer->id}
										<div class="panel-heading">
											<i class="material-icons">person</i>
											<span class="text-icon-inline">{l s='Customer' mod='apmarketplace'}</span>
											<span class="badge">
												{l s='#' mod='apmarketplace'}{$leo_customer->id}
											</span>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<dl class="leo-well list-detail">
													<dt>{l s='Email' mod='apmarketplace'}</dt>
													<dd>
														<a href="mailto:{$leo_customer->email}">
															<i class="material-icons">email</i>
															<span class="text-icon-inline">{$leo_customer->email}</span>
														</a>
													</dd>
													<dt>
														{l s='Account registered' mod='apmarketplace'}
													</dt>
													<dd class="text-muted">
														<i class="material-icons">date_range</i>
														<span class="text-icon-inline">{dateFormat date=$leo_customer->date_add full=true}</span>
													</dd>
													<dt>{l s='Valid orders placed' mod='apmarketplace'}</dt>
													<dd>
														<span class="badge badge-infor">
															{$leo_customerStats['nb_orders']|intval}
														</span>
													</dd>
													<dt>
													  	{l s='Total spent since registration' mod='apmarketplace'}
													</dt>
													<dd>
														<span class="badge badge-success">
															{if $leo_customerStats['total_orders'] != ''}
																{Tools::displayPrice($leo_customerStats['total_orders'])}
															{else}
																{Tools::displayPrice(0)}
															{/if}
														</span>
													</dd>
												</dl>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<ul class="nav nav-tabs" id="tabAddresses">
													<li class="nav-item">
														<a class="active nav-link" href="#addressShipping" data-toggle="tab">
															<i class="material-icons">local_shipping</i>
															{l s='Shipping address' mod='apmarketplace'}
														</a>
													</li>
													<li class="nav-item"> 
														<a class="nav-link" href="#addressInvoice" data-toggle="tab">
															<i class="material-icons">library_books</i>
															{l s='Invoice address' mod='apmarketplace'}
														</a>
													</li>
												</ul>
												<div class="tab-content panel">
													<div class="tab-pane active" id="addressShipping">
														{if !$order->isVirtual()}
															<form class="form-horizontal hidden-print" method="post" action="">
																<div class="input-group leo-input-group">
																	<div class="input-select">
																		<select name="id_address" class="form-control">
																			{foreach from=$customer_addresses item=address}
																			<option value="{$address['id_address']}"
																			{if $address['id_address'] == $order->id_address_delivery}
																			selected="selected"
																			{/if}>
																			{$address['alias']} -
																			{$address['address1']}
																			{$address['postcode']}
																			{$address['city']}
																			{if !empty($address['state'])}
																			{$address['state']}
																			{/if},
																			{$address['country']}
																		</option>
																		{/foreach}
																	</select>
																</div>
																<div class="input-button">
																	<button class="btn btn-default" type="submit" name="submitAddressShipping">
																		<i class="material-icons">autorenew</i> {l s='Change' mod='apmarketplace'}
																	</button>
																</div>
															</div>
															</form>

															<div class="leo-well">
																<div class="row">
																	<div class="col-sm-6">
																		{$addresses.delivery->firstname} {$addresses.delivery->lastname}
																		<br>
																		{$addresses.delivery->company}
																		<br>
																		{$addresses.delivery->address1}
																		{$addresses.delivery->address2}
																		<br>
																		{$addresses.delivery->city},
																		{$addresses.deliveryState->name} {$addresses.delivery->postcode}
																		<br>
																		{$addresses.delivery->country}
																		<br>
																		{$addresses.delivery->phone}
																	</div>
																	<div class="col-sm-6 hidden-print">
																		<div id="map-delivery-canvas" style="height: 190px"></div>
																	</div>
																</div>
															</div>
														{/if}
													</div>
													<div class="tab-pane" id="addressInvoice">
														<form class="form-horizontal hidden-print" method="post" action="">
															<div class="input-group leo-input-group">
																<div class="input-select">
																	<select name="id_address_invoice" class="form-control">
																		{foreach from=$customer_addresses item=address}
																			<option value="{$address['id_address']}"
																				{if $address['id_address'] == $order->id_address_invoice}
																					selected="selected"
																				{/if}>
																				{$address['alias']} -
																				{$address['address1']}
																				{$address['postcode']}
																				{$address['city']}
																				{if !empty($address['state'])}
																					{$address['state']}
																				{/if},
																				{$address['country']}
																			</option>
																		{/foreach}
																	</select>
																</div>
																<div class="input-button">
																	<button class="btn btn-default" type="submit" name="submitAddressInvoice">
																		<i class="material-icons">autorenew</i>
																		{l s='Change' mod='apmarketplace'}
																	</button>
																</div>
															</div>
														</form>
														<div class="leo-well">
															<div class="row">
																<div class="col-sm-6">
																	{$addresses.invoice->firstname} {$addresses.invoice->lastname}
																	<br>
																	{$addresses.invoice->company}
																	<br>
																	{$addresses.invoice->address1}
																	{$addresses.invoice->address2}
																	<br>
																	{$addresses.invoice->city},
																	{$addresses.invoiceState->name} {$addresses.invoice->postcode}
																	<br>
																	{$addresses.invoice->country}
																	<br>
																	{$addresses.invoice->phone}
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									{/if}
								</div>
							</div>
						</div>

						<div class="row" id="start_products">
							<div class="col-lg-12">
								<div class="leo-panel">
									<div class="panel-heading">
										<i class="material-icons">shopping_cart</i>
										<span class="text-icon-inline">{l s='Products' mod='apmarketplace'}</span>
									</div>
									<div class="table-responsive leo-table">
										<table class="table" id="orderProducts">
											<thead>
												<tr>
													<th></th>
													<th>
														<span class="title_box">
															{l s='Product' mod='apmarketplace'}
														</span>
													</th>
													<th>
														<span class="title_box">
															{l s='Price per unit' mod='apmarketplace'}
														</span>
													</th>
													<th class="text-center">
														<span class="title_box ">
															{l s='Qty' mod='apmarketplace'}
														</span>
													</th>
													{if ($order->hasBeenPaid())}
														<th class="text-center">
															<span class="title_box">
																{l s='Refunded' mod='apmarketplace'}
															</span>
														</th>
													{/if}
													{if ($order->hasBeenDelivered() || $order->hasProductReturned())}
													<th class="text-center">
														<span class="title_box ">
															{l s='Returned' mod='apmarketplace'}
														</span>
													</th>
													{/if}
													<th class="text-center">
														<span class="title_box ">
															{l s='Available quantity' mod='apmarketplace'}
														</span>
													</th>
													<th>
													<span class="title_box ">
														{l s='Total' mod='apmarketplace'}
													</span>
													</th>
												</tr>
											</thead>
											<tbody>
												{foreach from=$products item=product key=k}
													{include file='module:apmarketplace/views/templates/front/order/product_line.tpl'}
												{/foreach}
											</tbody>
										</table>
									</div>

									<div class="row">
										<div class="offset-lg-6 offset-md-5 col-lg-6 col-md-7 col-sm-12 col-xs-12">
											<div class="leo-panel">
												<div class="table-responsive leo-table">
													<table class="table">
														<tbody>
															<tr id='total_products'>
																<td class="text-right">
																	{l s='Products' mod='apmarketplace'}:
																</td>
																<td>{$order_product_price}</td>
															</tr>
															<tr id='total_discounts'>
																<td class="text-right">
																	{l s='Discounts' mod='apmarketplace'}:
																</td>
																<td>-{$order_discount_price}</td>
															</tr>
															<tr id='total_wrapping'>
																<td class="text-right">
																	{l s='Wrapping' mod='apmarketplace'}:
																</td>
																<td>{$order_wrapping_price}</td>
															</tr>
															<tr id='total_shipping'>
																<td class="text-right">
																	{l s='Shipping' mod='apmarketplace'}:
																</td>
																<td>{$order_shipping_price}</td>
															</tr>
															<tr id='total_taxes'>
																<td class="text-right">
																	{l s='Taxes' mod='apmarketplace'}:
																</td>
																<td>{$total_taxes}</td>
															</tr>
															<tr id='total_order'>
																<td class="text-right">
																	<strong>
																		{l s='Total' mod='apmarketplace'}:
																	</strong>
																</td>
																<td>{$total}</td>
															</tr>
															<tr id='total_commission'>
																<td class="text-right">
																	<strong>
																		{l s='Total After Commission' mod='apmarketplace'}:
																	</strong>
																</td>
																<td>{$total_commission}</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					{/if}
			</div>
		</div>
	</div>	
{/block}
