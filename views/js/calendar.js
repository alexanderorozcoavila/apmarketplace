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
Date.prototype.addDays = function(value) {
	this.setDate(this.getDate() + value);

	return this;
};

Date.prototype.addMonths = function(value) {
	var date = this.getDate();
	this.setMonth(this.getMonth() + value);

	if (this.getDate() < date) {
		this.setDate(0);
	}

	return this;
};

Date.prototype.addWeeks = function(value) {
	this.addDays(value * 7);

	return this;
};

Date.prototype.addYears = function(value) {
	var month = this.getMonth();
	this.setFullYear(this.getFullYear() + value);

	if (month < this.getMonth()) {
		this.setDate(0);
	}

	return this;
};

Date.parseDate = function(date, format) {
	if (format === undefined)
		format = 'Y-m-d';

	var formatSeparator = format.match(/[.\/\-\s].*?/);
	var formatParts     = format.split(/\W+/);
	var parts           = date.split(formatSeparator);
	var date            = new Date();

	if (parts.length === formatParts.length) {
		date.setHours(0);
		date.setMinutes(0);
		date.setSeconds(0);
		date.setMilliseconds(0);

		for (var i=0; i<=formatParts.length; i++) {
			switch(formatParts[i]) {
				case 'dd':
				case 'd':
				case 'j':
				date.setDate(parseInt(parts[i], 10)||1);
				break;

				case 'mm':
				case 'm':
				date.setMonth((parseInt(parts[i], 10)||1) - 1);
				break;

				case 'yy':
				case 'y':
				date.setFullYear(2000 + (parseInt(parts[i], 10)||1));
				break;

				case 'yyyy':
				case 'Y':
				date.setFullYear(parseInt(parts[i], 10)||1);
				break;
			}
		}
	}

	return date;
};

Date.prototype.subDays = function(value) {
	this.setDate(this.getDate() - value);

	return this;
};

Date.prototype.subMonths = function(value) {
	var date = this.getDate();
	this.setMonth(this.getMonth() - value);

	if (this.getDate() < date) {
		this.setDate(0);
	}

	return this;
};

Date.prototype.subWeeks = function(value) {
	this.subDays(value * 7);

	return this;
};

Date.prototype.subYears = function(value) {
	var month = this.getMonth();
	this.setFullYear(this.getFullYear() - value);

	if (month < this.getMonth()) {
		this.setDate(0);
	}

	return this;
};

Date.prototype.format = function(format) {
	if (format === undefined)
		return this.toString();

	var formatSeparator = format.match(/[.\/\-\s].*?/);
	var formatParts     = format.split(/\W+/);
	var result          = '';

	for (var i=0; i<=formatParts.length; i++) {
		switch(formatParts[i]) {
			case 'd':
			case 'j':
			result += this.getDate() + formatSeparator;
			break;

			case 'dd':
			result += (this.getDate() < 10 ? '0' : '')+this.getDate() + formatSeparator;
			break;

			case 'm':
			result += (this.getMonth() + 1) + formatSeparator;
			break;

			case 'mm':
			result += (this.getMonth() < 9 ? '0' : '')+(this.getMonth() + 1) + formatSeparator;
			break;

			case 'yy':
			case 'y':
			result += this.getFullYear() + formatSeparator;
			break;

			case 'yyyy':
			case 'Y':
			result += this.getFullYear() + formatSeparator;
			break;
		}
	}

	return result.slice(0, -1);
}

function updatePickerFromInput() {
	datepickerStart.setStart($(".apmarketplace_from").val());
	datepickerStart.setEnd($(".apmarketplace_to").val());
	datepickerStart.update();
	datepickerEnd.setStart($(".apmarketplace_from").val());
	datepickerEnd.setEnd($(".apmarketplace_to").val());
	datepickerEnd.update();

	$('.apmarketplace_from').trigger('change');

	if ($('#datepicker-compare').attr("checked")) {
		if ($('#compare-options').val() == 1)
			setPreviousPeriod();

		if ($('#compare-options').val() == 2)
			setPreviousYear();

		datepickerStart.setStartCompare($(".apmarketplace_from-compare").val());
		datepickerStart.setEndCompare($(".apmarketplace_to-compare").val());
		datepickerEnd.setStartCompare($(".apmarketplace_from-compare").val());
		datepickerEnd.setEndCompare($(".apmarketplace_to-compare").val());
		datepickerStart.setCompare(true);
		datepickerEnd.setCompare(true);
	}
}

function setDayPeriod() {
	date = new Date();
	$(".apmarketplace_from").val(date.format($(".apmarketplace_from").data('date-format')));
	$(".apmarketplace_to").val(date.format($(".apmarketplace_to").data('date-format')));
	$('.apmarketplace_from').trigger('change');

	updatePickerFromInput();
	$('#datepicker-from-info').html($(".apmarketplace_from").val());
	$('#datepicker-to-info').html($(".apmarketplace_to").val());
	$('#preselectDateRange').val('day');
	$('button[name="submitDateRange"]').click();
}

