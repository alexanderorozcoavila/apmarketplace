{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}

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
                <span class="category-name" data-toggle="collapse" href="#exCollapsingNavbar{$node.id}" aria-expanded="false" aria-controls="exCollapsingNavbar{$node.id}">{$node.name}</span>
                <input {if isset($id_category) && $id_category == $node.id}checked="checked"{/if} class="categories_id category_radio" id="categories_{$node.id}" type="radio" name="categories" value="{$node.id}">
              </div>
              {if $node.children}
                <div class="collapse" id="exCollapsingNavbar{$node.id}">
                  {categories nodes=$node.children depth=$depth+1}
                </div>
              {/if}
            {else}
              <div class="leo-categories clearfix">
                {if $node.children}
                  <span class="arrows collapse-icons" data-toggle="collapse" data-target="#exCollapsingNavbar{$node.id}">
                    <i class="material-icons add">&#xE315;</i>
                    <i class="material-icons remove">&#xE313;</i>
                  </span>
                {/if}
                <span class="category-name" data-toggle="collapse" href="#exCollapsingNavbar{$node.id}" aria-expanded="false" aria-controls="exCollapsingNavbar{$node.id}">{$node.name}</span>
                <input {if isset($id_category) && $id_category == $node.id}checked="checked"{/if} class="categories_id category_radio" id="categories_{$node.id}" type="radio" name="categories" value="{$node.id}">
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

<div class="leo-filter-categories">
  <label class="filter-by-categories" data-toggle="collapse" href="#exCollapsingNavbar-filter-by-categories" aria-expanded="false" aria-controls="exCollapsingNavbar-filter-by-categories">
    {l s='Filter By Categories' mod='apmarketplace'}
    {if isset($cate_name) && $cate_name != ''}
      <span class="category-name-fillter">{$cate_name}</span>
    {/if}
    <span class="arrows collapse-icons" data-toggle="collapse" data-target="#exCollapsingNavbar-filter-by-categories">
      <i class="material-icons add">&#xE313;</i>
      <i class="material-icons remove">&#xE316;</i>
    </span>
  </label>
  <div class="leo-filter-dropdown-menu collapse" id="exCollapsingNavbar-filter-by-categories">
    <div class="categories-tree-actions clearfix">
        <a class="product_catalog_category_tree_filter_expand float-xs-left" href="#">
          <i class="material-icons">&#xE313;</i>
          <span>{l s='Expand' mod='apmarketplace'}</span>
        </a>
        <a class="product_catalog_category_tree_filter_collapse float-xs-left" href="#" style="display: none;">
          <i class="material-icons">&#xE316;</i>
          <span>{l s='Collapse' mod='apmarketplace'}</span>
        </a>
        <a class="product_catalog_category_tree_filter_reset float-xs-right" href="#">
          <i class="material-icons">&#xE836;</i>
          <span>{l s='Unselect' mod='apmarketplace'}</span>
        </a>
    </div>
    <ul class="leo-category-top-menu">
      <li class="{if count($categories.children) > 0}has-child{/if}">
    		<div class="leo-categories clearfix">
          <span class="arrows collapse-icons" data-toggle="collapse" data-target="#exCollapsingNavbar{$categories.id}">
            <i class="material-icons add">&#xE315;</i>
            <i class="material-icons remove">&#xE313;</i>
          </span>
          <span class="category-name" data-toggle="collapse" href="#exCollapsingNavbar{$categories.id}" aria-expanded="false" aria-controls="exCollapsingNavbar{$categories.id}">{$categories.name}</span>
    		  <input {if isset($id_category) && $id_category == $categories.id}checked="checked"{/if} class="categories_id category_radio" id="categories_{$categories.id}" type="radio" name="categories" value="{$categories.id}">
        </div>
        <div class="collapse" id="exCollapsingNavbar{$categories.id}">{categories nodes=$categories.children}</div>
      </li>
    </ul>
  </div>
</div>