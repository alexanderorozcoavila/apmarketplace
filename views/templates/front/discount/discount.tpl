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
		<div class="align-items-start clearfix">
			<div class="add-new right-align">
				<a id="desc-cart_rule-new" class="list-toolbar-btn btn btn-primary" href="{$baseurl}discountadd">
					<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Add new' mod='apmarketplace'}" data-html="true" data-placement="top">
						<i class="material-icons">add</i>
						<span class="text-icon-inline">{l s='Add new' mod='apmarketplace'}</span>
					</span>
				</a>
			</div>
			<div class="count left-align">
				{l s='Cart Rules' mod='apmarketplace'}
				<span class="badge">{$count}</span>
			</div>
		</div>
		<div class="table-responsive leo-table clearfix">
			<table id="table-cart_rule" class="table cart_rule">
				<thead>
					<tr class="nodrag nodrop">
						<th class="column-headers">
							<span class="title_box active">
								{l s='ID' mod='apmarketplace'}
							</span>
						</th>
						<th>
							<span class="title_box">
								{l s='Name' mod='apmarketplace'}
							</span>
						</th>
						<th class="fixed-width-xs center">
							<span class="title_box">
								{l s='Priority' mod='apmarketplace'}
							</span>
						</th>
						<th class="fixed-width-sm">
							<span class="title_box">
								{l s='Code' mod='apmarketplace'}
							</span>
						</th>
						<th class="fixed-width-xs center">
							<span class="title_box">
								{l s='Quantity' mod='apmarketplace'}
							</span>
						</th>
						<th class="fixed-width-lg">
							<span class="title_box">
								{l s='Expiration date' mod='apmarketplace'}
							</span>
						</th>
						<th class="fixed-width-xs center">
							<span class="title_box">
								{l s='Status' mod='apmarketplace'}	
							</span>
						</th>
						<th class="fixed-width-xs center">
							<span class="title_box">
								{l s='Action' mod='apmarketplace'}	
							</span>
						</th>
					</tr>

					<tr class="column-filters">
						<th class="center">
							<input type="text" class="form-control" name="cart_ruleFilter_id_cart_rule" value="{if isset($id_cart_rule)}{$id_cart_rule}{/if}">
						</th>
						<th>
							<input type="text" class="form-control" name="cart_ruleFilter_name" value="{if isset($name)}{$name}{/if}">
						</th>
						<th class="center">
							<input type="text" class="form-control" name="cart_ruleFilter_priority" value="{if isset($priority)}{$priority}{/if}">
						</th>
						<th>
							<input type="text" class="form-control" name="cart_ruleFilter_code" value="{if isset($code)}{$code}{/if}">
						</th>
						<th class="center">
							<input type="text" class="form-control" name="cart_ruleFilter_quantity" value="{if isset($quantity)}{$quantity}{/if}">
						</th>
						<th class="center">
							{l s='--' mod='apmarketplace'}	
						</th>
						<th class="center">
							<select class="custom-select" name="cart_ruleFilter_active">
								<option value="">
									{l s='-' mod='apmarketplace'}
								</option>
								<option value="1" {if isset($active) && $active == 1}selected="selected"{/if}>
									{l s='Yes' mod='apmarketplace'}
								</option>
								<option value="0" {if isset($active) && $active == 0}selected="selected"{/if}>
									{l s='No' mod='apmarketplace'}
								</option>
							</select>
						</th>
						<th class="text-xs-right">
							<button class="btn btn-primary btn-search" type="submit" name="products_filter_submit">
								<i class="material-icons">search</i>
								<span class="text-icon-inline">{l s='Search' mod='apmarketplace'}</span>
							</button>
							<a href="{$baseurl}discountlist" class="btn btn-link">
								<i class="material-icons">clear</i>
								<span class="text-icon-inline">{l s='Reset' mod='apmarketplace'}</span>	
							</a>
						</th>
					</tr>
				</thead>
				<tbody>
					{if !empty($discounts)}
						{foreach from=$discounts item=discount}
							<tr>
								<td>
									{$discount.id_cart_rule}
								</td>
								<td>
									{$discount.name}
								</td>
								<td>
									{$discount.priority}
								</td>
								<td>
									{$discount.code}
								</td>
								<td>
									{$discount.quantity}
								</td>
								<td>
									{dateFormat date=$discount.date_to full=true}
								</td>
								<td>
									{if $discount.active == 0}
										<i class="material-icons">close</i>
									{/if}

									{if $discount.active == 1}
										<i class="material-icons">check</i>
									{/if}
								</td>
								<td class="text-xs-right">
									<a class="tooltip-link product-edit" href="{$baseurl}discountedit?id={$discount.id_cart_rule}">
										<i class="material-icons">mode_edit</i>
									</a>
									<a data-id={$discount.id_cart_rule} data-alert="{l s='You can Not Delete This Product !!' mod='apmarketplace'}" data-text="{l s='Delete Now' mod='apmarketplace'}" class="tooltip-link discount-delete" href="#">
										<i class="material-icons">delete</i>
									</a>
								</td>	
							</tr>
						{/foreach}
					{/if}
				</tbody>
			</table>
		</div>
	</div>							
{/block}
