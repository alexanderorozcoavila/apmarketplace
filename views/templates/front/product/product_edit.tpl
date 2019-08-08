{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
{extends file="module:apmarketplace/views/templates/front/layout/layout-full-width.tpl"}

{block name='right-column'}
	<form id="product_catalog_add" name="product_catalog_add" action="" method="POST" enctype="multipart/form-data">
		{if isset($notification)}
			<p class="notification alert alert-success">{$notification nofilter}</p>
		{/if}
		{if isset($permission) && $permission == 0}
			<p class="alert alert-danger">{l s='You can not edit this product' mod='apmarketplace'}</p>
		{else}
			<div class="row">
				<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
					<div class="form-group row">
						<div class="leo-lang col-md-3 col-sm-4 col-xs-6 col-sp-12">
							<select class="select-lang form-control">
								{foreach from=$langs item=lang}
									<option value="{$lang.id_lang}">{$lang.name}</option>
								{/foreach}
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 col-sm-3 col-xs-12 form-control-label text-xs-left required">{l s='Product Name' mod='apmarketplace'}</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							{foreach from=$langs item=lang}
								<input {if $lang.id_lang != $id_lang}style="display:none;"{/if} class="lang-lang form-control lang_{$lang.id_lang}" type="text" name="product_name[{$lang.id_lang}]" value="{if isset($product->name[$lang.id_lang])}{$product->name[$lang.id_lang] nofilter}{/if}">
							{/foreach}
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 col-sm-3 col-xs-12 form-control-label text-xs-left required">{l s='Product Image' mod='apmarketplace'}</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<div class="leo-product-image">
								<div class="image-product">
									{if !empty($product->arr_image)}
										<div class="row">
											{foreach from=$product->arr_image item=image}
												<div class="col-md-3 col-sm-4 col-xs-6 col-sp-12">
													<div class="image-item{if isset($image.cover) && $image.cover == 1} active{/if}">
														<img class="imgm img-thumbnail" src="{$image.src}">
														<span class="delete-image" data-image={$image.id_image} data-id_product="{$id_product}" data-text="{l s='Delete Now' mod='apmarketplace'}" data-alert="{l s='You can not delete this Image' mod='apmarketplace'}">
															<i class="material-icons">&#xE14c;</i>
														</span>
														<div class="form-group row">
															<label class="">
																{l s='Cover image' mod='apmarketplace'}
															</label>
															<input type="radio" {if isset($image.cover) && $image.cover == 1}checked="checked"{/if} name="image_cover" class="image_cover" value="{$image.id_image}">
														</div>
													</div>
												</div>
											{/foreach}
										</div>
									{/if}
								</div>
								<div class="file_templates" style="display:none;">
									<div class="row row-image">
										<div class="col-md-10 col-sm-10 col-xs-10">
											<input type="file" name="leo_media" class="leo_image form-control">
										</div>
										<div class="col-md-2 col-sm-2 col-xs-2">
											<a href="javascript:void(0);" class="delete"><i class="material-icons">&#xE14c;</i></a>
										</div>
									</div>
								</div>
								<div class="list-image">
									<div class="row row-image">
										<div class="col-md-10 col-sm-10 col-xs-10">
											<input type="file" name="leo_media1" class="leo_image form-control">
										</div>
										<div class="col-md-2 col-sm-2 col-xs-2"></div>
									</div>
								</div>
								<a href="javascript:void(0);" class="btn btn-primary btn-sm add_image"><i class="material-icons">&#xE145;</i> {l s='Add more image' mod='apmarketplace'}</a>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-12 col-sm-12 col-xs-12 form-control-label text-xs-left required">
							{l s='Summary' mod='apmarketplace'}
						</label>
						<div class="col-md-12 col-sm-12 col-xs-12">
							{foreach from=$langs item=lang}
								<div {if $lang.id_lang != $id_lang}style="display:none;"{/if} class="lang-lang lang_{$lang.id_lang}">
									<textarea class="form-control" name="product_short[{$lang.id_lang}]" rows="5">
										{if isset($product->description_short[$lang.id_lang])}
											{$product->description_short[$lang.id_lang] nofilter}
										{/if}
									</textarea>
								</div>
							{/foreach}	
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-12 col-sm-12 col-xs-12 form-control-label text-xs-left required">
							{l s='Description' mod='apmarketplace'}
						</label>
						<div class="col-md-12 col-sm-12 col-xs-12">
							{foreach from=$langs item=lang}
								<div {if $lang.id_lang != $id_lang}style="display:none;"{/if} class="lang-lang lang_{$lang.id_lang}">
									<textarea class="form-control" name="product_des[{$lang.id_lang}]" rows="5">
										{if isset($product->description[$lang.id_lang])}
											{$product->description[$lang.id_lang] nofilter}
										{/if}
									</textarea>
								</div>	
							{/foreach}
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
					<div class="form-group row">
						<label class="col-md-12 col-sm-12 col-xs-12 text-xs-left text-md-right form-control-label">
							{l s='Quantity' mod='apmarketplace'}
						</label>
						<div class="col-md-12 col-sm-12 col-xs-12">
							<input type="text" name="product_quantity" class="form-control" required="" value="{if isset($product->quantity)}{$product->quantity}{/if}">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-12 col-sm-12 col-xs-12 text-xs-left text-md-right form-control-label">
							{l s='Reference' mod='apmarketplace'}
						</label>
						<div class="col-md-12 col-sm-12 col-xs-12">
							<input type="text" name="product_reference" class="form-control" required="" value="{if isset($product->reference)}{$product->reference}{/if}">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-12 col-sm-12 col-xs-12 text-xs-left text-md-right form-control-label">
							{l s='Price' mod='apmarketplace'}
						</label>
						<div class="col-md-12 col-sm-12 col-xs-12">
							<input type="text" name="product_price" class="form-control" required="" value="{if isset($product->price)}{$product->price}{/if}">
						</div>
					</div>
					<div class="form-group row">
						{function name="categories" nodes=[] depth=0}
						{strip}
						{if $nodes|count}
						<ul class="category-sub-menu">
							{foreach from=$nodes item=node}
								<li data-depth="{$depth}" class="{if $node.children}has-child{/if}">
									{if $depth===0}
										<div class="leo-categories clearfix">
											{if $node.children}
												<span class="arrows collapse-icons" data-toggle="collapse" data-target="#exCollapsingNavbar{$node.id}">
													<i class="material-icons add">&#xE315;</i>
													<i class="material-icons remove">&#xE313;</i>
												</span>
											{/if}
											<input class="categories_id category_box" id="categories_{$node.id}" type="checkbox" name="categories[]" value="{$node.id}" {if isset($node.check) && $node.check == 1}checked="checked"{/if}>
											<span class="category-name" data-toggle="collapse" href="#exCollapsingNavbar{$node.id}" aria-expanded="false" aria-controls="exCollapsingNavbar{$node.id}">{$node.name}</span>
											<input class="categories_id category_radio" type="radio" name="category" value="{$node.id}">
										</div>
										{if $node.children}
											<div class="collapse" id="exCollapsingNavbar{$node.id}">
												{categories nodes=$node.children depth=$depth+1}
											</div>
										{/if}
									{else}
										<div class="leo-categories clearfix">
											<input class="categories_id category_box" id="categories_{$node.id}" type="checkbox" name="categories[]" value="{$node.id}" {if isset($node.check) && $node.check == 1}checked="checked"{/if}>
											<span class="category-name" data-toggle="collapse" href="#exCollapsingNavbar{$node.id}" aria-expanded="false" aria-controls="exCollapsingNavbar{$node.id}">{$node.name}</span>
											<input class="categories_id category_radio" type="radio" name="category" value="{$node.id}">
										</div>
										{if $node.children}
											<div class="collapse" id="exCollapsingNavbar{$node.id}">
												{categories nodes=$node.children depth=$depth+1}
											</div>
										{/if}
									{/if}
								</li>
							{/foreach}
						</ul>
						{/if}
						{/strip}
						{/function}
						<label class="col-md-12 col-sm-12 col-xs-12 text-xs-left form-control-label">
							<h3>{l s='Category' mod='apmarketplace'}</h3>
						</label>
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="leo-categories-select">
								<div class="categories-tree-actions clearfix">
									<a class="product_catalog_category_tree_filter_expand float-xs-left" href="#">
										<i class="material-icons">&#xE313;</i>
										<span>{l s='Expand' mod='apmarketplace'}</span>
									</a>
									<a class="product_catalog_category_tree_filter_collapse float-xs-left" href="#" style="display: none;">
										<i class="material-icons">&#xE316;</i>
										<span>{l s='Collapse' mod='apmarketplace'}</span>
									</a>
									<span class="product_catalog_category_tree_title float-xs-right" href="#">
										{l s='Main Category' mod='apmarketplace'}
									</span>
								</div>
								<div class="leo-category-top-menu">
									<ul class="category-top-menu">
										<li class="{if count($categories.children) > 0}has-child{/if}">
											<div class="leo-categories clearfix">
												<span class="arrows collapse-icons" data-toggle="collapse" data-target="#exCollapsingNavbar{$categories.id}">
													<i class="material-icons add">&#xE315;</i>
													<i class="material-icons remove">&#xE313;</i>
												</span>
												<input {if isset($check) && $check == 1}checked="checked"{/if} class="categories_id category_box" id="categories_{$categories.id}" type="checkbox" name="categories[]" value="{$categories.id}">
												<span class="category-name" data-toggle="collapse" href="#exCollapsingNavbar{$categories.id}" aria-expanded="false" aria-controls="exCollapsingNavbar{$categories.id}">{$categories.name}</span>
												<input checked="checked" class="categories_id category_radio" type="radio" name="category" value="{$categories.id}">
											</div>
											<div class="collapse" id="exCollapsingNavbar{$categories.id}">{categories nodes=$categories.children}</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="add_product">
						<button class="btn btn-primary" type="submit" name="edit_product">
							<i class="material-icons">save</i>
							{l s='Save Product' mod='apmarketplace'}
						</button>
					</div>
				</div>
			</div>
		{/if}
	</form>		
{/block}
