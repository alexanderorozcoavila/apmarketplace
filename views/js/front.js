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
	sendMassage();
	addDateTime();
	review();
	loadReview();
	searchProduct();
	questionAnswer();
});

function questionAnswer()
{
	if ($('.send-question').length > 0) {
		$('.leo_reply').click(function() {
			$(this).closest('.item-question').find('.leo-answer').show();
		});
		$('.send-question').click(function() {
			var id_product = $(this).data('product');
			var id_customer = $(this).data('customer');
			var content = $(this).parent().parent().find('.content_question').val();
			var id_question = 0;
			ajaxQuestionAnswer(id_customer, id_product, id_question, content);
		});
		$('.send-answer').click(function() {
			var id_product = $(this).data('product');
			var id_customer = $(this).data('customer');
			var id_question = $(this).data('question');
			var content = $(this).parent().parent().find('.content_answer').val();
			ajaxQuestionAnswer(id_customer, id_product, id_question, content);
		});
	}
}

function ajaxQuestionAnswer(id_customer, id_product, id_question, content)
{
	$.ajax({
		type: 'POST',
		headers: {"cache-control": "no-cache"},
		url: dashboard_ajax_url + '?rand=' + new Date().getTime(),
		async: true,
		cache: false,
		data: {
			"action": "ajaxQuestionAnswer",
			'id_customer': id_customer,
			'id_product': id_product,
			'id_question': id_question,
			'content' : content,
		},
		success: function (result)
		{
			if (result == '1') {
				location.reload();	
			}			
		}
	});
}

function searchProduct()
{
	$('#search_widget').hide();
	$('.leo_search').keyup(function() {
		if ($(this).val().length >= 3) {
			var name = $(this).val();
			$.ajax({
				type: 'POST',
				headers: {"cache-control": "no-cache"},
				url: dashboard_ajax_url + '?rand=' + new Date().getTime(),
				async: true,
				cache: false,
				data: {
					"action": "searchProductHome",
					'name': name
				},
				success: function (result)
				{
					var html = '';
					if (result != 1) {
						var object_results = $.parseJSON(result);
						$.each(object_results, function(key, data) {
							html += '<div class="item-product clearfix">';
							html += '<div class="product-img clearfix col-md-3">';
							html += '<a class="image" href="'+data.link+'" title="'+data.name+'">';
							html += '<img class="imgm img-thumbnail" src="'+data.image+'" title="'+data.name+'" alt="'+data.name+'">';
							html += '</a>';
							html += '</div>';
							html += '<div class="product-info col-md-9">';
							html += '<a class="product-name" href="'+data.link+'" title="'+data.name+'">'+data.name+'</a>';
							html += '<p class="product_price">'+data.price_tax_excl+'</p>';
							html += '</div>';
							html += '</div>';
						});
						$('.leo_item').html(html);
						$('body').click(function() {
							$('.leo_item').html('');
						});
					} else {
						html += product;
						$('.leo_item').html(html);
						$('body').click(function() {
							$('.leo_item').html('');
						});
					}			
				}
			});
		}
	});
}

function review()
{
	$('.read-review').click(function(){
		if ($('.leo-product-show-review-title').length)
		{
			$('html, body').animate({
				scrollTop: $('.leo-product-show-review-title').offset().top
			}, 500);
		}
		return false;
	});
}

function loadReview()
{
	if ($('.open-review-form').length) {
		var id_product = $('.open-review-form').data('id-product');		
		var is_logged = $('.open-review-form').data('is-logged');
		$.ajax({
			type: 'POST',
			headers: {"cache-control": "no-cache"},
			url: dashboard_ajax_url + '?rand=' + new Date().getTime(),
			async: true,
			cache: false,
			data: {
				"action": "render-modal-review",
				"id_product": id_product,				
				"is_logged": is_logged
			},
			success: function (result)
			{
				if(result != '')
				{						
					$('body').append(result);
					activeEventModalReview();
					activeStar();
					// $('.open-review-form').fadeIn('fast');
				}
				else
				{
					alert(review_error);
				}
							
			}
		});
		$('.open-review-form').click(function(){
			if ($('#criterions_list').length)
			{	
				$('.leo-modal-review').modal('show');
			}
			else
			{
				if ($('.leo-modal-review .modal-body .disable-form-review').length)
				{
					$('.leo-modal-review').modal('show');
				}
				else
				{
					$('.leo-modal-review-bt').remove();
					$('.leo-modal-review .modal-header').remove();
					$('.leo-modal-review .modal-body').empty();
					$('.leo-modal-review .modal-body').append('<div class="form-group disable-form-review has-danger text-center"><label class="form-control-label">'+disable_review_form_txt+'</label></div>');
					$('.leo-modal-review').modal('show');
				}
				
			}
			return false;
		});
	}
}

