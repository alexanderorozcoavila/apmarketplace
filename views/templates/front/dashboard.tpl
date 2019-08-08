{* 
* @Module Name: AP Market Place
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: Apmarketplace is module help you can build content for your shop
*}
{extends file="module:apmarketplace/views/templates/front/layout/layout-sidebar.tpl"}

{block name='right-column'}
	<div class="row">
		<div class="col-lg-12">
			<div id="calendar" class="panel row">
				<form action="" method="get" id="calendar_form" name="calendar_form" class="form-inline">
					<div class="col-lg-12 datepicker1" id="datepicker1">
						<label>
							{l s="From" mod='apmarketplace'}
							<input id="date-start" class="datepicker form-control datepicker1 hasDatepicker apmarketplace_from" autocomplete="off" type="text" name="apmarketplace_from" data-date="{if isset($firstday)}{$firstday}{/if}" data-date-format="Y-mm-dd" value="{if isset($firstday)}{$firstday}{/if}">
						</label>
						<label>
							{l s="To" mod='apmarketplace'}
							<input id="date-end" class="datepicker form-control datepicker2 hasDatepicker apmarketplace_to" autocomplete="off" type="text" name="apmarketplace_to" data-date="{if isset($lastday)}{$lastday}{/if}" data-date-format="Y-mm-dd" value="{if isset($lastday)}{$lastday}{/if}">
						</label>
						<button class="btn btn btn-primary" type="submit" name="apply">
							<span><i class="material-icons">&#xE8b6;</i>{l s="Apply" mod='apmarketplace'}</span>
						</button>										
					</div>
				</form>	
			</div>	
		</div>
		<div class="col-lg-12">
			{include file="module:apmarketplace/views/templates/front/dashtrends.tpl"}
		</div>
	</div>
	<script>
		var currency_format = {$leo_currency->format|floatval};
		var currency_sign = '{$leo_currency->sign|@addcslashes:'\''}';
		var currency_blank = {$leo_currency->blank|intval};
		var 	translated_dates = {
			days: ['{l s='Sunday' js=1 mod='apmarketplace'}', '{l s='Monday' js=1 mod='apmarketplace'}', '{l s='Tuesday' js=1 mod='apmarketplace'}', '{l s='Wednesday' js=1 mod='apmarketplace'}', '{l s='Thursday' js=1 mod='apmarketplace'}', '{l s='Friday' js=1 mod='apmarketplace'}', '{l s='Saturday' js=1 mod='apmarketplace'}', '{l s='Sunday' js=1 mod='apmarketplace'}'],
			daysShort: ['{l s='Sun' js=1 mod='apmarketplace'}', '{l s='Mon' js=1 mod='apmarketplace'}', '{l s='Tue' js=1 mod='apmarketplace'}', '{l s='Wed' js=1 mod='apmarketplace'}', '{l s='Thu' js=1 mod='apmarketplace'}', '{l s='Fri' js=1 mod='apmarketplace'}', '{l s='Sat' js=1 mod='apmarketplace'}', '{l s='Sun' js=1 mod='apmarketplace'}'],
			daysMin: ['{l s='Su' js=1 mod='apmarketplace'}', '{l s='Mo' js=1 mod='apmarketplace'}', '{l s='Tu' js=1 mod='apmarketplace'}', '{l s='We' js=1 mod='apmarketplace'}', '{l s='Th' js=1 mod='apmarketplace'}', '{l s='Fr' js=1 mod='apmarketplace'}', '{l s='Sa' js=1 mod='apmarketplace'}', '{l s='Su' js=1 mod='apmarketplace'}'],
			months: ['{l s='January' js=1 mod='apmarketplace'}', '{l s='February' js=1 mod='apmarketplace'}', '{l s='March' js=1 mod='apmarketplace'}', '{l s='April' js=1 mod='apmarketplace'}', '{l s='May' js=1 mod='apmarketplace'}', '{l s='June' js=1 mod='apmarketplace'}', '{l s='July' js=1 mod='apmarketplace'}', '{l s='August' js=1 mod='apmarketplace'}', '{l s='September' js=1 mod='apmarketplace'}', '{l s='October' js=1 mod='apmarketplace'}', '{l s='November' js=1 mod='apmarketplace'}', '{l s='December' js=1 mod='apmarketplace'}'],
			monthsShort: ['{l s='Jan' js=1 mod='apmarketplace'}', '{l s='Feb' js=1 mod='apmarketplace'}', '{l s='Mar' js=1 mod='apmarketplace'}', '{l s='Apr' js=1 mod='apmarketplace'}', '{l s='May ' js=1 mod='apmarketplace'}', '{l s='Jun' js=1 mod='apmarketplace'}', '{l s='Jul' js=1 mod='apmarketplace'}', '{l s='Aug' js=1 mod='apmarketplace'}', '{l s='Sep' js=1 mod='apmarketplace'}', '{l s='Oct' js=1 mod='apmarketplace'}', '{l s='Nov' js=1 mod='apmarketplace'}', '{l s='Dec' js=1 mod='apmarketplace'}']
		};
		var priceDisplayPrecision = 0;
	</script>
{/block}

