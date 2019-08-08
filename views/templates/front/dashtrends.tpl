{* 
* @Module Name: AP Market Place
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: Apmarketplace is module help you can build content for your shop
*}
<div id="dashboard">
	<section id="dashtrends" class="leo-panel widget">
		<header class="panel-heading">
			<i class="material-icons">reorder</i> <span>{l s='Dashboard' d='apmarketplace'}</span>
		</header>
		<div id="dashtrends_toolbar" class="row">
			<dl onclick="selectDashtrendsChart(this, 'sales');" class="col-xs-4 col-lg-2 label-tooltip" data-toggle="tooltip" data-placement="bottom">
				<dt>{l s='Sales' d='apmarketplace'}</dt>
				<dd class="data_value size_l"><span id="sales_score"></span></dd>
				<dd class="dash_trend"><span id="sales_score_trends"></span></dd>
			</dl>
			<dl onclick="selectDashtrendsChart(this, 'orders');" class="col-xs-4 col-lg-2 label-tooltip" data-toggle="tooltip" data-placement="bottom">
				<dt>{l s='Orders' d='apmarketplace'}</dt>
				<dd class="data_value size_l"><span id="orders_score"></span></dd>
				<dd class="dash_trend"><span id="orders_score_trends"></span></dd>
			</dl>
			<dl onclick="selectDashtrendsChart(this, 'average_cart_value');" class="col-xs-4 col-lg-2 label-tooltip" data-toggle="tooltip" data-placement="bottom">
				<dt>{l s='Cart Value' d='apmarketplace'}</dt>
				<dd class="data_value size_l"><span id="cart_value_score"></span></dd>
				<dd class="dash_trend"><span id="cart_value_score_trends"></span></dd>
			</dl>
			<dl onclick="selectDashtrendsChart(this, 'visits');" class="col-xs-4 col-lg-2 label-tooltip" data-toggle="tooltip" data-placement="bottom">
				<dt>{l s='Visits' d='apmarketplace'}</dt>
				<dd class="data_value size_l"><span id="visits_score"></span></dd>
				<dd class="dash_trend"><span id="visits_score_trends"></span></dd>
			</dl>
			<dl onclick="selectDashtrendsChart(this, 'conversion_rate');" class="col-xs-4 col-lg-2 label-tooltip" data-toggle="tooltip" data-placement="bottom">
				<dt>{l s='Conversion Rate' d='apmarketplace'}</dt>
				<dd class="data_value size_l"><span id="conversion_rate_score"></span></dd>
				<dd class="dash_trend"><span id="conversion_rate_score_trends"></span></dd>
			</dl>
			<dl onclick="selectDashtrendsChart(this, 'net_profits');" class="col-xs-4 col-lg-2 label-tooltip" data-toggle="tooltip" data-placement="bottom">
				<dt>{l s='Net Profit' d='apmarketplace'}</dt>
				<dd class="data_value size_l"><span id="net_profits_score"></span></dd>
				<dd class="dash_trend"><span id="net_profits_score_trends"></span></dd>
			</dl>
		</div>

		<div id="dash_trends_chart1" class="chart with-transitions">
			<svg></svg>
		</div>	
	</section>
</div>
