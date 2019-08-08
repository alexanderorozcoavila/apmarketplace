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
		          <h1>{l s='Create an account vendor' mod='apmarketplace'}</h1>
		     </header>
		     <section class="register-form">
		     	{if $exit == 1}
					<div class="help-block">
						<ul>
							<li class="alert alert-danger">{l s='Account already exists !!' mod='apmarketplace'}</li>
						</ul>
					</div>
				{/if}
		     	<p>{l s='Already have an account vendor' mod='apmarketplace'}? <a href="{$baseurl}vendorlogin?login=1">{l s='Log in instead' mod='apmarketplace'}!</a></p>
		     	<form id="customer-form" class="js-customer-form" action="" method="POST" enctype="multipart/form-data">
		     		<section>
		     			<div class="form-group row">
		     				<label class="col-md-3 form-control-label required">{l s='Username' mod='apmarketplace'}</label>
		     				<div class="col-md-6">
		     					<input class="form-control" name="user_name" value="" required="" type="text">
		     				</div>
		     				{if isset($error) && $error == 1}
			     				<div class="col-md-6" style="margin-top:15px;">
			     					<p class="alert alert-danger">{l s='Invalid username address' mod='apmarketplace'}</p>
			     				</div>
			     			{/if}
		     			</div>

						<div class="form-group row">
							<label class="col-md-3 form-control-label required">{l s='Password' mod='apmarketplace'}</label>
							<div class="col-md-6">
								<div class="input-group js-parent-focus">
									<input class="form-control js-child-focus js-visible-password" required="" name="pass_word" value="{if isset($pass_word)}{$pass_word}{/if}" type="password">
									<span class="input-group-btn">
										<button class="btn" type="button" data-action="show-password" data-text-show="{l s='Show' mod='apmarketplace'}" data-text-hide="{l s='Hide' mod='apmarketplace'}">
											{l s='Show' mod='apmarketplace'}
										</button>
									</span>
								</div>
							</div>
						</div>

						<div class="form-group row ">
						    <label class="col-md-3 form-control-label required">{l s='First Name' mod='apmarketplace'}</label>
						    <div class="col-md-6">
						    		<input class="form-control" name="first_name" value="{if isset($first_name)}{$first_name}{/if}" required="" type="text">
						    </div>
						</div>

						<div class="form-group row ">
						    <label class="col-md-3 form-control-label required">{l s='Last Name' mod='apmarketplace'}</label>
						    <div class="col-md-6">
						    		<input class="form-control" name="last_name" value="{if isset($last_name)}{$last_name}{/if}" required="" type="text">
						    </div>
						</div>

						<div class="form-group row ">
						    <label class="col-md-3 form-control-label required">{l s='Email' mod='apmarketplace'}</label>
						    <div class="col-md-6">
						    		<input class="form-control" name="email" value="{if isset($email)}{$email}{/if}" required="" type="text">
						    </div>
						</div>

						<div class="form-group row ">
						    <label class="col-md-3 form-control-label required">{l s='Logo Image' mod='apmarketplace'}</label>
						    <div class="col-md-6">
						    		<input class="form-control" name="leo_image" value="" required="" type="file">
						    </div>
						</div>

						<div class="form-group row ">
						    <label class="col-md-3 form-control-label required">{l s='Phone' mod='apmarketplace'}</label>
						    <div class="col-md-6">
						    	<input class="form-control" name="phone" value="{if isset($phone)}{$phone}{/if}" required="" type="number">
						    </div>
						</div>
						
						<div class="form-group row ">
						    <label class="col-md-3 form-control-label">{l s='Fax' mod='apmarketplace'}</label>
						    <div class="col-md-6">
						    		<input class="form-control" name="fax" value="{if isset($fax)}{$fax}{/if}" type="text">
						    </div>
						    <div class="col-md-3 form-control-comment">{l s='Optional' mod='apmarketplace'}</div>
						</div>

						<div class="form-group row ">
						    <label class="col-md-3 form-control-label">{l s='Facebook' mod='apmarketplace'}</label>
						    <div class="col-md-6">
						    		<input class="form-control" name="fb" value="{if isset($fb)}{$fb}{/if}" type="text">
						    </div>
						    <div class="col-md-3 form-control-comment">{l s='Optional' mod='apmarketplace'}</div>
						</div>

						<div class="form-group row ">
						    <label class="col-md-3 form-control-label">{l s='Twitter' mod='apmarketplace'}</label>
						    <div class="col-md-6">
						    		<input class="form-control" name="tt" value="{if isset($tt)}{$tt}{/if}" type="text">
						    </div>
						    <div class="col-md-3 form-control-comment">{l s='Optional' mod='apmarketplace'}</div>
						</div>

						<div class="form-group row ">
						    <label class="col-md-3 form-control-label">{l s='Instagram' mod='apmarketplace'}</label>
						    <div class="col-md-6">
						    		<input class="form-control" name="ins" value="{if isset($ins)}{$ins}{/if}" type="text">
						    </div>
						    <div class="col-md-3 form-control-comment">{l s='Optional' mod='apmarketplace'}</div>
						</div>

						<div class="form-group row ">
						    <label class="col-md-3 form-control-label">{l s='Shop Url' mod='apmarketplace'}</label>
						    <div class="col-md-6">
						    		<input class="form-control" name="url_shop" value="{if isset($url_shop)}{$url_shop}{/if}" type="text">
						    </div>
						    <div class="col-md-3 form-control-comment">{l s='Optional' mod='apmarketplace'}</div>
						</div>
					</section>

					<footer class="form-footer text-sm-center clearfix">
						<input name="submitLogin" value="1" type="hidden">
						<button id="submit-login" name="submit_login" class="btn btn-primary" data-link-action="sign-in" type="submit">
							{l s='Save' mod='apmarketplace'}
						</button>
					</footer>

		     	</form>
		     </section>
		</section>
	</section>
{/block}
