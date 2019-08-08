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

{extends file="helpers/form/form.tpl"}
{block name="field"}
	{if $input.type == 'question'}
		<div class="form-group">
			<h3 class="title-question">{l s='Question:' mod='apmarketplace'}</h3>
			<span class="content-question">{$input.questions.content}</span>
			<span class="content-by">{l s='By' mod='apmarketplace'} {$input.questions.customer}</span>
			<span class="date-time">{$input.questions.date_ques}</span>
		</div>
		<div class="form-group">
			<h3 class="title-question">{l s='Answer:' mod='apmarketplace'}</h3>
			{if !empty($input.answers)}
				<ul class="leo-question">
					{foreach from=$input.answers item=answer}
						<li class="answer-item">
							<span>{$answer.answer}</span>
							<span>{l s='By:' mod='apmarketplace'} {$answer.customer}</span>
							<span>{$input.questions.date_ques}</span>
						</li>
					{/foreach}
				</ul>
			{/if}
		</div>
		<div class="form-group">
			<h3 class="title-question">{l s='Reply:' mod='apmarketplace'}</h3>
			<div class="col-lg-9">
				<textarea class="answer textarea-autosize" name="answer">
					
				</textarea>
			</div>
		</div>
	{else}
		{$smarty.block.parent}	
	{/if}
{/block}