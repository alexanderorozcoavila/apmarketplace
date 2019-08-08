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
<div id="_desktop_apmarketplace">
	<div class="apmarketplace-infor">
		{if $check == 0}
			<a href="{$baseurl}vendorlogin?login=1" title="{l s='Log in to vendor account' mod='apmarketplace'}" rel="nofollow">
		        <i class="material-icons">account_box</i>
		        <span class="hidden-sm-down">{l s='Sign in Vendor' mod='apmarketplace'}</span>
      		</a>
		{/if}

		{if $check == 1}
			<div class="apmarketplace-selector dropdown js-dropdown">
				<button data-toggle="dropdown" class="btn-unstyle" aria-haspopup="true" aria-expanded="false">
					<i class="material-icons">account_box</i>
					<span class="expand-more hidden-sm-down">{$vendors->first_name} {$vendors->last_name}</span>
					<i class="material-icons expand-more">&#xE5C5;</i>
				</button>
				<ul class="dropdown-menu" aria-labelledby="language-selector-label">
					<li class="vendor-dashboard dropdown-item">
						<a href="{$baseurl}dashboard" title="{l s='Dashboard' mod='apmarketplace'}" rel="nofollow">{l s='Dashboard' mod='apmarketplace'}</a>
					</li>
					<li class="vendor-logout dropdown-item">
						<a href="{$baseurl}vendorlogin?out=1" title="{l s='Sign Out vendor' mod='apmarketplace'}" rel="nofollow">{l s='Sign out' mod='apmarketplace'}</a>
					</li>
				</ul>
			</div>
		{/if}
	</div>
</div>
