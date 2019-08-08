{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
{extends file=$layout}

{block name='content'}
	{if $check == 0}
		<p class="alert alert-success">{l s='Your account not active. Please contact for us to active account' mod='apmarketplace'}</p>
	{/if}
	{if $check == 1}
		<form id="product_catalog_list" name="product_catalog_list" method="GET" action="">
			<section id="wrapper">
				<div class="container">
					<div class="row">
						{include file="module:apmarketplace/views/templates/front/left.tpl"}
						<div id="content-wrapper" class="right-column col-xs-12 col-sm-8 col-md-9">
							<div class="row">
								{if !empty($orders)}
									<div class="panel col-lg-12">
										<h3>{l s='Orders waiting for pay' mod='apmarketplace'}</h3>
										<div class="table-responsive-row clearfix">
											<table class="table-apmarketplace_order">
												<thead>
													<tr class="nodrag nodrop">
														<td>{l s='ID' mod='apmarketplace'}</td>
														<td>{l s='ID Product' mod='apmarketplace'}</td>
														<td>{l s='ID Vendor' mod='apmarketplace'}</td>
														<td>{l s='Product Name' mod='apmarketplace'}</td>
														<td>{l s='First Name' mod='apmarketplace'}</td>
														<td>{l s='Last Name' mod='apmarketplace'}</td>
														<td>{l s='Email' mod='apmarketplace'}</td>
														<td>{l s='Total' mod='apmarketplace'}</td>
														<td>{l s='Total After Commission' mod='apmarketplace'}</td>
													</tr>
												</thead>
												<tbody>
													{foreach from=$orders item=order}
														<tr class="odd">
															<td>{$order.id_apmarketplace_order}</td>
															<td>{$order.id_product}</td>
															<td>{$order.id_vendor}</td>
															<td>{$order.name}</td>
															<td>{$order.first_name}</td>
															<td>{$order.last_name}</td>
															<td>{$order.email}</td>
															<td>{Tools::displayPrice($order.total_price_tax_incl)}</td>
															<td>{Tools::displayPrice($order.total)}</td>
														</tr>
													{/foreach}
												</tbody>
											</table>
										</div>
									</div>
								{/if}
								{if !empty($order_pays)}
									<div class="panel col-lg-12">
										<h3>{l s='Orders has been paid' mod='apmarketplace'}</h3>
										<div class="table-responsive-row clearfix">
											<table class="table-apmarketplace_order">
												<thead>
													<tr class="nodrag nodrop">
														<td>{l s='ID' mod='apmarketplace'}</td>
														<td>{l s='ID Product' mod='apmarketplace'}</td>
														<td>{l s='ID Vendor' mod='apmarketplace'}</td>
														<td>{l s='Product Name' mod='apmarketplace'}</td>
														<td>{l s='First Name' mod='apmarketplace'}</td>
														<td>{l s='Last Name' mod='apmarketplace'}</td>
														<td>{l s='Email' mod='apmarketplace'}</td>
														<td>{l s='Total' mod='apmarketplace'}</td>
														<td>{l s='Total After Commission' mod='apmarketplace'}</td>
													</tr>
												</thead>
												<tbody>
													{foreach from=$order_pays item=order_pay}
														<tr class="odd">
															<td>{$order_pays.id_apmarketplace_order}</td>
															<td>{$order_pays.id_product}</td>
															<td>{$order_pays.id_vendor}</td>
															<td>{$order_pays.name}</td>
															<td>{$order_pays.first_name}</td>
															<td>{$order_pays.last_name}</td>
															<td>{$order_pays.email}</td>
															<td>{Tools::displayPrice($order_pays.total_price_tax_incl)}</td>
															<td>{Tools::displayPrice($order_pays.total)}</td>
														</tr>
													{/foreach}
												</tbody>
											</table>
										</div>
									</div>
								{/if}
							</div>		
						</div>
					</div>
				</div>
			</section>
		</form>
	{/if}					
{/block}
