{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
{extends file="module:apmarketplace/views/templates/front/layout/layout-sidebar.tpl"}

{block name='right-column'}			
	<form id="form-cart_rule" name="form_cart_rule" method="GET" action="">
		{if isset($notification)}
			<p class="notification alert alert-success">{$notification nofilter}</p>
		{/if}
		<div class="discount-add leo-panel">
			<div class="panel-heading">
				<i class="material-icons">create</i>
				<span class="text-icon-inline">{l s='Cart rule' mod='apmarketplace'}</span>
			</div>
			<div class="panel-body">
				<ul class="tab nav nav-tabs">
					<li class="nav-item">
						<a class="nav-link active" id="cart_rule_link_informations" href="#cart_rule_informations" data-toggle="tab">
							<i class="material-icons">info</i>
							<span class="text-icon-inline">{l s='Information' mod='apmarketplace'}</span>	
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="cart_rule_link_conditions" href="#cart_rule_conditions" data-toggle="tab">
							<i class="material-icons">shuffle</i>
							<span class="text-icon-inline">{l s='Conditions' mod='apmarketplace'}</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="cart_rule_link_actions" href="#cart_rule_actions" data-toggle="tab">
							<i class="material-icons">build</i>
							<span class="text-icon-inline">{l s='Actions' mod='apmarketplace'}</span>
						</a>
					</li>
				</ul>
				<form action="" id="cart_rule_form" class="form-horizontal" method="POST">
					<div class="tab-content panel">
						<div id="cart_rule_informations" class="tab-pane active cart_rule_tab">
							<div class="form-group row">
								<label class="control-label col-md-3 col-sm-3 col-xs-12 required">
									{l s='Name' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<input class="form-control leo_name" required="" type="text" name="name" value="">
								</div>
							</div>

							<div class="form-group row">
								<label class="control-label col-md-3 col-sm-3 col-xs-12 required">
									{l s='Description' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<textarea class="textarea-autosize form-control leo_des" name="description" required="">
									</textarea>
								</div>
							</div>
							<div class="form-group row">
								<label class="control-label col-md-3 col-sm-3 col-xs-12 required">
									{l s='Code' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="leo-input-group group-input">
										<div class="input-text">
											<input class="form-control leo_code" required="" type="text" name="code" value="">
										</div>
										<div class="input-button">
											<a href="#" class="btn btn-primary gen_code">
												<i class="material-icons">shuffle</i>
												<span class="text-input-inline">{l s='Generate' mod='apmarketplace'}</span>
											</a>
										</div>
									</div>
									<span class="help-block">{l s='Caution! If you leave this field blank, the rule will automatically be applied to benefiting customers.' mod='apmarketplace'}</span>
								</div>
							</div>
							<div class="form-group row">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">
									{l s='Highlight' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="switch prestashop-switch fixed-width-lg">
										<input type="radio" name="highlight" id="highlight_on" value="1">
										<label for="highlight_on">
											{l s='Yes' mod='apmarketplace'}
										</label>
										<input type="radio" name="highlight" id="highlight_off" value="0" checked="checked">
										<label for="highlight_off">
											{l s='No' mod='apmarketplace'}
										</label>
										<a class="slide-button btn"></a>
									</span>
								</div>
							</div>

							<div class="form-group row">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">
									{l s='Partial use' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="switch prestashop-switch fixed-width-lg">
										<input type="radio" name="partial_use" id="partial_use_on" value="1" checked="checked">
										<label class="t" for="partial_use_on">
											{l s='Yes' mod='apmarketplace'}
										</label>
										<input type="radio" name="partial_use" id="partial_use_off" value="0">
										<label class="t" for="partial_use_off">
											{l s='No' mod='apmarketplace'}
										</label>
										<a class="slide-button btn"></a>
									</span>
								</div>
							</div>

							<div class="form-group row">
								<label class="control-label col-md-3 col-sm-3 col-xs-12 required">
									{l s='Priority' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<input class="form-control leo_priority" required="" type="text" name="priority" value="">
								</div>
							</div>

							<div class="form-group row">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">
									{l s='Status' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="switch prestashop-switch fixed-width-lg">
										<input type="radio" name="active" id="active_on" value="1" checked="checked">
										<label class="t" for="active_on">
											{l s='Yes' mod='apmarketplace'}
										</label>
										<input type="radio" name="active" id="active_off" value="0">
										<label class="t" for="active_off">
											{l s='No' mod='apmarketplace'}
										</label>
										<a class="slide-button btn"></a>
									</span>
								</div>
							</div>
						</div>
						<div id="cart_rule_conditions" class="tab-pane cart_rule_tab">
							<div class="form-group row">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">
									{l s='Limit to a single customer' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="leo-input-group group-icon">
										<span class="input-icon">
											<i class="material-icons">person</i>
										</span>
										<div class="input-text">
											<input type="text" id="customerFilter" class="form-control leo_customer" required="" name="customerFilter" value="" autocomplete="off">
										</div>
										<span class="input-icon">
											<i class="material-icons">search</i>
										</span>
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">
									{l s='Valid' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="leo-input-group group-icon">
												<span class="input-icon">
													{l s='From' mod='apmarketplace'}
												</span>
												<div class="input-text">
													<input type="text" class="date_from datepicker form-control input-medium hasDatepicker" name="date_from" value="">
												</div>
												<span class="input-icon">
													<i class="material-icons">&#xE916;</i>
												</span>
											</div>
											<div id="date_from" style="display:none;"></div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="leo-input-group group-icon">
												<span class="input-icon">
													{l s='To' mod='apmarketplace'}
												</span>
												<div class="input-text">
													<input type="text" class="date_to datepicker form-control input-medium hasDatepicker" name="date_to" value="">
												</div>
												<span class="input-icon">
													<i class="material-icons">&#xE916;</i>
												</span>
											</div>
											<div id="date_to" style="display:none;"></div>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">
									{l s='Minimum amount' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="row small-grid">
										<div class="col-xs-3">
											<input class="form-control" type="text" name="minimum_amount" value="">
										</div>
										<div class="col-xs-2">
											<select name="minimum_amount_currency" class="form-control">
												{foreach from=$currencies item='currency'}
													<option value="{$currency.id_currency}">
														{$currency.iso_code}
													</option>
												{/foreach}	
											</select>
										</div>
										<div class="col-xs-3">
											<select name="minimum_amount_tax" class="form-control">
												<option value="0">
													{l s='Tax excluded' mod='apmarketplace'}
												</option>
												<option value="1">
													{l s='Tax included' mod='apmarketplace'}
												</option>
											</select>
										</div>
										<div class="col-xs-4">
											<select name="minimum_amount_shipping" class="form-control">
												<option value="0">
													{l s='Shipping excluded' mod='apmarketplace'}
												</option>
												<option value="1">
													{l s='Shipping included' mod='apmarketplace'}
												</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">
									{l s='Total available' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<input class="form-control" type="text" name="quantity" value="">
								</div>
							</div>
							<div class="form-group row">
								<label class="control-label col-lg-3">
									{l s='Total available for each user' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<input class="form-control" type="text" name="quantity_per_user" value="">
								</div>
							</div>
						</div>
						<div id="cart_rule_actions" class="tab-pane cart_rule_tab">
							<div class="form-group row">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">
									{l s='Free shipping' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="switch prestashop-switch fixed-width-lg">
										<input type="radio" name="free_shipping" id="free_shipping_on" value="1">
										<label class="t" for="free_shipping_on">
											{l s='Yes shipping' mod='apmarketplace'}
										</label>
										<input type="radio" name="free_shipping" id="free_shipping_off" value="0" checked="checked">
										<label class="t" for="free_shipping_off">
											{l s='No shipping' mod='apmarketplace'}
										</label>
										<a class="slide-button btn"></a>
									</span>
								</div>
							</div>
							<div class="form-group row">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">
									{l s='Apply a discount' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="leo-radio">
										<label for="apply_discount_percent">
											<input type="radio" name="apply_discount" id="apply_discount_percent" value="percent">
											<span class="text-icon-inline">{l s='Percent (%)' mod='apmarketplace'}</span>
										</label>
									</div>
									<div class="leo-radio">
										<label for="apply_discount_amount">
											<input type="radio" name="apply_discount" id="apply_discount_amount" value="amount">
											<span class="text-icon-inline">{l s='Amount' mod='apmarketplace'}</span>
										</label>
									</div>
									<div class="leo-radio">
										<label for="apply_discount_off">
											<input type="radio" checked="checked" name="apply_discount" id="apply_discount_off" value="off">
											<i class="material-icons text-danger">close</i>
											<span class="text-icon-inline">{l s='None' mod='apmarketplace'}</span>
										</label>
									</div>
								</div>
							</div>
							<div id="apply_discount_percent_div" class="form-group row" style="display:none;">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">
									{l s='Value' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="leo-input-group group-icon">
										<span class="input-icon">
											{l s='%' mod='apmarketplace'}
										</span>
										<span class="input-text">
											<input type="text" id="reduction_percent" class="form-control" name="reduction_percent" value="">
										</span>
									</div>
									<span class="help-block">
										<i class="icon-warning-sign"></i>
										{l s='Does not apply to the shipping costs' mod='apmarketplace'}
									</span>
								</div>
							</div>
							<div id="apply_discount_amount_div" class="form-group row" style="display:none;">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">
									{l s='Amount' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="row">
										<div class="col-lg-4">
											<input class="form-control" type="text" id="reduction_amount" name="reduction_amount">
										</div>
										<div class="col-lg-4">
											<select name="reduction_currency" class="form-control">
												{foreach from=$currencies item='currency'}
													<option value="{$currency.id_currency}">
														{$currency.iso_code}
													</option>
												{/foreach}
											</select>
										</div>
										<div class="col-lg-4">
											<select name="reduction_tax" class="form-control">
												<option value="0">
													{l s='Tax excluded' mod='apmarketplace'}
												</option>
												<option value="1">
													{l s='Tax included' mod='apmarketplace'}
												</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div id="apply_discount_to_div" class="form-group row" style="display:none;">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">
									{l s='Apply a discount to' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="leo-radio">
										<label for="apply_discount_to_order">
											<input type="radio" name="apply_discount_to" id="apply_discount_to_order" value="order">
											<span class="text-icon-inline">{l s='Order (without shipping)' mod='apmarketplace'}</span>
										</label>
									</div>
									<div class="leo-radio">
										<label for="apply_discount_to_product">
											<input type="radio" name="apply_discount_to" id="apply_discount_to_product" value="specific">
											<span class="text-icon-inline">{l s='Specific product' mod='apmarketplace'}</span>
										</label>
									</div>
									<div class="leo-radio">
										<label for="apply_discount_to_cheapest">
											<input type="radio" name="apply_discount_to" id="apply_discount_to_cheapest" value="cheapest">
											<span class="text-icon-inline">{l s='Cheapest product' mod='apmarketplace'}</span>
										</label>
									</div>
								</div>
							</div>
							<div id="apply_discount_to_product_special" class="form-group row">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">
									{l s='Exclude discounted products' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="switch prestashop-switch fixed-width-lg">
										<input type="radio" name="reduction_exclude_special" id="reduction_exclude_special_on" value="1">
										<label class="t" for="reduction_exclude_special_on">
											{l s='Yes' mod='apmarketplace'}
										</label>
										<input checked="checked" type="radio" name="reduction_exclude_special" id="reduction_exclude_special_off" value="0">
										<label class="t" for="reduction_exclude_special_off">
											{l s='No' mod='apmarketplace'}
										</label>
										<a class="slide-button btn"></a>
									</span>
								</div>
							</div>
							<div class="form-group row">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">
									{l s='Send a free gift' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="switch prestashop-switch fixed-width-lg">
										<input type="radio" name="free_gift" id="free_gift_on" value="1">
										<label class="t" for="free_gift_on">
											{l s='Yes' mod='apmarketplace'}
										</label>
										<input checked="checked" type="radio" name="free_gift" id="free_gift_off" value="0">
										<label class="t" for="free_gift_off">
											{l s='No' mod='apmarketplace'}
										</label>
										<a class="slide-button btn"></a>
									</span>
								</div>
							</div>
							<div id="free_gift_div" class="form-group row" style="display:none;">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">
									{l s='Search a product' mod='apmarketplace'}
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="leo-input-group group-icon">
										<div class="input-text">
											<input class="form-control" type="text" id="giftProductFilter" value="">
										</div>
										<span class="input-icon">
											<i class="material-icons">search</i>
										</span>
									</div>
								</div>
							</div>
							<div id="gift_products_found" class="form-group row" style="display:none;">
								<div id="gift_product_list" class="form-group row">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">
										{l s='Matching products' mod='apmarketplace'}
									</label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<select name="gift_product" id="gift_product" class="form-control">
											
										</select>
									</div>
								</div>
								<div id="gift_attributes_list" class="form-group" style="display:none;">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">
										{l s='Available combinations' mod='apmarketplace'}
									</label>
									<div class="col-md-9 col-sm-9 col-xs-12" id="gift_attributes_list_select">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-footer" id="toolbar-footer">
						<button class="btn btn-primary" type="submit" name="add_distcount">
							<i class="material-icons">save</i>
							{l s='Save' mod='apmarketplace'}
						</button>
					</div>
				</form>
			</div>
		</div>		
	</form>
{/block}
