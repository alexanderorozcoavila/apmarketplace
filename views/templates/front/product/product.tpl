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
		<div class="products-catalog">
			<div class="align-items-start clearfix">
				<div class="right-align add_product">
					<a href="{$baseurl}productadd" class="btn btn-primary">
						<i class="material-icons">&#xE145;</i>
						{l s='New Product' mod='apmarketplace'}
					</a>
				</div>
				<div class="left-align product_catalog_category_tree_filter">
					{include file="module:apmarketplace/views/templates/front/category_tree.tpl"}
				</div>
			</div>
			<div class="table-responsive leo-table">
				<table class="table product">
					<thead class="with-filters">
						<tr class="column-headers">
							<th>{l s='Image' mod='apmarketplace'}</th>
							<th>{l s='Name' mod='apmarketplace'}</th>
							<th>{l s='Reference' mod='apmarketplace'}</th>
							<th>{l s='Category' mod='apmarketplace'}</th>
							<th>{l s='Price' mod='apmarketplace'}</th>
							<th>{l s='Quantity' mod='apmarketplace'}</th>
							<th>{l s='Status' mod='apmarketplace'}</th>
							<th>{l s='Actions' mod='apmarketplace'}</th>
						</tr>
						<tr class="column-filters">
							<th></th>
							<th>
								<input class="form-control" type="text" name="filter_column_name" placeholder="{l s='Search Name' mod='apmarketplace'}" value="{if isset($filter_column_name)}{$filter_column_name}{/if}">
							</th>
							<th>
								<input class="form-control" type="text" name="filter_column_reference" placeholder="{l s='Search Ref' mod='apmarketplace'}" value="{if isset($filter_column_reference)}{$filter_column_reference}{/if}">
							</th>
							<th>
							</th>
							<th class="text-xs-center">
								<input class="form-control form-min-max" type="text" name="filter_column_price_min" placeholder="{l s='Min' mod='apmarketplace'}" value="{if isset($filter_column_price_min)}{$filter_column_price_min}{/if}">
								<input class="form-control form-min-max" type="text" name="filter_column_price_max" placeholder="{l s='Max' mod='apmarketplace'}" value="{if isset($filter_column_price_max)}{$filter_column_price_max}{/if}">
							</th>
							<th class="text-xs-center">
								<input class="form-control form-min-max" type="text" name="filter_column_quantity_min" placeholder="{l s='Min' mod='apmarketplace'}" value="{if isset($filter_column_quantity_min)}{$filter_column_quantity_min}{/if}">
								<input class="form-control form-min-max" type="text" name="filter_column_quantity_max" placeholder="{l s='Max' mod='apmarketplace'}" value="{if isset($filter_column_quantity_max)}{$filter_column_quantity_max}{/if}">
							</th>
							<th class="text-xs-center">
								<select class="custom-select" name="filter_column_active">
									<option value=""></option>
									<option value="1" {if isset($filter_column_active) && $filter_column_active == 1}selected="selected"{/if}>{l s='Active' mod='apmarketplace'}</option>
									<option value="0" {if isset($filter_column_active) && $filter_column_active == 0}selected="selected"{/if}>{l s='Inactive' mod='apmarketplace'}</option>
								</select>
							</th>
							<th class="text-xs-right">
								<button class="btn btn-primary btn-search" type="submit" name="products_filter_submit">
									<i class="material-icons">&#xE8b6;</i>
									{l s='Search' mod='apmarketplace'}
								</button>
								<a href="{$baseurl}productlist" class="btn btn-link">
									<i class="material-icons">&#xE14c;</i>
									{l s='Reset' mod='apmarketplace'}
								</a>
							</th>
						</tr>
					</thead>
					<tbody>
						{if !empty($products)}
							{foreach from=$products item=product}
								<tr data-id-product="{$product.id_product}">
									<td>
										<a href="{$product.link}">
											<img class="imgm img-thumbnail" src="{$product.image_url}">
										</a>
									</td>
									<td>
										<a href="{$product.link}">{$product.name}</a>
									</td>
									<td>{$product.reference}</td>
									<td>{$product.category_default}</td>
									<td>{Tools::displayPrice($product.orderprice)}</td>
									<td>{$product.quantity_all_versions}</td>
									<td class="text-xs-center">
										{if $product.active == 1}
											<i class="material-icons text-success">&#xE876;</i>
										{else}
											<i class="material-icons text-danger">&#xE5cd;</i>
										{/if}
									</td>
									<td class="text-xs-center">
										<a class="tooltip-link btn-edit" title="{l s='Edit' mod='apmarketplace'}" href="{$baseurl}productedit?id={$product.id_product}">
											<i class="material-icons">&#xE254;</i>
										</a>
										<a class="tooltip-link btn-view" title="{l s='View' mod='apmarketplace'}" href="{$product.link}">
											<i class="material-icons">&#xE417;</i>
										</a>
										<a data-alert="{l s='You can Not Delete This Product !!' mod='apmarketplace'}" data-text="{l s='Delete Now' mod='apmarketplace'}" title="{l s='Delete' mod='apmarketplace'}" class="tooltip-link btn-delete" href="#">
											<i class="material-icons">&#xE872;</i>
										</a>
									</td>
								</tr>
							{/foreach}
						{/if}
					</tbody>
				</table>
			</div>
		</div>
	</form>				
{/block}
