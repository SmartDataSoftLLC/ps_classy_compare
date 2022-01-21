{extends file='page.tpl'}
{block name='page_header_container'}
{/block}

{block name='page_content_container'}
{*
* 2007-2017 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<style type="text/css"> 
@media only screen and (min-width: 1170px)
.cd-products-columns .product {
    width: 310px;
}
.cd-products-columns .product {
    position: relative;
    float: left;
    width: 250px;
    text-align: center;
	margin-right:20px;
    -webkit-transition: opacity .3s, visibility .3s, -webkit-transform .3s;
    -moz-transition: opacity .3s, visibility .3s, -moz-transform .3s;
    transition: opacity .3s, visibility .3s, transform .3s;
	
}
.cd-products-columns .product {
    position: relative;
    float: left;
    width: 250px;
    text-align: center;
    -webkit-transition: opacity .3s, visibility .3s, -webkit-transform .3s;
    -moz-transition: opacity .3s, visibility .3s, -moz-transform .3s;
    transition: opacity .3s, visibility .3s, transform .3s;
}
@media only screen and (min-width: 1170px)
.cd-products-columns .product {
    width: 310px;
}
</style>

https://github.com/PrestaShop/PrestaShop-1.6/blob/1.6.1.24/themes/default-bootstrap/products-comparison.tpl

https://codyhouse.co/demo/products-comparison-table/index.html
compare list

    
<section class="cd-products-comparison-table">
<div class="cd-products-wrapper">
<ul class="cd-products-columns">

  {if isset($products)}
 

   {foreach from=$products item=product}
<li class="product">

<a href="#"  data-id-product="{$product.id}"  class="remove_compare_button"> <i class="material-icons ">delete</i></a>
<div class="product{if !empty($productClasses)} {$productClasses}{/if}">
  <article class="product-miniature js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}">
    <div class="thumbnail-container">
      {block name='product_thumbnail'}
        {if $product.cover}
          <a href="{$product.url}" class="thumbnail product-thumbnail">
            <img
              class="img-fluid"
              src="{$product.cover.bySize.home_default.url}"
              alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
              loading="lazy"
              data-full-size-image-url="{$product.cover.large.url}"
              width="250"
              height="250"
            />
          </a>
        {else}
          <a href="{$product.url}" class="thumbnail product-thumbnail">
            <img
              src="{$urls.no_picture_image.bySize.home_default.url}"
              loading="lazy"
              width="250"
              height="250"
            />
          </a>
        {/if}
      {/block}

      <div class="product-description">
        {block name='product_name'}
          {if $page.page_name == 'index'}
            <h3 class="h3 product-title"><a href="{$product.url}" content="{$product.url}">{$product.name|truncate:30:'...'}</a></h3>
          {else}
            <h2 class="h3 product-title"><a href="{$product.url}" content="{$product.url}">{$product.name|truncate:30:'...'}</a></h2>
          {/if}
        {/block}

        {block name='product_price_and_shipping'}
          {if $product.show_price}
            <div class="product-price-and-shipping">
              {if $product.has_discount}
                {hook h='displayProductPriceBlock' product=$product type="old_price"}

                <span class="regular-price" aria-label="{l s='Regular price' d='Shop.Theme.Catalog'}">{$product.regular_price}</span>
                {if $product.discount_type === 'percentage'}
                  <span class="discount-percentage discount-product">{$product.discount_percentage}</span>
                {elseif $product.discount_type === 'amount'}
                  <span class="discount-amount discount-product">{$product.discount_amount_to_display}</span>
                {/if}
              {/if}

              {hook h='displayProductPriceBlock' product=$product type="before_price"}

              <span class="price" aria-label="{l s='Price' d='Shop.Theme.Catalog'}">
                {capture name='custom_price'}{hook h='displayProductPriceBlock' product=$product type='custom_price' hook_origin='products_list'}{/capture}
                {if '' !== $smarty.capture.custom_price}
                  {$smarty.capture.custom_price nofilter}
                {else}
                  {$product.price}
                {/if}
              </span>

              {hook h='displayProductPriceBlock' product=$product type='unit_price'}

              {hook h='displayProductPriceBlock' product=$product type='weight'}
            </div>
          {/if}
        {/block}

        {block name='product_reviews'}
          {hook h='displayProductListReviews' product=$product}
        {/block}
      </div>

      {include file='catalog/_partials/product-flags.tpl'}

      <div class="highlighted-informations{if !$product.main_variants} no-variants{/if} hidden-sm-down">
        {block name='quick_view'}
          <a class="quick-view js-quick-view" href="#" data-link-action="quickview">
            <i class="material-icons search">&#xE8B6;</i> {l s='Quick view' d='Shop.Theme.Actions'}
          </a>
        {/block}

        {block name='product_variants'}
          {if $product.main_variants}
            {include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
          {/if}
        {/block}
      </div>
    </div>
  </article>
</div>
    

  {/foreach}
</ul> 
</div> 

</div> 
</section>
{/if}


{/block}