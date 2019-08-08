{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
{extends file=$layout}

{block name='content'}
	<section id="main">
		<section id="content" class="page-content card card-block">
			<header class="page-header">
		          <h1>{l s='Log in to Vendor Account' mod='apmarketplace'}</h1>
		     </header>
			<section id="content">
				<section class="login-form">
					{if $check == 0}
						<div class="help-block">
							<ul>
								<li class="alert alert-danger">{l s='Authentication failed' mod='apmarketplace'}</li>
							</ul>
						</div>
					{/if}
					<form id="login-form" action="" method="POST">
						<section>
							<div class="form-group row">
								<label class="col-md-3 form-control-label required">{l s='User' mod='apmarketplace'}</label>
								<div class="col-md-6">
									<input class="form-control" type="text" name="user" required="" value="">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-3 form-control-label required">{l s='Password' mod='apmarketplace'}</label>
								<div class="col-md-6">
									<div class="input-group js-parent-focus">
										<input class="form-control js-child-focus js-visible-password" required="" name="password" value="" type="password">
										<span class="input-group-btn">
			              						<button class="btn" type="button" data-action="show-password" data-text-show="{l s='Show' mod='apmarketplace'}" data-text-hide="{l s='Hide' mod='apmarketplace'}">
			              							{l s='Show' mod='apmarketplace'}
			              						</button>
			            					</span>
									</div>
								</div>
							</div>
						</section>

						<footer class="form-footer text-sm-center clearfix">
			        			<input name="submitLogin" value="1" type="hidden">
					          <button id="submit-login" class="btn btn-primary" name="submit_login" data-link-action="sign-in" type="submit">
					          	{l s='Sign in' mod='apmarketplace'}
					          </button>
		      			</footer>
					</form>
				</section>
				<hr>
				<div class="no-account">
		        		<a href="{$baseurl}vendorlogin?create_account=1" data-link-action="display-register-form">
		        			{l s='No account? Create one here' mod='apmarketplace'}
		        		</a>
		      	</div>
			</section>
		</section>
	</section>
{/block}
