{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
{extends file=$layout}

{block name='content'}
	{if $check == 0}
		<p class="alert alert-success">{l s='Your account not active. Please contact for us to active account' mod='apmarketplace'}</p>
	{/if}
	{if $check == 1}
		<form id="product_catalog_list" name="product_catalog_list" method="GET" action="">
			<section id="wrapper">
				<div class="container">
					<div class="row">
						{include file="module:apmarketplace/views/templates/front/left.tpl"}
						<div id="content-wrapper" class="right-column col-xs-12 col-sm-8 col-md-9">
							<div class="row">
								{if !empty($reviews)}
									<div class="panel col-lg-12">
										<h3>{l s='Reviews Waiting Approved' mod='apmarketplace'}</h3>
										<div class="table-responsive-row clearfix">
											<table class="table-apmarketplace_order">
												<thead>
													<tr class="nodrag nodrop">
														<td>{l s='ID' mod='apmarketplace'}</td>
														<td>{l s='Review title' mod='apmarketplace'}</td>
														<td>{l s='Review' mod='apmarketplace'}</td>
														<td>{l s='Rating' mod='apmarketplace'}</td>
														<td>{l s='Author' mod='apmarketplace'}</td>
														<td>{l s='Product' mod='apmarketplace'}</td>
														<td>{l s='Time of publication' mod='apmarketplace'}</td>
													</tr>
												</thead>
												<tbody>
													{foreach from=$reviews item=review}
														<tr class="odd">
															<td>{$review.id_review}</td>
															<td>{$review.title}</td>
															<td>{$review.content}</td>
															<td>{$review.grade}{l s='/5' mod='apmarketplace'}</td>
															<td>{$review.customer_name}</td>
															<td>{$review.name}</td>
															<td>{$review.date_add}</td>
														</tr>
													{/foreach}
												</tbody>
											</table>
										</div>
									</div>
								{/if}
								{if !empty($review_accepts)}
									<div class="panel col-lg-12">
										<h3>{l s='Approved Reviews' mod='apmarketplace'}</h3>
										<div class="table-responsive-row clearfix">
											<table class="table-apmarketplace_order">
												<thead>
													<tr class="nodrag nodrop">
														<td>{l s='ID' mod='apmarketplace'}</td>
														<td>{l s='Review title' mod='apmarketplace'}</td>
														<td>{l s='Review' mod='apmarketplace'}</td>
														<td>{l s='Rating' mod='apmarketplace'}</td>
														<td>{l s='Author' mod='apmarketplace'}</td>
														<td>{l s='Product' mod='apmarketplace'}</td>
														<td>{l s='Time of publication' mod='apmarketplace'}</td>
													</tr>
												</thead>
												<tbody>
													{foreach from=$review_accepts item=review_accept}
														<tr class="odd">
															<td>{$review_accept.id_review}</td>
															<td>{$review_accept.title}</td>
															<td>{$review_accept.content}</td>
															<td>{$review_accept.grade}{l s='/5' mod='apmarketplace'}</td>
															<td>{$review_accept.customer_name}</td>
															<td>{$review_accept.name}</td>
															<td>{$review_accept.date_add}</td>
														</tr>
													{/foreach}
												</tbody>
											</table>
										</div>
									</div>
								{/if}
							</div>		
						</div>
					</div>
				</div>
			</section>
		</form>
	{/if}					
{/block}
