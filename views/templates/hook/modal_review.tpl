{* 
* @Module Name: Leo Feature
* @Website: leotheme.com.com - prestashop template provider
* @author Leotheme <leotheme@gmail.com>
* @copyright  2007-2018 Leotheme
* @description: Leo feature for prestashop 1.7: ajax cart, review, compare, wishlist at product list 
*}
<div class="modal leo-modal leo-modal-review fade" tabindex="-1" role="dialog" aria-hidden="true">
	<!--
	<div class="vertical-alignment-helper">
	-->
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title h2 text-xs-center">			
				{l s='Write a review' mod='apmarketplace'}		
			</h4>
		  </div>
		  
		  <div class="modal-body">
			<div class="row">
				{if isset($product_modal_review) && $product_modal_review}
					<div class="product-info clearfix  col-xs-12 col-sm-6">
						<img class="img-fluid" src="{$productcomment_cover_image}" alt="{$product_modal_review->name|escape:'html':'UTF-8'}" />
						<div class="product_desc">
							<p class="product_name">
								<strong>{$product_modal_review->name}</strong>
							</p>
							{$product_modal_review->description_short nofilter}
						</div>
					</div>
				{/if}
				<div class="new_review_form_content col-xs-12 col-sm-6">					
					<ul id="criterions_list">
						<li>
							<label>{l s='Quality' mod='apmarketplace'}:</label>
							<div class="star_content">
								<input class="star not_uniform" type="radio" name="criterion" value="1">
								<input class="star not_uniform" type="radio" name="criterion" value="2">
								<input class="star not_uniform" type="radio" name="criterion" value="3">
								<input class="star not_uniform" type="radio" name="criterion" value="4" checked="checked">
								<input class="star not_uniform" type="radio" name="criterion" value="5">
							</div>
							<div class="clearfix"></div>
						</li>
					</ul>				
					<form class="form-new-review" action="#" method="post">
						<div class="form-group">
						  <label class="form-control-label" for="new_review_title">{l s='Title' mod='apmarketplace'} <sup class="required">*</sup></label>
						  <input type="text" class="form-control" id="new_review_title" required="" name="new_review_title">					  
						</div>
						<div class="form-group">
						  <label class="form-control-label" for="new_review_content">{l s='Comment' mod='apmarketplace'} <sup class="required">*</sup></label>
						  <textarea type="text" class="form-control" id="new_review_content" required="" name="new_review_content"></textarea>				  
						</div>
						<div class="form-group">
							<label class="form-control-label"><sup>*</sup> {l s='Required fields' mod='apmarketplace'}</label>
						<input id="id_product_review" name="id_product_review" type="hidden" value='{$product_modal_review->id}' />
						</div>
						<button class="btn btn-primary form-control-submit leo-fake-button pull-xs-right" type="submit" style="display:none;">
						  
						</button>
					</form>
				</div>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Close' mod='apmarketplace'}</button>
			<button type="button" class="leo-modal-review-bt btn btn-primary">
				<span class="leo-modal-review-loading cssload-speeding-wheel"></span>
				<span class="leo-modal-review-bt-text">
					{l s='Submit' mod='apmarketplace'}
				</span>
			</button>
			
		  </div>
		  
		</div>
	  </div>
	<!--
	</div>
	-->
</div>