function activeEventModalReview()
{
	$('.form-new-review').submit(function(){
		if ($('.new_review_form_content .form-group.leo-has-error').length ||
			$('.leo-fake-button').hasClass('validate-ok')
		) {
			return false;
		}
	});
	$('.leo-modal-review').on('show.bs.modal', function (e) {
		$('.leo-modal-review-bt').click(function() {
			if (!$(this).hasClass('active'))
			{
				$(this).addClass('active');
				$('.leo-modal-review-bt-text').hide();
				$('.leo-modal-review-loading').css({'display':'block'});
				
				$('.new_review_form_content input, .new_review_form_content textarea').each(function(){
					
					if ($(this).val() == '')
					{
						$(this).parent('.form-group').addClass('leo-has-error');
						$(this).attr("required", "");
					}
					else
					{
						$(this).parent('.form-group').removeClass('leo-has-error');
						$(this).removeAttr('required');
					}
				})
				
				if ($('.new_review_form_content .form-group.leo-has-error').length)
				{
					$(this).removeClass('active');
					$('.leo-modal-review-bt-text').show();
					$('.leo-modal-review-loading').hide();
				}
				else
				{
					$('.leo-fake-button').addClass('validate-ok');
					$.ajax({
						type: 'POST',
						headers: {"cache-control": "no-cache"},
						url: dashboard_ajax_url + '?action=add-new-review&rand=' + new Date().getTime(),
						async: true,
						cache: false,
						data: $( ".new_review_form_content input, .new_review_form_content textarea" ).serialize(),
						success: function (result)
						{
							if (result != '')
							{
								var object_result = $.parseJSON(result);
								$('.leo-modal-review-bt').fadeOut('slow', function() {
									$(this).remove();
									
								});
								$('.leo-modal-review .modal-body>.row').fadeOut('slow', function() {
									$(this).remove();
									if (object_result.result)
									{
										$('.leo-modal-review .modal-body').append('<div class="form-group has-success"><label class="form-control-label">'+object_result.sucess_mess+'</label></div>');
									}
									else
									{
										$.each(object_result.errors, function(key, val) {
											$('.leo-modal-review .modal-body').append('<div class="form-group has-danger text-center"><label class="form-control-label">'+val+'</label></div>');
										});
									}
								});
							}
							else
							{
								alert(review_error);
							}
							
						},
						error: function (XMLHttpRequest, textStatus, errorThrown) {
							alert("TECHNICAL ERROR: \n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
							window.location.replace($('.open-review-form').data('product-link'));
						}
					});
				}
				$('.leo-fake-button').trigger('click');
			}
			
		})
	})
	
	$('.leo-modal-review').on('hide.bs.modal', function (e) {
		if (!$('.leo-modal-review-bt').length && !$('.leo-modal-review .modal-body .disable-form-review').length) {
			location.reload();
		}
	})
	
}

function activeStar()
{
	$('input.star').rating({cancel: cancel_rating_txt});
	$('.auto-submit-star').rating({cancel: cancel_rating_txt});
}

function addDateTime()
{
	$(".apmarketplace_from").removeAttr("id").removeClass("hasDatepicker").datepicker({
	    prevText: '',
	    nextText: '',
	    dateFormat: 'yy-mm-dd',
	});

	$(".apmarketplace_to").removeAttr("id").removeClass("hasDatepicker").datepicker({
	    prevText: '',
	    nextText: '',
	    dateFormat: 'yy-mm-dd',
	});

}

function sendMassage()
{
	var url = $('.leo_widget').data('url');
	var email_ven = $('.leo_widget').data('email');
	var name = '';
	var phone = '';
	var email = '';
	var message = '';
	$('.leo_name input').change(function() {
		name = $(this).val();
	});

	$('.leo_phone input').change(function() {
		phone = $(this).val();
	});

	$('.leo_email input').change(function() {
		email = $(this).val();
	});

	$('.leo_message textarea').change(function() {
		message = $(this).val();
	});

	if (typeof(url) != 'undefined') {
		$('.leo_submit').click(function() {
			if (name == '') {
				$('.leo_name').addClass('leo_error');
				return false;
			} else {
				$('.leo_name').removeClass('leo_error');
			}

			if (phone == '') {
				$('leo_phone').addClass('leo_error');
				return false;
			} else {
				$('.leo_phone').removeClass('leo_error');
			}

			$.ajax({
				type: 'POST',
				url: url + 'modules/apmarketplace/ajax.php',
				async: true,
				cache: false,
				data: {
					"action" : "sendMail",
					"name" : name,
					"phone" : phone,
					"email" : email,
					"message" : message,
					'email_ven' : email_ven,
				},
				success: function (data)
				{	
					if (data == "1") {
						$('.leo-success').show();	
					} else {
						$('.leo-error').show();
					}	 
				}	
			});
		});	
	}
}