function setPreviousDayPeriod() {
	date = new Date();
	date = date.subDays(1);
	$(".apmarketplace_from").val(date.format($(".apmarketplace_from").data('date-format')));
	$(".apmarketplace_to").val(date.format($(".apmarketplace_to").data('date-format')));
	$('.apmarketplace_from').trigger('change');

	updatePickerFromInput();
	$('#datepicker-from-info').html($(".apmarketplace_from").val());
	$('#datepicker-to-info').html($(".apmarketplace_to").val());
	$('#preselectDateRange').val('prev-day');
	$('button[name="submitDateRange"]').click();
}

function setMonthPeriod() {
	date = new Date();
	$(".apmarketplace_to").val(date.format($(".apmarketplace_to").data('date-format')));
	date = new Date(date.setDate(1));
	$(".apmarketplace_from").val(date.format($(".apmarketplace_from").data('date-format')));
	$('.apmarketplace_from').trigger('change');	

	updatePickerFromInput();
	$('#datepicker-from-info').html($(".apmarketplace_from").val());
	$('#datepicker-to-info').html($(".apmarketplace_to").val());
	$('#preselectDateRange').val('month');
	$('button[name="submitDateRange"]').click();
}

function setPreviousMonthPeriod() {
	date = new Date();
	date = new Date(date.getFullYear(), date.getMonth(), 0);
	$(".apmarketplace_to").val(date.format($(".apmarketplace_to").data('date-format')));
	date = new Date(date.setDate(1));
	$(".apmarketplace_from").val(date.format($(".apmarketplace_from").data('date-format')));
	$('.apmarketplace_from').trigger('change');	

	updatePickerFromInput();
	$('#datepicker-from-info').html($(".apmarketplace_from").val());
	$('#datepicker-to-info').html($(".apmarketplace_to").val());
	$('#preselectDateRange').val('prev-month');
	$('button[name="submitDateRange"]').click();
}

function setYearPeriod() {
	date = new Date();
	$(".apmarketplace_to").val(date.format($(".apmarketplace_to").data('date-format')));
	date = new Date(date.getFullYear(), 0, 1);
	$(".apmarketplace_from").val(date.format($(".apmarketplace_from").data('date-format')));	
	$('.apmarketplace_from').trigger('change');

	updatePickerFromInput();
	$('#datepicker-from-info').html($(".apmarketplace_from").val());
	$('#datepicker-to-info').html($(".apmarketplace_to").val());
	$('#preselectDateRange').val('year');
	$('button[name="submitDateRange"]').click();
}

function setPreviousYearPeriod() {
	date = new Date();
	date = new Date(date.getFullYear(), 11, 31);
	date = date.subYears(1);
	$(".apmarketplace_to").val(date.format($(".apmarketplace_to").data('date-format')));
	date = new Date(date.getFullYear(), 0, 1);
	$(".apmarketplace_from").val(date.format($(".apmarketplace_from").data('date-format')));
	$('.apmarketplace_from').trigger('change');

	updatePickerFromInput();
	$('#datepicker-from-info').html($(".apmarketplace_from").val());
	$('#datepicker-to-info').html($(".apmarketplace_to").val());
	$('#preselectDateRange').val('prev-year');
	$('button[name="submitDateRange"]').click();
}


function setPreviousPeriod() {
	startDate = Date.parseDate($(".apmarketplace_from").val(), $(".apmarketplace_from").data('date-format')).subDays(1);
	endDate = Date.parseDate($(".apmarketplace_to").val(), $(".apmarketplace_to").data('date-format')).subDays(1);

	diff = endDate - startDate;
	startDateCompare = new Date(startDate-diff);

	$(".apmarketplace_to-compare").val(startDate.format($(".apmarketplace_to-compare").data('date-format')));
	$(".apmarketplace_from-compare").val(startDateCompare.format($(".apmarketplace_from-compare").data('date-format')));
}

function setPreviousYear() {
	startDate = Date.parseDate($(".apmarketplace_from").val(), $(".apmarketplace_from").data('date-format')).subYears(1);
	endDate = Date.parseDate($(".apmarketplace_to").val(), $(".apmarketplace_to").data('date-format')).subYears(1);
	$(".apmarketplace_from-compare").val(startDate.format($(".apmarketplace_from").data('date-format')));
	$(".apmarketplace_to-compare").val(endDate.format($(".apmarketplace_from").data('date-format')));
}


