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
<div class="row">
	<div class="col-lg-6">
		<h3>{l s='Review' mod='apmarketplace'}</h3>
		<div class="leo-product-show-review-title">
			<div id="product_reviews_block_tab">
				{if $reviews}
					{foreach from=$reviews item=review}
						{if $review.content}
							<div class="review" itemprop="review" itemscope itemtype="https://schema.org/Review">
								<div class="review-info row">
									<div class="review_author col-sm-3">
										<span>{l s='Grade' mod='apmarketplace'}&nbsp;</span>
										<div class="star_content clearfix"  itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
											{section name="i" start=0 loop=5 step=1}
											{if $review.grade le $smarty.section.i.index}
											<div class="star"></div>
											{else}
											<div class="star star_on"></div>
											{/if}
											{/section}
											<meta itemprop="worstRating" content = "0" />
											<meta itemprop="ratingValue" content = "{$review.grade|escape:'html':'UTF-8'}" />
											<meta itemprop="bestRating" content = "5" />
										</div>
										<div class="review_author_infos">
											<strong itemprop="author">{$review.customer_name|escape:'html':'UTF-8'}</strong>
											<meta itemprop="datePublished" content="{$review.date_add|escape:'html':'UTF-8'|substr:0:10}" />
											<em>{$review.date_add|escape:'html':'UTF-8'}</em>
										</div>
									</div>

									<div class="review_details col-sm-9">
										<p itemprop="name" class="title_block">
											<strong>{$review.title}</strong>
										</p>
										<p itemprop="reviewBody">{$review.content|escape:'html':'UTF-8'|nl2br nofilter}</p>

									</div><!-- .review_details -->
								</div>
							</div> <!-- .review -->
						{/if}
					{/foreach}
					{if $customer.is_logged}
						<a class="open-review-form" href="javascript:void(0)" data-id-product="{$id_product_tab_content}" data-is-logged="{$customer.is_logged}" data-product-link="{$link_product_tab_content}">
							<i class="material-icons">&#xE150;</i>
							{l s='Write a review' mod='apmarketplace'}
						</a>
					{/if}
				{/if}
			</div>
		</div> 
	</div>
	<div class="col-lg-6">
		<h3>{l s='Question' mod='apmarketplace'}</h3>
		<div class="leo-product-question-answer">
			{if $customer.is_logged}
				<div class="leo-question">
					<textarea class="content_question" value="" name="content_question" placeholder="{l s='Your Question For This Product' mod='apmarketplace'}"></textarea>
					<div class="submit-question">
						<button class="btn btn-primary send-question" data-customer={$id_customer} data-product="{$id_product_tab_content}">
							{l s='Send' mod='apmarketplace'}
						</button>
					</div>
				</div>
			{else}
				<div class="leo-question">
					<a href="javascript:void(0)" class="leo-text">{l s='You must login to write question for this product' mod='apmarketplace'}</a>
				</div>	
			{/if}

			{if !empty(questions)}
				<ul class="list-question">
					{foreach from=$questions item=question}
						<li class="item-question">
							<a href="javascript:void(0)" class="title">{$question.customer_name}</a>
							<div class="question">
								{$question.content}
							</div>
							<div class="actionuser">
								<a href="javascript:void(0)" class="time">{$question.date_ques}</a>
								{if $customer.is_logged}
									<a href="javascript:void(0)" class="leo_reply">{l s='Reply' mod='apmarketplace'}</a>
								{/if}
							</div>
							<div class="answer">
								<ul>
									{if !empty($question.answers)}
										{foreach from=$question.answers item=answer}
											<li class="item-answer">
												<a href="javascript:void(0)" class="title">{$answer.customer}</a>
												<div class="answer">{$answer.answer}</div>
												<div class="actionuser">
													<a href="javascript:void(0)" class="time">{$answer.date_ans}</a>
													{if $customer.is_logged}
														<a href="javascript:void(0)" class="leo_reply answer">{l s='Reply' mod='apmarketplace'}</a>
													{/if}
												</div>
											</li>
										{/foreach}
									{/if}
								</ul>
								{if $customer.is_logged}
									<div class="leo-answer" style="display:none;">
										<textarea class="content_answer" value
										="" name="content_answer" placeholder="{l s='Your Question For This Product' mod='apmarketplace'}"></textarea>
										<div class="submit-question">
											<button class="btn btn-primary send-answer" data-product="{$id_product_tab_content}" data-customer={$id_customer} data-question="{$question.id_apmarketplace_question}">
												{l s='Send' mod='apmarketplace'}
											</button>
										</div>
									</div>
								{/if}
							</div>
						</li>
					{/foreach}
				</ul>
			{/if}
		</div>
	</div>
</div>
