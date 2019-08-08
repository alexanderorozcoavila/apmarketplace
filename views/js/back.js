/**
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
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/

$(document).ready(function() {
	getNewReview();
	getNewPayOrder();
	getNewQuestion();
});

function getNewQuestion()
{
	$.ajax({
		type: 'POST',
		headers: {"cache-control": "no-cache"},
		url: baseurl + '?rand=' + new Date().getTime(),
		async: true,
		cache: false,
		data: {
			"action": "get-new-question",		
		},
		success: function (result)
		{
			if(result != '')
			{						
				var obj = $.parseJSON(result);
				if (obj.questions > 0)
				{
					$('#subtab-AdminApmarketplaceManagement').addClass('has-questions');
					$('#subtab-AdminApmarketplaceQuestion').append('<div class="notification-container"><span class="notification-counter">'+obj.questions+'</span></div>');
				}
					
			}
				
		},
	});
}

function getNewPayOrder()
{
	$.ajax({
		type: 'POST',
		headers: {"cache-control": "no-cache"},
		url: baseurl + '?rand=' + new Date().getTime(),
		async: true,
		cache: false,
		data: {
			"action": "get-new-pay-order",		
		},
		success: function (result)
		{
			if(result != '')
			{						
				var obj = $.parseJSON(result);
				if (obj.orders > 0)
				{
					$('#subtab-AdminApmarketplaceManagement').addClass('has-orders');
					$('#subtab-AdminApmarketplaceOrder').append('<div class="notification-container"><span class="notification-counter">'+obj.orders+'</span></div>');
				}
					
			}
				
		},
	});
}

function getNewReview()
{
	$.ajax({
		type: 'POST',
		headers: {"cache-control": "no-cache"},
		url: baseurl + '?rand=' + new Date().getTime(),
		async: true,
		cache: false,
		data: {
			"action": "get-new-review",		
		},
		success: function (result)
		{
			if(result != '')
			{						
				var obj = $.parseJSON(result);
				if (obj.number_review > 0)
				{
					$('#subtab-AdminApmarketplaceManagement').addClass('has-review');
					$('#subtab-AdminApmarketplaceReview').append('<div class="notification-container"><span class="notification-counter">'+obj.number_review+'</span></div>');
				}
					
			}
				
		},
	});
}
