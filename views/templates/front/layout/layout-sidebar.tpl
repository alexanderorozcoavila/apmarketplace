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
		<div class="left-column col-xs-12 col-sm-12 col-md-12 col-lg-3">
			{block name='left-column'}
				{include file="module:apmarketplace/views/templates/front/left.tpl"}
			{/block}
		</div>
		<div class="right-column col-xs-12 col-sm-12 col-md-12 col-lg-9">
			{block name='right-column'}
				<p>Hello world! This is HTML5 Boilerplate.</p>
			{/block}
		</div>
	</div>
{/block}