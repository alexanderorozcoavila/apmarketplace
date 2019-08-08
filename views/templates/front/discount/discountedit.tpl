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
		<form id="form-cart_rule" name="form_cart_rule" method="GET" action="">
			<section id="wrapper">
				<div class="container">
					<div class="row">
						{include file="module:apmarketplace/views/templates/front/left.tpl"}
						<div id="content-wrapper" class="right-column col-xs-12 col-sm-8 col-md-9">
							<div class="row">
								<div class="col-lg-12">
									{if isset($permis) && $permis == 0}
										<h2>{l s='You Can Not View This Discount' mod='apmarketplace'}</h2>
									{else}
									<div class="orders-catalog">
										<div class="content container-fluid">
											<div class="row">
												{if isset($notification)}
													<p class="notification">{$notification nofilter}</p>
												{/if}
												<div class="col-md-12">
													<div class="panel">
														<i class="material-icons">create</i>
														<h3>{l s='Cart rule' mod='apmarketplace'}</h3>
													</div>
													<div class="productTabs">
														<ul class="tab nav nav-tabs">
															<li class="tab-row active">
																<a class="tab-page" id="cart_rule_link_informations" href="#cart_rule_informations">
																	<i class="material-icons">info</i>
																	{l s='Information' mod='apmarketplace'}		
																</a>
															</li>
															<li class="tab-row">
																<a class="tab-page" id="cart_rule_link_conditions" href="#cart_rule_conditions">
																	<i class="material-icons">shuffle</i>
																	{l s='Conditions' mod='apmarketplace'}
																</a>
															</li>
															<li class="tab-row">
																<a class="tab-page" id="cart_rule_link_actions" href="#cart_rule_actions">
																	<i class="material-icons">build</i>
																	{l s='Actions' mod='apmarketplace'}
																</a>
															</li>
														</ul>
													</div>
													<form action="" id="cart_rule_form" class="form-horizontal" method="POST">
														<div id="cart_rule_informations" class="panel cart_rule_tab">
															<div class="form-group row">
																<label class="control-label col-lg-3 required">
																	{l s='Name' mod='apmarketplace'}
																</label>
																<div class="col-lg-8">
																	<input class="form-control leo_name" required="" type="text" name="name" value="{$discounts->name}">
																</div>
															</div>

															<div class="form-group row">
																<label class="control-label col-lg-3 required">
																	{l s='Description' mod='apmarketplace'}
																</label>
																<div class="col-lg-8">
																	<textarea class="textarea-autosize form-control leo_des" name="description" required="">
																		{$discounts->description}
																	</textarea>
																</div>
															</div>
															<div class="form-group row">
																<label class="control-label col-lg-3 required">
																	{l s='Code' mod='apmarketplace'}
																</label>
																<div class="col-lg-8">
																	<div class="col-lg-12">
																		<div class="col-lg-10">
																			<input class="form-control leo_code" required="" type="text" name="code" value="{$discounts->code}">
																		</div>
																		<div class="col-lg-2">
																			<a href="#" class="btn btn-default gen_code">
																				<i class="material-icons">shuffle</i>
																				{l s='Generate' mod='apmarketplace'}
																			</a>
																		</div>
																	</div>
																	<span class="help-block">{l s='Caution! If you leave this field blank, the rule will automatically be applied to benefiting customers.' mod='apmarketplace'}</span>
																</div>
															</div>
															<div class="form-group row">
																<label class="control-label col-lg-3">
																	{l s='Highlight' mod='apmarketplace'}
																</label>
																<div class="col-lg-9">
																	<span class="switch prestashop-switch fixed-width-lg">
																		<input {if $discounts->highlight == 1}checked="checked"{/if} type="radio" name="highlight" id="highlight_on" value="1">
																		<label for="highlight_on">
																			{l s='Yes' mod='apmarketplace'}
																		</label>
																		<input {if $discounts->highlight == 0}checked="checked"{/if} type="radio" name="highlight" id="highlight_off" value="0">
																		<label for="highlight_off">
																			{l s='no' mod='apmarketplace'}
																		</label>
																	</span>
																</div>
															</div>

															<div class="form-group row">
																<label class="control-label col-lg-3">
																	{l s='Partial use' mod='apmarketplace'}
																</label>
																<div class="col-lg-9">
																	<span class="switch prestashop-switch fixed-width-lg">
																		<input type="radio" name="partial_use" id="partial_use_on" value="1" {if $discounts->partial_use == 1}checked="checked"{/if}>
																		<label class="t" for="partial_use_on">
																			{l s='Yes' mod='apmarketplace'}
																		</label>
																		<input type="radio" name="partial_use" id="partial_use_off" value="0" {if $discounts->partial_use == 0}checked="checked"{/if}>
																		<label class="t" for="partial_use_off">
																			{l s='No' mod='apmarketplace'}
																		</label>
																	</span>
																</div>
															</div>

															<div class="form-group row">
																<label class="control-label col-lg-3 required">
																	{l s='Priority' mod='apmarketplace'}
																</label>
																<div class="col-lg-8">
																	<input class="form-control leo_priority" required="" type="text" name="priority" value="{$discounts->priority}">
																</div>
															</div>

															<div class="form-group row">
																<label class="control-label col-lg-3">
																	{l s='Status' mod='apmarketplace'}
																</label>
																<div class="col-lg-9">
																	<span class="switch prestashop-switch fixed-width-lg">
																		<input {if $discounts->active == 1}checked="checked"{/if}  type="radio" name="active" id="active_on" value="1">
																		<label class="t" for="active_on">
																			{l s='Yes' mod='apmarketplace'}
																		</label>
																		<input {if $discounts->active == 0}checked="checked"{/if}  type="radio" name="active" id="active_off" value="0">
																		<label class="t" for="active_off">
																			{l s='No' mod='apmarketplace'}
																		</label>
																	</span>
																</div>
															</div>
														</div>
														<div id="cart_rule_conditions" class="panel cart_rule_tab" style="display:none;">
															<div class="form-group row">
																<label class="control-label col-lg-3">
																	{l s='Limit to a single customer' mod='apmarketplace'}
																</label>
																<div class="col-lg-9">
																	<div class="input-group col-lg-12">
																		<span class="input-group-addon">
																			<i class="material-icons">person</i>
																		</span>
																		<input type="text" id="customerFilter" class="form-control leo_customer" required="" name="customerFilter" value="" autocomplete="off">
																		<span class="input-group-addon">
																			<i class="material-icons">search</i>
																		</span>
																	</div>
																</div>
															</div>

															<div class="form-group row">
																<label class="control-label col-lg-3">
																	{l s='Valid' mod='apmarketplace'}
																</label>
																<div class="col-lg-9">
																	<div class="row">
																		<div class="col-lg-6">
																			<div class="input-group col-lg-12">
																				<span class="input-group-addon">
																					{l s='From' mod='apmarketplace'}
																				</span>
																				<input type="text" class="date_from datepicker form-control input-medium hasDatepicker" name="date_from" value="{$discounts->date_from}">
																				<span class="input-group-addon">
																					<i class="material-icons">calendar_today</i>
																				</span>
																			</div>
																			<div id="date_from" style="display:none;"></div>
																		</div>
																		<div class="col-lg-6">
																			<div class="input-group col-lg-12">
																				<span class="input-group-addon">
																					{l s='To' mod='apmarketplace'}
																				</span>
																				<input type="text" class="date_to datepicker form-control input-medium hasDatepicker" name="date_to" value="{$discounts->date_to}">
																				<span class="input-group-addon">
																					<i class="material-icons">calendar_today</i>
																				</span>
																			</div>
																			<div id="date_to" style="display:none;"></div>
																		</div>
																	</div>
																</div>
															</div>

															<div class="form-group row">
																<label class="control-label col-lg-3">
																	{l s='Minimum amount' mod='apmarketplace'}
																</label>
																<div class="row">
																	<div class="col-lg-3">
																		<input class="form-control" type="text" name="minimum_amount" value="{$discounts->minimum_amount}">
																	</div>
																	<div class="col-lg-2">
																		<select name="minimum_amount_currency" class="form-control">
																			{foreach from=$currencies item='currency'}
																				<option {if $discounts->minimum_amount_currency == $currency.id_currency}selected="selected"{/if} value="{$currency.id_currency}">
																					{$currency.iso_code}
																				</option>
																			{/foreach}	
																		</select>
																	</div>
																	<div class="col-lg-3">
																		<select name="minimum_amount_tax" class="form-control">
																			<option {if $discounts->minimum_amount_tax == 0}selected="selected"{/if} value="0">
																				{l s='Tax excluded' mod='apmarketplace'}
																			</option>
																			<option {if $discounts->minimum_amount_tax == 1}selected="selected"{/if} value="1">
																				{l s='Tax included' mod='apmarketplace'}
																			</option>
																		</select>
																	</div>
																	<div class="col-lg-4">
																		<select name="minimum_amount_shipping" class="form-control">
																			<option {if $discounts->minimum_amount_shipping == 0}selected="selected"{/if} value="0">
																				{l s='Shipping excluded' mod='apmarketplace'}
																			</option>
																			<option {if $discounts->minimum_amount_shipping == 1}selected="selected"{/if} value="1">
																				{l s='Shipping included' mod='apmarketplace'}
																			</option>
																		</select>
																	</div>
																</div>
															</div>
															<div class="form-group row">
																<label class="control-label col-lg-3">
																	{l s='Total available' mod='apmarketplace'}
																</label>
																<div class="col-lg-9">
																	<input class="form-control" type="text" name="quantity" value="{$discounts->quantity}">
																</div>
															</div>
															<div class="form-group row">
																<label class="control-label col-lg-3">
																	{l s='Total available for each user' mod='apmarketplace'}
																</label>
																<div class="col-lg-9">
																	<input class="form-control" type="text" name="quantity_per_user" value="{$discounts->quantity_per_user}">
																</div>
															</div>
														</div>
														<div id="cart_rule_actions" class="panel cart_rule_tab" style="display:none;">
															<div class="form-group row">
																<label class="control-label col-lg-3">
																	{l s='Free shipping' mod='apmarketplace'}
																</label>
																<div class="col-lg-9">
																	<span class="switch prestashop-switch fixed-width-lg">
																		<input {if $discounts->free_shipping == 1}checked="checked"{/if} type="radio" name="free_shipping" id="free_shipping_on" value="1">
																		<label class="t" for="free_shipping_on">
																			{l s='Yes shipping' mod='apmarketplace'}
																		</label>
																		<input {if $discounts->free_shipping == 1}checked="checked"{/if} type="radio" name="free_shipping" id="free_shipping_off" value="0" checked="checked">
																		<label class="t" for="free_shipping_off">
																			{l s='No shipping' mod='apmarketplace'}
																		</label>
																	</span>
																</div>
															</div>
															<div class="form-group row">
																<label class="control-label col-lg-3">
																	{l s='Apply a discount' mod='apmarketplace'}
																</label>
																<div class="col-lg-9">
																	<div class="radio">
																		<label for="apply_discount_percent">
																			<input {if $discounts->reduction_percent|floatval > 0}checked="checked"{/if} type="radio" name="apply_discount" id="apply_discount_percent" value="percent">
																			{l s='Percent (%)' mod='apmarketplace'}
																		</label>
																	</div>
																	<div class="radio">
																		<label for="apply_discount_amount">
																			<input {if $discounts->reduction_amount|floatval > 0}checked="checked"{/if} type="radio" name="apply_discount" id="apply_discount_amount" value="amount">
																			{l s='Amount' mod='apmarketplace'}
																		</label>
																	</div>
																	<div class="radio">
																		<label for="apply_discount_off">
																			<input {if $discounts->reduction_amount|floatval > 0 && $discounts->reduction_percent|floatval > 0}checked="checked"{/if} type="radio" checked="checked" name="apply_discount" id="apply_discount_off" value="off">
																			<i class="material-icons">close</i>
																			{l s='None' mod='apmarketplace'}
																		</label>
																	</div>
																</div>
															</div>
															<div id="apply_discount_percent_div" class="form-group row" style="display:none;">
																<label class="control-label col-lg-3">
																	{l s='Value' mod='apmarketplace'}
																</label>
																<div class="col-lg-9">
																	<div class="input-group col-lg-2">
																		<span class="input-group-addon">
																			{l s='%' mod='apmarketplace'}
																		</span>
																		<input type="text" id="reduction_percent" class="form-control" name="reduction_percent" value="{$discounts->reduction_percent|floatval}">
																	</div>
																	<span class="help-block">
																		<i class="icon-warning-sign"></i>
																		{l s='Does not apply to the shipping costs' mod='apmarketplace'}
																	</span>
																</div>
															</div>
															<div id="apply_discount_amount_div" class="form-group row" style="display:none;">
																<label class="control-label col-lg-3">
																	{l s='Amount' mod='apmarketplace'}
																</label>
																<div class="col-lg-9">
																	<div class="row">
																		<div class="col-lg-4">
																			<input class="form-control" type="text" id="reduction_amount" name="reduction_amount" value="{$discounts->reduction_amount|floatval}">
																		</div>
																		<div class="col-lg-4">
																			<select name="reduction_currency" class="form-control">
																				{foreach from=$currencies item='currency'}
																					<option {if $discounts->reduction_currency == $currency.id_currency || $discounts->reduction_currency}selected="selected"{/if} value="{$currency.id_currency}">
																						{$currency.iso_code}
																					</option>
																				{/foreach}
																			</select>
																		</div>
																		<div class="col-lg-4">
																			<select name="reduction_tax" class="form-control">
																				<option {if $discounts->reduction_tax == 0}selected="selected"{/if} value="0">
																					{l s='Tax excluded' mod='apmarketplace'}
																				</option>
																				<option {if $discounts->reduction_tax == 1}selected="selected"{/if} value="1">
																					{l s='Tax included' mod='apmarketplace'}
																				</option>
																			</select>
																		</div>
																	</div>
																</div>
															</div>
															<div id="apply_discount_to_div" class="form-group row" style="display:none;">
																<label class="control-label col-lg-3">
																	{l s='Apply a discount to' mod='apmarketplace'}
																</label>
																<div class="col-lg-9">
																	<div class="radio">
																		<label for="apply_discount_to_order">
																			<input {if $discounts->reduction_product|intval == 0} checked="checked"{/if} type="radio" name="apply_discount_to" id="apply_discount_to_order" value="order">
																			{l s='Order (without shipping)' mod='apmarketplace'}
																		</label>
																	</div>
																	<div class="radio">
																		<label for="apply_discount_to_product">
																			<input {if $discounts->reduction_product|intval > 0} checked="checked"{/if} type="radio" name="apply_discount_to" id="apply_discount_to_product" value="specific">
																			{l s='Specific product' mod='apmarketplace'}
																		</label>
																	</div>
																	<div class="radio">
																		<label for="apply_discount_to_cheapest">
																			<input {if $discounts->reduction_product|intval == -1}  checked="checked"{/if} type="radio" name="apply_discount_to" id="apply_discount_to_cheapest" value="cheapest">
																			{l s='Cheapest product' mod='apmarketplace'}
																		</label>
																	</div>
																</div>
															</div>
															<div id="apply_discount_to_product_special" class="form-group row">
																<label class="control-label col-lg-3">
																	{l s='Exclude discounted products' mod='apmarketplace'}
																</label>
																<div class="col-lg-9">
																	<span class="switch prestashop-switch fixed-width-lg">
																		<input {if $discounts->reduction_exclude_special|intval} checked="checked"{/if} type="radio" name="reduction_exclude_special" id="reduction_exclude_special_on" value="1">
																		<label class="t" for="reduction_exclude_special_on">
																			{l s='Yes' mod='apmarketplace'}
																		</label>
																		<input {if !$discounts->reduction_exclude_special|intval} checked="checked"{/if} type="radio" name="reduction_exclude_special" id="reduction_exclude_special_off" value="0">
																		<label class="t" for="reduction_exclude_special_off">
																			{l s='No' mod='apmarketplace'}
																		</label>
																	</span>
																</div>
															</div>
															<div class="form-group row">
																<label class="control-label col-lg-3">
																	{l s='Send a free gift' mod='apmarketplace'}
																</label>
																<div class="col-lg-9">
																	<span class="switch prestashop-switch fixed-width-lg">
																		<input {if $discounts->gift_product|intval}checked="checked"{/if} type="radio" name="free_gift" id="free_gift_on" value="1">
																		<label class="t" for="free_gift_on">
																			{l s='Yes' mod='apmarketplace'}
																		</label>
																		<input {if !$discounts->gift_product|intval}checked="checked"{/if} type="radio" name="free_gift" id="free_gift_off" value="0">
																		<label class="t" for="free_gift_off">
																			{l s='No' mod='apmarketplace'}
																		</label>
																	</span>
																</div>
															</div>
															<div id="free_gift_div" class="form-group row" {if !$discounts->gift_product|intval}style="display:none;"{/if}>
																<label class="control-label col-lg-3">
																	{l s='Search a product' mod='apmarketplace'}
																</label>
																<div class="col-lg-9">
																	<div class="input-group col-lg-5">
																		<input class="form-control" type="text" id="giftProductFilter" value="{$gift_product_filter}">
																		<span class="input-group-addon">
																			<i class="material-icons">search</i>
																		</span>
																	</div>
																</div>
															</div>
															<div id="gift_products_found" class="form-group row">
																<div id="gift_product_list" class="form-group row">
																	<label class="control-label col-lg-3">
																		{l s='Matching products' mod='apmarketplace'}
																	</label>
																	<div class="col-lg-5">
																		<select name="gift_product" id="gift_product" class="form-control">
																			<option value="{$discounts->gift_product}">{$product->name} - {$product->leo_price}</option>
																		</select>
																	</div>
																</div>
																<div id="gift_attributes_list" class="form-group" style="display:none;">
																	<label class="control-label col-lg-3">
																		{l s='Available combinations' mod='apmarketplace'}
																	</label>
																	<div class="col-lg-5" id="gift_attributes_list_select">
																	</div>
																</div>
															</div>
														</div>
														<div class="panel-footer" id="toolbar-footer">
															<button class="btn btn-primary" type="submit" name="edit_distcount">
																<i class="material-icons">save</i>
																{l s='Save' mod='apmarketplace'}
															</button>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
									{/if}
								</div>
							</div>		
						</div>
					</div>
				</div>
			</section>
		</form>
	{/if}					
{/block}
