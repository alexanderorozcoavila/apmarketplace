{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}

<div class="table-responsive leo-table">
	<table class="table history-status row-margin-bottom">
		<tbody>
			{if !empty($history)}
				{foreach from=$history item=row key=key}
					{if ($key == 0)}
						<tr>
							<td style="background-color:{$row['color']}">
								<img src="{$baseurl}img/os/{$row['id_order_state']|intval}.gif" width="16" height="16" alt="{$row['ostate_name']|stripslashes}" />
							</td>
							<td style="background-color:{$row['color']};color:{$row['text-color']}">
								{$row['ostate_name']|stripslashes}
							</td>
							<td style="background-color:{$row['color']};color:{$row['text-color']}">
								{if $row['employee_lastname']}{$row['employee_firstname']|stripslashes} {$row['employee_lastname']|stripslashes}{/if}
							</td>
							<td style="background-color:{$row['color']};color:{$row['text-color']}">
								{dateFormat date=$row['date_add'] full=true}
							</td>
						</tr>
					{else}
						<tr>
							<td>
								<img src="{$baseurl}/img/os/{$row['id_order_state']|intval}.gif" width="16" height="16" />
							</td>
							<td>{$row['ostate_name']|stripslashes}</td>
							<td>
								{if $row['employee_lastname']}{$row['employee_firstname']|stripslashes} {$row['employee_lastname']|stripslashes}{else}&nbsp;{/if}
							</td>
							<td>{dateFormat date=$row['date_add'] full=true}</td>
						</tr>
					{/if}
				{/foreach}
			{/if}
		</tbody>
	</table>
</div>

<form class="form-horizontal well hidden-print" action="" method="POST">
	<div class="input-group leo-input-group">
		<div class="input-select">
			<select id="id_order_state" class="chosen form-control" name="id_order_state">
				{foreach from=$states item=state}
					<option value="{$state['id_order_state']|intval}"{if isset($currentState) && $state['id_order_state'] == $currentState->id} selected="selected" disabled="disabled"{/if}>{$state['name']|escape}</option>
				{/foreach}
			</select>
		</div>
		<div class="input-button">
			<button type="submit" name="submitState" id="submit_state" class="btn btn-primary">{l s='Update status' mod='apmarketplace'}</button>
		</div>
	</div>
</form>