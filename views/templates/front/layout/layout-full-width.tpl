{* 
* @Module Name: AP Market Place
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: Apmarketplace is module help you can build content for your shop
*}

{extends file="module:apmarketplace/views/templates/front/layout/layout-module.tpl"}


{block name='content-column'}
	<div class="row">
		<div class="content-column col-xs-12 col-sm-12 col-md-12">
			{include file="module:apmarketplace/views/templates/front/nav.tpl"}
			{block name='left-column'}{/block}
			{block name='right-column'}
				<p>Hello world! This is HTML5 Boilerplate.</p>
			{/block}
		</div>
	</div>
{/block}