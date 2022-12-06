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
{extends file='page.tpl'}
{block name='page_title'}
  <div class="page-title">
    <h2>{l s='Compare Details' d='Modules.Classycompare.Shop'}</h3>
  </div>
{/block}
<div class="compare-details-section">
  {block name='page_content'}
    {if isset($compare_data) && !empty($compare_data)}
      <table class="table table-labeled hidden-sm-down">
        <tbody>
          {assign var="class" value="no"}
            <tr>
              <td class="compare-table-top" colspan="{count($list_ids)+1}">
                <input type="checkbox" id="highlight" name="highlight">
                <label for="highlight"> {l s='Highlight Diffrent Rows' d='Modules.Classycompare.Shop'}</label>
              </td>
            </tr>
            {foreach from=$compare_data key=k item=data}
              {if $k == "id"}
                <tr>
                  <th></th>
                  {foreach from=$list_ids key=id item=value}
                      <td class="compare-item-{$value} compare-item-{$k}">
                        <a href="#"  data-id-product="{$value}"  class="btn btn-danger remove_compare_button"> <i class="material-icons ">delete</i>{l s='Remove' d='Modules.Classycompare.Shop'}</a>
                      </td>
                  {/foreach}
                </tr>
              {elseif $k == "thumbnail"}
                <tr>
                  <th></th>
                  {foreach from=$list_ids item=value}
                      <td class="compare-item-{$value} compare-item-{$k}">
                        <a href="{$compare_data_hidden.link.$value}" target="_blank">
                          <img
                            class="img-fluid"
                            src="{$data.$value}"
                            loading="lazy"
                            data-full-size-image-url="{$value}"
                          />
                        </a>
                      </td>
                  {/foreach}
                </tr>
              {elseif $k == "add_cart"}
                <tr>
                  <th></th>
                  {foreach from=$list_ids key=id item=value}
                    <td class="compare-item-{$value}">
                      <a href="{$data.$value}" class="btn btn-primary add-to-cart">
                        <i class="material-icons shopping-cart"></i>
                        {l s='Add to Cart' d='Modules.Classycompare.Shop'}
                      </a>
                    </td>
                  {/foreach}
                </tr>
              {else}
                <tr class="{$class}-background {$diff_array.$k}">
                  <th class="{$class}-background">{$k|ucfirst}</th>
                  {foreach from=$list_ids key=id item=value}
                    {if isset($data.$value)}
                      <td class="compare-item-{$value} compare-item-{$k}">
                        {if $k == "name"}
                          <a href="{$compare_data_hidden.link.$value}" target="_blank">
                        {/if}
                        {if $k == "price" && $compare_data_hidden.discounted.$value == "1"}
                            <span class="regular_price">{$compare_data_hidden.regular_price.$value}</span>
                            <span class="discount_amount">{$compare_data_hidden.discount_amount.$value}</span>
                            {$data.$value}
                        {else}
                          {$data.$value nofilter}
                        {/if}
                        {if $k == "name"}
                          </a>
                        {/if}
                      </td>
                    {else} 
                      <td class="compare-item-{$value} compare-item-no-value">{l s='--' d='Modules.Classycompare.Shop'}</td>
                    {/if}
                  {/foreach}
                </tr>
              {/if}
              {if $class == "no"}
                {assign "class" "with"}
              {else}
                {assign "class" "no"}
              {/if}
            {/foreach}
          </tbody>
        </table>
      {else}
        <div class="page-title">
          <h2>{l s='No Products Added to Compare' d='Modules.Classycompare.Shop'}</h3>
        </div>
      {/if}
      <style>
        .compare-details-section table, .compare-details-section table tr td, .compare-details-section table tr th{
          border: 1px solid {$tborder_color};
        }
        .compare-details-section{
          background: {$details_background} !important;
          overflow-x: scroll;
          border: 6px solid {$details_background} !important;
          color: {$details_textcolor};
        }
        .compare-details-section table .compare-item-description p, .compare-details-section table .compare-table-top label{
          color: {$details_textcolor};
        }
        .with-background{
          background: {$with_back} !important;
        }
        .no-background {
          background: {$no_back} !important;
        }
      </style>
    {/block}
  {/block}
</div>