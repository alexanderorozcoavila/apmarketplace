{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
{extends file=$layout}

{block name='content'}
	<div class="leo-list-vendors">
		{if !empty($list_vendor)}
			{foreach from=$list_vendor item=val_v}
				<div class="leo-profile-vendor">
					<div class="row">
						<div class="col-md-3 col-xs-4 col-sp-12">
							<div class="leo-image">
								<a href="{$baseurl}store?id={$val_v.id_apmarketplace_vendor}">
									<img class="img-fluid" alt="{$val_v.last_name}" title="{$val_v.last_name}" src="{$baseurl}modules/apmarketplace/views/img/vendor/{$val_v.image}">
								</a>
							</div>
							<div class="leo-infor-vendor">
								<p class="first-name">{l s='First Name' mod='apmarketplace'}: {$val_v.first_name}</p>
								<p class="last-name">{l s='Last Name' mod='apmarketplace'}: {$val_v.last_name}</p>
								<p class="Email">{l s='Email' mod='apmarketplace'}: {$val_v.email}</p>
								<p class="phone">{l s='Phone Number' mod='apmarketplace'}: {$val_v.phone}</p>
							</div>
							<div class="leo-vendor">
								<a href="{$baseurl}store?id={$val_v.id_apmarketplace_vendor}">{l s='Visit Store' mod='apmarketplace'}</a>
							</div>
						</div>
					</div>
				</div>
			{/foreach}
		{else}
			<h3 class="no-vendor">{l s='No Vendor' mod='apmarketplace'}</h3>
		{/if}
	</div>
{/block}
