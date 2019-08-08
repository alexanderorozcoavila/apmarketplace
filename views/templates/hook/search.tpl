{*
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<!-- <script>{Configuration::get('APMARKETPLACE_CONFIG_SUBIZ_CODE') nofilter}</script> -->
{literal}
	<script>
		/*
		subiz('setLanguage', 'en');
		window.subiz('changeCss', '.color-theme-default {background-color: ' + color + ';}');
		*/
	</script>
{/literal}
<div class="row">
	<div class="col-md-12">
		<div class="wrap-search">
			<div class="search-widget">
				<form method="get" action="search">
					<input type="text" name="s" value="{if isset($name)}{$name}{/if}" aria-label="Search" class="leo_search" autocomplete="off">
					<button type="submit">
						<i class="material-icons search"></i>
			      		<span class="hidden-xl-down">Search</span>
					</button>
				</form>
			</div>
			<div class="leo_search">
				<div class="leo_item row">

				</div>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
	.search-widget {
		width: 100%;
	}
	input.leo_search {
		width: 100%;
	}
	.wrap-search {
		position: relative;
		width: 500px;
		margin: 0px auto 10px;
		max-width: 100%;
	}
	div.leo_search {
		background: white;
		position: absolute;
		top: 100%;
		left: 0px;
		width: 100%;
		z-index: 99;
	}
</style>
