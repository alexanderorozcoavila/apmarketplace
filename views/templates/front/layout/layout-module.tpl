{* 
* @Module Name: AP Market Place
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: Apmarketplace is module help you can build content for your shop
*}

{extends file=$layout}
{block name='content'}
	{block name='check-error'}
		{if $check == 0}
			<p class="alert alert-success">{l s='Your account not active. Please contact for us to active account' mod='apmarketplace'}</p>
		{/if}
	{/block}

	{block name='check-success'}
		{if $check == 1}
			<section id="apmarketplace">
				<div class="container">
					{block name='content-column'}
						<div class="row">
							{block name='left-column'}
								<p>Hello world! This is HTML5 Boilerplate.</p>
							{/block}
							{block name='right-column'}
								<p>Hello world! This is HTML5 Boilerplate.</p>
							{/block}
						</div>
					{/block}
				</div>
			</section>
		{/if}
	{/block}
{/block}
