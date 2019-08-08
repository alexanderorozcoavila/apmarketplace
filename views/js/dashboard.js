/**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

// This variables are defined in the dashboard view.tpl
// dashboard_ajax_url
// adminstats_ajax_url
// no_results_translation
// dashboard_use_push
// read_more

function refreshDashboard(module_name, use_push, extra) {
	var module_list = [];
	var date_from = $('.apmarketplace_from').val();
	var date_to = $('.apmarketplace_to').val();

	this.getWidget = function(module_id) {
		$.ajax({
			url : dashboard_ajax_url,
			data : {
				action:'refreshDashboard',
				module: module_list[module_id],
				dashboard_use_push: Number(use_push),
				extra: extra,
				date_from: date_from,
				date_to: date_to
			},
			// Ensure to get fresh data
			headers: { "cache-control": "no-cache" },
			cache: false,
			global: false,
			dataType: 'json',
			success : function(widgets){
				for (var widget_name in widgets) {
					for (var data_type in widgets[widget_name]) {
						window[data_type](widget_name, widgets[widget_name][data_type]);
					}
				}
				if (parseInt(dashboard_use_push) === 1) {
					refreshDashboard(false, true);
				}
			},
			contentType: 'application/json'
		});
	};
	if (module_name === false) {
		$('.widget').each( function () {
			module_list.push($(this).attr('id'));
			if (!use_push) {
				$(this).addClass('loading');
			}
		});
	}
	else {
		module_list.push(module_name);
		if (!use_push) {
			$('#'+module_name+' section').each( function (){
				$(this).addClass('loading');
			});
		}
	}
	for (var module_id in module_list) {
		if (use_push && !$('#'+module_list[module_id]).hasClass('allow_push')) {
			continue;
		}
		this.getWidget(module_id);
	}
}

function data_value(widget_name, data) {
	for (var data_id in data) {
		$('#'+data_id+' ').html(data[data_id]);
		$('#'+data_id+', #'+widget_name).closest('section').removeClass('loading');
	}
}

function data_trends(widget_name, data) {
	for (var data_id in data) {
		this.el = $('#'+data_id);
		this.el.html(data[data_id].value);
		if (data[data_id].way === 'up') {
			this.el.parent().removeClass('dash_trend_down').removeClass('dash_trend_right').addClass('dash_trend_up');
		}
		else if (data[data_id].way === 'down') {
			this.el.parent().removeClass('dash_trend_up').removeClass('dash_trend_right').addClass('dash_trend_down');
		}
		else {
			this.el.parent().removeClass('dash_trend_down').removeClass('dash_trend_up').addClass('dash_trend_right');
		}
		this.el.closest('section').removeClass('loading');
	}
}

function data_table(widget_name, data) {
	for (var data_id in data) {
		//fill header
		var tr = '<tr>';
		for (var header in data[data_id].header) {
			var head = data[data_id].header[header];
			var th = '<th '+ (head.class ? ' class="'+head.class+'" ' : '' )+ ' '+(head.id ? ' id="'+head.id+'" ' : '' )+'>';
			th += (head.wrapper_start ? ' '+head.wrapper_start+' ' : '' );
			th += head.title;
			th += (head.wrapper_stop ? ' '+head.wrapper_stop+' ' : '' );
			th += '</th>';
			tr += th;
		}
		tr += '</tr>';
		$('#'+data_id+' thead').html(tr);

		//fill body
		$('#'+data_id+' tbody').html('');

		if(typeof data[data_id].body === 'string') {
			$('#'+data_id+' tbody').html('<tr><td class="text-center" colspan="'+data[data_id].header.length+'"><br/>'+data[data_id].body+'</td></tr>');
		}
		else if (data[data_id].body.length) {
			for (var body_content_id in data[data_id].body) {
				tr = '<tr>';
				for (var body_content in data[data_id].body[body_content_id]) {
					var body = data[data_id].body[body_content_id][body_content];
					var td = '<td '+ (body.class ? ' class="'+body.class+'" ' : '' )+ ' '+(body.id ? ' id="'+body.id+'" ' : '' )+'>';
					td += (body.wrapper_start ? ' '+body.wrapper_start+' ' : '' );
					td += body.value;
					td += (body.wrapper_stop ? ' '+body.wrapper_stop+' ' : '' );
					td += '</td>';
					tr += td;
				}
				tr += '</tr>';
				$('#'+data_id+' tbody').append(tr);
			}
		}
		else {
			$('#'+data_id+' tbody').html('<tr><td class="text-center" colspan="'+data[data_id].header.length+'">'+no_results_translation+'</td></tr>');
		}
	}
}

function data_chart(widget_name, charts) {
	for (var chart_id in charts) {
		window[charts[chart_id].chart_type](widget_name, charts[chart_id]);
	}
}

$(document).ready( function () {
	refreshDashboard('apmarketplace', false);
	$('#page-header-desc-configuration-switch_demo').tooltip().click(function(e) {
		$.ajax({
			url : dashboard_ajax_url,
			data : {
				ajax:true,
				action:'setSimulationMode',
				PS_DASHBOARD_SIMULATION: $(this).find('i').hasClass('process-icon-toggle-on') ? 0 : 1
			},
			success : function(result) {
				if ($('#page-header-desc-configuration-switch_demo i').hasClass('process-icon-toggle-on')) {
					$('#page-header-desc-configuration-switch_demo i').removeClass('process-icon-toggle-on').addClass('process-icon-toggle-off');
				} else {
					$('#page-header-desc-configuration-switch_demo i').removeClass('process-icon-toggle-off').addClass('process-icon-toggle-on');
				}
				refreshDashboard(false, false);
			}
		});
	});
});
