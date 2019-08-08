{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}

{extends file='layouts/layout-left-column.tpl'}

{block name='content'}
  <section id="id_vendor">
    <div class="row">
      <div class="col-md-3">
        <div class="vendor-img">
          <img src="{$baseurl}modules/apmarketplace/views/img/vendor/{$vendor->image}">
        </div>
      </div>
      <div class="col-md-9">
        <div class="vendor-infor">
          <h3>{$vendor->last_name} {$vendor->first_name }</h3>
          <p class="leo_phone">
            <i class="material-icons">phone</i>
            {$vendor->phone}
          </p>
          <p class="leo_email">
            <i class="material-icons">email</i>
            {$vendor->email} 
          </p>
        </div>
      </div>
    </div>
  </section>
  <section id="main">
    <section id="products">
      {if $listing.products|count}
        <div id="">
          {block name='product_list'}
            <div id="js-product-list">
              <div class="products">  
                {assign var="products" value=$listing.products}
                {if isset($productProfileDefault) && $productProfileDefault}
                  {include file='catalog/_partials/miniatures/leo_col_products.tpl' products=$products} 
                {else}
                  <div class="row">
                    {foreach from=$products item="product"}
                      <div class="ajax_block_product product_block col-sp-12 col-xs-6 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                        {block name='product_miniature'}
                          {include file='catalog/_partials/miniatures/product.tpl' product=$product}
                        {/block}
                      </div>
                    {/foreach}
                  </div>
                {/if}  
              </div>
              
              {block name='pagination'}
          			{include file='modules/apmarketplace/views/templates/front/pagination.tpl' pagination=$listing.pagination}
        			{/block}

              <div class="hidden-md-up text-xs-right up">
                <a href="#header" class="btn btn-secondary">
                  {l s='Back to top' d='Shop.Theme.Actions'}
                  <i class="material-icons">&#xE316;</i>
                </a>
              </div>
            </div>
          {/block}
        </div>
      {else}
        {include file='errors/not-found.tpl'}
      {/if}
    </section>
  </section>
{/block}