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
	if ($('#product_catalog_add').length > 0) {
		tinymce.init({
			selector: "textarea",
			
			/* theme of the editor */
			theme: "modern",
			skin: "lightgray",
			
			/* width and height of the editor */
			width: "100%",
			height: 300,
			
			/* display statusbar */
			statubar: true,
			
			/* plugin */
			plugins: [
				"advlist autolink link image lists charmap print preview hr anchor pagebreak",
				"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				"save table contextmenu directionality emoticons template paste textcolor"
			],

			/* toolbar */
			toolbar: "insertfile code | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
			
			/* style */
			style_formats: [
				{title: "Headers", items: [
					{title: "Header 1", format: "h1"},
					{title: "Header 2", format: "h2"},
					{title: "Header 3", format: "h3"},
					{title: "Header 4", format: "h4"},
					{title: "Header 5", format: "h5"},
					{title: "Header 6", format: "h6"}
				]},
				{title: "Inline", items: [
					{title: "Bold", icon: "bold", format: "bold"},
					{title: "Italic", icon: "italic", format: "italic"},
					{title: "Underline", icon: "underline", format: "underline"},
					{title: "Strikethrough", icon: "strikethrough", format: "strikethrough"},
					{title: "Superscript", icon: "superscript", format: "superscript"},
					{title: "Subscript", icon: "subscript", format: "subscript"},
					{title: "Code", icon: "code", format: "code"}
				]},
				{title: "Blocks", items: [
					{title: "Paragraph", format: "p"},
					{title: "Blockquote", format: "blockquote"},
					{title: "Div", format: "div"},
					{title: "Pre", format: "pre"}
				]},
				{title: "Alignment", items: [
					{title: "Left", icon: "alignleft", format: "alignleft"},
					{title: "Center", icon: "aligncenter", format: "aligncenter"},
					{title: "Right", icon: "alignright", format: "alignright"},
					{title: "Justify", icon: "alignjustify", format: "alignjustify"}
				]}
			]
		});
	}
	actionProduct();
	submitCategory();
	addImage();
	deleteRowImage();
	selectCategory();
	deleteImage();
	clickPaymentInformation();
	leoCategoriesFilter();
	leoLang();
});

function leoLang()
{
	$('.select-lang').change(function() {
		var id = $(this).val();
		$('.lang-lang').hide();
		$('.lang_' + id + '').show();
	});
}

function leoCategoriesFilter()
{
	$('.product_catalog_category_tree_filter_expand').click(function(e) {
		e.preventDefault();
		$('.product_catalog_category_tree_filter_collapse').show();
		$('.product_catalog_category_tree_filter_expand').hide();
		$('.leo-category-top-menu .collapse').collapse('show');
	});

	$('.product_catalog_category_tree_filter_collapse').click(function(e) {
		e.preventDefault();
		$('.product_catalog_category_tree_filter_expand').show();
		$('.product_catalog_category_tree_filter_collapse').hide();
		$('.leo-category-top-menu .collapse').collapse('hide');
	});

	$('.product_catalog_category_tree_filter_reset').click(function(e) {
		e.preventDefault();
		var url = window.location.href;
		url = url.replace("categories=", "");
		window.location.replace(url);
	});
}

function clickPaymentInformation()
{
	$('.open_payment_information').click(function() {
		if ($(this).hasClass('active')) {
			$(this).removeClass('active');
			$('.payment_information').hide();
		} else {
			$(this).addClass('active');
			$('.payment_information').show();
		}
	});
}

function deleteImage()
{
	$('.delete-image').click(function() {
		var id_product = $(this).data('id_product');
		var id_image = $(this).data('image');
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
					"action" : "deleteImage",
					"id_product" : id_product,
					"id_image" : id_image,
				},
				success: function (data)
				{	
					if (data == 1) {
						window.location.reload()
					} else {
						alert(''+alert+'');
					}
				}	
			});
		}
	});
}

function submitCategory()
{
	if ($('#product_catalog_list').length) {
		$('.categories_id').click(function() {
			$('#product_catalog_list').trigger('submit');
		});
	}
}

function actionProduct()
{
	$('.btn-delete').click(function(e) {
		e.preventDefault();
		var id_product = $(this).closest('tr').data('id-product');
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
					"action" : "deleteProduct",
					"id_product" : id_product,
				},
				success: function (data)
				{	
					if (data == 1) {
						window.location.reload()
					} else {
						alert(''+alert+'');
					}
				}	
			});
		}
	});
}

function addImage()
{
	$('.leo-product-image .add_image').click(function(e) {
		e.preventDefault();
		var html = $('.file_templates').html();
		var number = Number($('.row-image').length);
		html = html.replace('leo_media', 'leo_media' + number);
		$('.list-image').append(html);
		deleteRowImage();
	})
}

function deleteRowImage()
{
	$('.delete').click(function() {
		$(this).closest('.row-image').remove();
		var count = 1;
		$('.list-image .row-image input').each(function() {
			$(this).attr('name', 'leo_media' + count);
			count = count + 1;
		});
	});
}

function selectCategory()
{
	$('.category_radio').click(function() {
		var id = $(this).val();
		$('.category_box').each(function() {
			if ($(this).val() == id) {
				$(this).attr('checked', true);
			}
		})
	});
}
