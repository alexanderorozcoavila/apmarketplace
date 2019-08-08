{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
{extends file="module:apmarketplace/views/templates/front/layout/layout-full-width.tpl"}

{block name='right-column'}
	<form id="product_catalog_list" name="product_catalog_list" method="GET" action="">
		<div class="orders-catalog">
			<div class="row align-items-start">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="table-responsive table-product">
						<table class="table leo-table">
							<thead class="with-filters">
								<tr class="column-headers">
									<th>{l s='ID' mod='apmarketplace'}</th>
									<th>{l s='Reference' mod='apmarketplace'}</th>
									<th>{l s='New client' mod='apmarketplace'}</th>
									<th>{l s='Delivery' mod='apmarketplace'}</th>
									<th>{l s='Customer' mod='apmarketplace'}</th>
									<th>{l s='Total' mod='apmarketplace'}</th>
									<th>{l s='Total After Commission' mod='apmarketplace'}</th>
									<th>{l s='Payment' mod='apmarketplace'}</th>
									<th>{l s='Status' mod='apmarketplace'}</th>
									<th>{l s='Action' mod='apmarketplace'}</th>
								</tr>
								<tr class="column-filters">
									<th>
										<input style="width:100px;" class="form-control" type="text" name="orderFilter_id_order" value="{if isset($id_order)}{$id_order}{/if}">
									</th>
									<th>
										<input class="form-control" type="text" name="orderFilter_reference" value="{if isset($reference)}{$reference}{/if}">
									</th>
									<th class="text-xs-center">
										<select class="custom-select" name="orderFilter_new">
											<option value="">{l s='---' mod='apmarketplace'}</option>
											<option {if isset($new) && $new == 1}selected="selected"{/if} value="1">{l s='Yes' mod='apmarketplace'}</option>
											<option {if isset($new) && $new == 0}selected="selected"{/if} value="0">{l s='No' mod='apmarketplace'}</option>
										</select>
									</th>
									<th class="text-xs-center">
										<select class="custom-select" name="orderFilter_country">
											<option value="">{l s='---' mod='apmarketplace'}</option>
											{if !empty($delivery)}
												{foreach from=$delivery item=deliveri}
													<option {if isset($country) && $country == $deliveri.id_country}selected="selected"{/if} value="{$deliveri.id_country}">{$deliveri.name}</option>
												{/foreach}
											{/if}
										</select>
									</th>
									<th>
										<input class="form-control" type="text" name="orderFilter_customer" value="{if isset($cus)}{$cus}{/if}">
									</th>
									<th class="text-xs-right">
										<input style="width:100px;" class="form-control" type="text" name="orderFilter_total_paid_tax_incl" value="{if isset($total)}{$total}{/if}">
									</th>
									<th></th>
									<th>
										<input class="form-control" type="text" name="orderFilter_payment"
										value="{if isset($payment)}{$payment}{/if}"
										>
									</th>
									<th class="text-xs-center">
										<select class="custom-select" name="orderFilter_os">
											<option value="">{l s='---' mod='apmarketplace'}</option>
											{if !empty($statuses)}
												{foreach from=$statuses item=status}
													<option {if isset($address_delivery) && $address_delivery == $status.id_order_state}selected="selected"{/if} value="{$status.id_order_state}">{$status.name}</option>
												{/foreach}
											{/if}
										</select>
									</th>
									<th class="text-xs-right">
										<button class="btn btn-primary btn-search" type="submit" name="products_filter_submit">
											<i class="material-icons">&#xE8b6;</i>
											{l s='Search' mod='apmarketplace'}
										</button>
										<a href="{$baseurl}orderslist" class="btn btn-link">
											<i class="material-icons">&#xE14c;</i>
											{l s='Reset' mod='apmarketplace'}
										</a>
									</th>
								</tr>
							</thead>
							<tbody>
								{if !empty($orders)}
									{foreach from=$orders item=order}
										<tr class="items_order">
											<td>{$order.id_order}</td>
											<td>{$order.reference}</td>
											<td>
												{if $order.new == 1}
													<i class="material-icons text-success">&#xE876;</i>
												{else}
													<i class="material-icons text-danger">&#xE5cd;</i>
												{/if}
											</td>
											<td>{$order.cname}</td>
											<td>{$order.customer}</td>
											<td>{$order.total_paid_tax_incl}</td>
											<td>{$order.after}</td>
											<td>{$order.payment}</td>
											<td>{$order.osname}</td>
											<td class="text-xs-center">
												<a href="{$baseurl}orderedit?id={$order.id_order}" title="{l s='View' mod='apmarketplace'}" class="btn-view">
													<i class="material-icons">&#xE417;</i><span>{l s='View' mod='apmarketplace'}</span>
												</a>
											</td>
										</tr>
									{/foreach}
								{/if}		
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>		
	</form>					
{/block}
