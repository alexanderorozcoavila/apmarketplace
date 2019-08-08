{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
{extends file="module:apmarketplace/views/templates/front/layout/layout-sidebar.tpl"}

{block name='right-column'}	
	<section class="register-form">
		<form id="customer-form" class="js-customer-form" action="" method="POST" enctype="multipart/form-data">
			<section>
				<div class="form-group row">
					<label class="col-md-3 form-control-label required">{l s='First Name' mod='apmarketplace'}</label>
					<div class="col-md-6">
						<input class="form-control" name="first_name" value="{$vendor->first_name}" required="" type="text">
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3 form-control-label required">{l s='Last Name' mod='apmarketplace'}</label>
					<div class="col-md-6">
						<input class="form-control" name="last_name" value="{$vendor->last_name}" required="" type="text">
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3 form-control-label required">{l s='Email' mod='apmarketplace'}</label>
					<div class="col-md-6">
						<input class="form-control" name="email" value="{$vendor->email}" required="" type="text">
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3 form-control-label required">{l s='Logo' mod='apmarketplace'}</label>
					<div class="col-md-6">
						{if $vendor->image != ''}
							<img class="img img-thumbnail" style="max-width: 100px;margin-bottom: 10px;" src="{$baseurl}modules/apmarketplace/views/img/vendor/{$vendor->image}">
						{/if}
						<input class="form-control" name="leo_image" value="" required="" type="file">
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3 form-control-label required">{l s='Phone' mod='apmarketplace'}</label>
					<div class="col-md-6">
						<input class="form-control" name="phone" value="{$vendor->phone}" required="" type="text">
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3 form-control-label">{l s='Fax' mod='apmarketplace'}</label>
					<div class="col-md-6">
						<input class="form-control" name="fax" value="{$vendor->fax}" type="text">
					</div>
					<div class="col-md-3 form-control-comment">{l s='Optional' mod='apmarketplace'}</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3 form-control-label">{l s='Facebook' mod='apmarketplace'}</label>
					<div class="col-md-6">
						<input class="form-control" name="fb" value="{$vendor->fb}" type="text">
					</div>
					<div class="col-md-3 form-control-comment">{l s='Optional' mod='apmarketplace'}</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3 form-control-label">{l s='Twitter' mod='apmarketplace'}</label>
					<div class="col-md-6">
						<input class="form-control" name="tt" value="{$vendor->tt}" type="text">
					</div>
					<div class="col-md-3 form-control-comment">{l s='Optional' mod='apmarketplace'}</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3 form-control-label">{l s='Instagram' mod='apmarketplace'}</label>
					<div class="col-md-6">
						<input class="form-control" name="ins" value="{$vendor->ins}" type="text">
					</div>
					<div class="col-md-3 form-control-comment">{l s='Optional' mod='apmarketplace'}</div>
				</div>


				<div class="form-group row">
					<label class="col-md-3 form-control-label">{l s='Shop Url' mod='apmarketplace'}</label>
					<div class="col-md-6">
						<input class="form-control" name="url_shop" value="{$vendor->url_shop}" type="text">
					</div>
					<div class="col-md-3 form-control-comment">{l s='Optional' mod='apmarketplace'}</div>
				</div>
			</section>

			<footer class="form-footer text-sm-center clearfix">
				<input name="submitLogin" value="1" type="hidden">
				<button id="submit-login" name="submit_edit" class="btn btn-primary" data-link-action="sign-in" type="submit">
					{l s='Save' mod='apmarketplace'}
				</button>
			</footer>
		</form>
	</section>
{/block}