$( document ).ready(function() {
	//Instanciate datepickers
	datepickerStart = $('.datepicker1').daterangepicker({
		"dates": translated_dates,
		"weekStart": 1,
		"start": $(".apmarketplace_from").val(),
		"end": $(".apmarketplace_to").val()
	}).on('changeDate', function(ev){
		if (ev.date.valueOf() >= datepickerEnd.date.valueOf()){
			datepickerEnd.setValue(ev.date.setMonth(ev.date.getMonth()+1));
		}
	}).data('daterangepicker');

	datepickerEnd = $('.datepicker2').daterangepicker({
		"dates": translated_dates,
		"weekStart": 1,
		"start": $(".apmarketplace_from").val(),
		"end": $(".apmarketplace_to").val()
	}).on('changeDate', function(ev){
		if (ev.date.valueOf() <= datepickerStart.date.valueOf()){
			datepickerStart.setValue(ev.date.setMonth(ev.date.getMonth()-1));
		}
	}).data('daterangepicker');
	console.log($(".apmarketplace_from").val());
	console.log(11111111111111);
	//Set first date picker to month -1 if same month
	startDate = Date.parseDate($(".apmarketplace_from").val(), $(".apmarketplace_from").data('date-format'));

	endDate = Date.parseDate($(".apmarketplace_to").val(), $(".apmarketplace_to").data('date-format'));

	if (startDate.getFullYear() == endDate.getFullYear() && startDate.getMonth() == endDate.getMonth())
		datepickerStart.setValue(startDate.subMonths(1));

	//Events binding
	$(".apmarketplace_from").focus(function() {
		datepickerStart.setCompare(false);
		datepickerEnd.setCompare(false);
		$(".date-input").removeClass("input-selected");
		$(this).addClass("input-selected");
	});

	$(".apmarketplace_to").focus(function() {
		datepickerStart.setCompare(false);
		datepickerEnd.setCompare(false);
		$(".date-input").removeClass("input-selected");
		$(this).addClass("input-selected");
	});

	$(".apmarketplace_from-compare").focus(function() {
		datepickerStart.setCompare(true);
		datepickerEnd.setCompare(true);
		$('#compare-options').val(3);
		$(".date-input").removeClass("input-selected");
		$(this).addClass("input-selected");
	});

	$(".apmarketplace_to-compare").focus(function() {
		datepickerStart.setCompare(true);
		datepickerEnd.setCompare(true);
		$('#compare-options').val(3);
		$(".date-input").removeClass("input-selected");
		$(this).addClass("input-selected");
	});
	
	$('#datepicker-cancel').click(function() {
		$('#datepicker').addClass('hide');
	});

	$('#datepicker').show(function() {
		$('.apmarketplace_from').focus();
		$('.apmarketplace_from').trigger('change');
	});

	$('#datepicker-compare').click(function() {
		if ($(this).attr("checked")) {
			$('#compare-options').trigger('change');
			$('#form-date-body-compare').show();
			$('#compare-options').prop('disabled', false);
		} else {
			datepickerStart.setStartCompare(null);
			datepickerStart.setEndCompare(null);
			datepickerEnd.setStartCompare(null);
			datepickerEnd.setEndCompare(null);
			$('#form-date-body-compare').hide();
			$('#compare-options').prop('disabled', true);
			$('.apmarketplace_from').focus();
		}
	})

	$('#compare-options').change(function() {
		if (this.value == 1)
			setPreviousPeriod();
			
		if (this.value == 2)
			setPreviousYear();

		datepickerStart.setStartCompare($(".apmarketplace_from-compare").val());
		datepickerStart.setEndCompare($(".apmarketplace_to-compare").val());
		datepickerEnd.setStartCompare($(".apmarketplace_from-compare").val());
		datepickerEnd.setEndCompare($(".apmarketplace_to-compare").val());
		datepickerStart.setCompare(true);
		datepickerEnd.setCompare(true);

		if (this.value == 3)
			$('.apmarketplace_from-compare').focus();
	});

	if ($('#datepicker-compare').attr("checked"))
	{
		if ($(".apmarketplace_from-compare").val().replace(/^\s+|\s+$/g, '').length == 0)
			$('#compare-options').trigger('change');

		datepickerStart.setStartCompare($(".apmarketplace_from-compare").val());
		datepickerStart.setEndCompare($(".apmarketplace_to-compare").val());
		datepickerEnd.setStartCompare($(".apmarketplace_from-compare").val());
		datepickerEnd.setEndCompare($(".apmarketplace_to-compare").val());
		datepickerStart.setCompare(true);
		datepickerEnd.setCompare(true);
	}

	$('#datepickerExpand').on('click',function() {
		if ($('#datepicker').hasClass('hide'))
		{
			$('#datepicker').removeClass('hide');
			$('.apmarketplace_from').focus();
		}
		else
			$('#datepicker').addClass('hide');
	});

	$('.submitDateDay').on('click',function(e){
		e.preventDefault;
		setDayPeriod();
	});
	$('.submitDateMonth').on('click',function(e){
		e.preventDefault;
		setMonthPeriod()
	});
	$('.submitDateYear').on('click',function(e){
		e.preventDefault;
		setYearPeriod();
	});
	$('.submitDateDayPrev').on('click',function(e){
		e.preventDefault;
		setPreviousDayPeriod();
	});
	$('.submitDateMonthPrev').on('click',function(e){
		e.preventDefault;
		setPreviousMonthPeriod();
	});
	$('.submitDateYearPrev').on('click',function(e){
		e.preventDefault;
		setPreviousYearPeriod();
	});
});
