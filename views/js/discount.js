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
	deleteDiscount();
	genCode();
	applyDistcount();
	addDateTimeDiscount();
	
});

         function logEvent(type, date) {
                $("<div class='log__entry'/>").hide().html("<strong>"+type + "</strong>: "+date).prependTo($('#eventlog')).show(200);
            }

function addDateTimeDiscount()
{	
	$('.date_from').click(function() {
		$('#date_from').show();
		$('#date_to').hide();
	});
	$('.date_to').click(function() {
		$('#date_to').show();
		$('#date_from').hide();
	});

	$('#date_from').datetimepicker({
		viewMode: 'YMDHMS',
		onDateChange: function() {
			$('.date_from').val(this.getText());
		},
	});
	$('#date_from .ok').click(function() {
		$('#date_from').hide();
	});

	$('#date_to').datetimepicker({
		viewMode: 'YMDHMS',
		onDateChange: function() {
			$('.date_to').val(this.getText());
		},
	});
	$('#date_to .ok').click(function() {
		$('#date_to').hide();
	});
}

function applyDistcount()
{
	$('#apply_discount_percent').click(function() {
		$('#apply_discount_amount_div').hide('slow');
		$('#apply_discount_to_div').show('slow');
		$('#apply_discount_percent_div').show('slow');
		$('#apply_discount_to_cheapest').closest('.radio').show('slow');
	});
	$('#apply_discount_off').click(function() {
		$('#apply_discount_amount_div').hide('slow');
		$('#apply_discount_to_div').hide('slow');
		$('#apply_discount_percent_div').hide('slow');
	});
	$('#apply_discount_amount').click(function() {
		$('#apply_discount_amount_div').show('slow');
		$('#apply_discount_to_div').show('slow');
		$('#apply_discount_percent_div').hide('slow');
		$('#apply_discount_to_cheapest').closest('.radio').hide('slow');
	})
	$("input[name=free_gift]").click(function() {
		var value = $("input[name=free_gift]:checked").val();
		if (value == 1) {
			$('#free_gift_div').show('slow');
		} else {
			$('#free_gift_div').hide('slow');
		}
	});
	$('#giftProductFilter').keyup(function() {
		var length = $(this).val().length;
		var value = $(this).val(); 
		if (length >= 3) {
			$.ajax({
				type: 'POST',
				url: dashboard_ajax_url,
				async: true,
				cache: false,
				data: {
					"action" : "searchProduct",
					"value" : value,
				},
				success: function (data)
				{	
					var object_result = $.parseJSON(data);
					if (object_result.found == 1) {
						var products = object_result.products;
						var match = '';
						var combination = ''
						$.each(products, function(key, product) {
							match += '<option value="' + product.id_product + '">' + product.name + '</option>';
							combination += '<select id="product_' + product.id_product + '" style="display:none;" class="form-control form-atribute" name="product_' + product.id_product + '">';
							$.each(product.combinations, function(key1, combi) {
								combination += '<option value="' + combi.id_product_attribute + '">' + combi.attributes + ' - ' + combi.formatted_price + '</option>';
							});	
							combination += '</select>';
						});
						$('#gift_product').append(match);
						$('#gift_attributes_list_select').append(combination);
						$('#gift_products_found').show();
						selectProduct();
					}
				}	
			});	
		}
	});
}

function selectProduct()
{
	$('#gift_product').change(function() {
		var id_product = $(this).val();
		if ($('#product_' + id_product + ' option').length > 0) {
			$('#gift_attributes_list').show();
			$('.form-atribute').hide();
			$('#product_' + id_product + '').show();
		} else {
			$('#gift_attributes_list').hide();
		}
	})
}

function genCode()
{
	$('.gen_code').click(function() {
		var code = '';
		var chars = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ";
		for (var i = 1; i <= 8; ++i)
			code += chars.charAt(Math.floor(Math.random() * chars.length));
		$('.leo_code').val(code);
	})
}

function deleteDiscount()
{
	$('.discount-delete').click(function(e) {
		e.preventDefault();
		var id_cart_rule = $(this).data('id');
		var text = $(this).data('text');
		var alert = $(this).data('alert');
		var r = confirm(text);
		if (r == true) {
		    	$.ajax({
				type: 'POST',
				url: dashboard_ajax_url,
				async: true,
				cache: false,
				data: {
					"action" : "deleteCartRule",
					"id" : id_cart_rule,
				},
				success: function (data)
				{	
					if (data == 1) {
						window.location.reload()
					} else {
						alert(alert);
					}
				}	
			});
		}
	});
}
