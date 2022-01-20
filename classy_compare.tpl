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
.cd-products-comparison-table {
	margin-bottom: 6em
}

.cd-products-comparison-table::after {
	display: none;
	content: 'mobile'
}

.cd-products-comparison-table header {
	padding: 0 5% 25px
}

.cd-products-comparison-table header::after {
	clear: both;
	content: "";
	display: table
}

.cd-products-comparison-table h2 {
	float: left;
	font-weight: 700
}

.cd-products-comparison-table .actions {
	float: right
}

.cd-products-comparison-table .reset,
.cd-products-comparison-table .filter {
	font-size: 1.4rem
}

.cd-products-comparison-table .reset {
	color: #404042;
	text-decoration: underline
}

.cd-products-comparison-table .filter {
	padding: .6em 1.5em;
	color: #fff;
	background-color: #ccc;
	border-radius: 3px;
	margin-left: 1em;
	cursor: not-allowed;
	-webkit-transition: background-color .3s;
	-moz-transition: background-color .3s;
	transition: background-color .3s
}

.cd-products-comparison-table .filter.active {
	cursor: pointer;
	background-color: #9dc997
}

.no-touch .cd-products-comparison-table .filter.active:hover {
	background-color: #a7cea1
}

@media only screen and (min-width:1170px) {
	.cd-products-comparison-table {
		margin-bottom: 8em
	}
	.cd-products-comparison-table::after {
		content: 'desktop'
	}
	.cd-products-comparison-table header {
		padding: 0 5% 40px
	}
	.cd-products-comparison-table h2 {
		font-size: 2.4rem
	}
	.cd-products-comparison-table .reset,
	.cd-products-comparison-table .filter {
		font-size: 1.6rem
	}
	.cd-products-comparison-table .filter {
		padding: .6em 2em;
		margin-left: 1.6em
	}
}

.cd-products-table {
	position: relative;
	overflow: hidden
}

.cd-products-table .features {
	position: absolute;
	z-index: 1;
	top: 0;
	left: 0;
	width: 120px;
	border-style: solid;
	border-color: #e6e6e6;
	border-top-width: 1px;
	border-bottom-width: 1px;
	background-color: #fafafa;
	opacity: .95
}

.cd-products-table .features::after {
	content: '';
	position: absolute;
	top: 0;
	left: 100%;
	width: 4px;
	height: 100%;
	background-color: transparent;
	background-image: -webkit-linear-gradient(left, rgba(0, 0, 0, 0.06), transparent);
	background-image: linear-gradient(to right, rgba(0, 0, 0, 0.06), transparent);
	opacity: 0
}

@media only screen and (min-width:1170px) {
	.cd-products-table .features {
		width: 210px
	}
}

.cd-products-table.scrolling .features::after {
	opacity: 1
}

.cd-products-wrapper {
	overflow-x: auto;
	-webkit-overflow-scrolling: touch;
	border-style: solid;
	border-color: #e6e6e6;
	border-top-width: 1px;
	border-bottom-width: 1px
}

.cd-products-columns {
	width: 1200px;
	margin-left: 120px
}

.cd-products-columns::after {
	clear: both;
	content: "";
	display: table
}

@media only screen and (min-width:1170px) {
	.cd-products-columns {
		width: 2480px;
		margin-left: 210px
	}
}

.cd-products-columns .product {
	position: relative;
	float: left;
	width: 150px;
	text-align: center;
	-webkit-transition: opacity .3s, visibility .3s, -webkit-transform .3s;
	-moz-transition: opacity .3s, visibility .3s, -moz-transform .3s;
	transition: opacity .3s, visibility .3s, transform .3s
}

.filtering .cd-products-columns .product:not(.selected) {
	opacity: 0;
	visibility: hidden;
	-webkit-transform: scale(0);
	-moz-transform: scale(0);
	-ms-transform: scale(0);
	-o-transform: scale(0);
	transform: scale(0)
}

.no-product-transition .cd-products-columns .product.selected {
	-webkit-transition: opacity .3s, visibility .3s;
	-moz-transition: opacity .3s, visibility .3s;
	transition: opacity .3s, visibility .3s
}

.filtered .cd-products-columns .product:not(.selected) {
	position: absolute
}

@media only screen and (min-width:1170px) {
	.cd-products-columns .product {
		width: 310px
	}
}

.cd-features-list li {
	font-size: 1.4rem;
	font-weight: 700;
	padding: 25px 40px;
	border-color: #e6e6e6;
	border-style: solid;
	border-top-width: 1px;
	border-right-width: 1px
}

.cd-features-list li.rate {
	padding: 21px 0
}

.cd-features-list li.rate span {
	display: inline-block;
	height: 22px;
	width: 110px;
	background: url(../img/cd-star.svg);
	color: transparent
}

@media only screen and (min-width:1170px) {
	.cd-features-list li {
		font-size: 1.6rem
	}
	.cd-features-list li.rate {
		padding: 22px 0
	}
}

.features .cd-features-list li,
.cd-products-table .features .top-info {
	font-size: 1.2rem;
	font-weight: 700;
	line-height: 14px;
	padding: 25px 10px;
	text-align: left
}

@media only screen and (min-width:1170px) {
	.features .cd-features-list li,
	.cd-products-table .features .top-info {
		text-transform: uppercase;
		line-height: 16px;
		padding: 25px 20px;
		letter-spacing: 1px
	}
}

.features .cd-features-list li {
	white-space: nowrap;
	text-overflow: ellipsis;
	overflow: hidden
}

.cd-products-table .top-info {
	position: relative;
	height: 177px;
	width: 150px;
	text-align: center;
	padding: 1.25em 2.5em;
	border-color: #e6e6e6;
	border-style: solid;
	border-right-width: 1px;
	-webkit-transition: height .3s;
	-moz-transition: height .3s;
	transition: height .3s;
	cursor: pointer;
	background: #fff
}

.cd-products-table .top-info::after {
	content: '';
	position: absolute;
	left: 0;
	top: 100%;
	height: 4px;
	width: 100%;
	background-color: transparent;
	background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0.06), transparent);
	background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.06), transparent);
	opacity: 0
}

.cd-products-table .top-info h3 {
	padding: 1.25em 0 .625em;
	font-weight: 700;
	font-size: 1.4rem
}

.cd-products-table .top-info img {
	display: block;
	-webkit-backface-visibility: hidden;
	backface-visibility: hidden
}

.cd-products-table .top-info h3,
.cd-products-table .top-info img {
	-webkit-transition: -webkit-transform .3s;
	-moz-transition: -moz-transform .3s;
	transition: transform .3s
}

.cd-products-table .top-info .check {
	position: relative;
	display: inline-block;
	height: 16px;
	width: 16px;
	margin: 0 auto 1em
}

.cd-products-table .top-info .check::after,
.cd-products-table .top-info .check::before {
	position: absolute;
	top: 0;
	left: 0;
	content: '';
	height: 100%;
	width: 100%
}

.cd-products-table .top-info .check::before {
	border-radius: 50%;
	border: 1px solid #e6e6e6;
	background: #fff;
	-webkit-transition: background-color .3s, -webkit-transform .3s, border-color .3s;
	-moz-transition: background-color .3s, -moz-transform .3s, border-color .3s;
	transition: background-color .3s, transform .3s, border-color .3s
}

.cd-products-table .top-info .check::after {
	background: url(../img/cd-check.svg) no-repeat center center;
	background-size: 24px 24px;
	opacity: 0;
	-webkit-transition: opacity .3s;
	-moz-transition: opacity .3s;
	transition: opacity .3s
}

@media only screen and (min-width:1170px) {
	.cd-products-table .top-info {
		height: 280px;
		width: 310px
	}
	.cd-products-table .top-info h3 {
		padding-top: 1.4em;
		font-size: 1.6rem
	}
	.cd-products-table .top-info .check {
		margin-bottom: 1.5em
	}
}

.cd-products-table .features .top-info {
	width: 120px;
	cursor: auto;
	background: #fafafa
}

@media only screen and (min-width:1170px) {
	.cd-products-table .features .top-info {
		width: 210px
	}
}

.cd-products-table .selected .top-info .check::before {
	background: #9dc997;
	border-color: #9dc997;
	-webkit-transform: scale(1.5);
	-moz-transform: scale(1.5);
	-ms-transform: scale(1.5);
	-o-transform: scale(1.5);
	transform: scale(1.5);
	-webkit-animation: cd-bounce .3s;
	-moz-animation: cd-bounce .3s;
	animation: cd-bounce .3s
}

@-webkit-keyframes cd-bounce {
	0% {
		-webkit-transform: scale(1)
	}
	60% {
		-webkit-transform: scale(1.6)
	}
	100% {
		-webkit-transform: scale(1.5)
	}
}

@-moz-keyframes cd-bounce {
	0% {
		-moz-transform: scale(1)
	}
	60% {
		-moz-transform: scale(1.6)
	}
	100% {
		-moz-transform: scale(1.5)
	}
}

@keyframes cd-bounce {
	0% {
		-webkit-transform: scale(1);
		-moz-transform: scale(1);
		-ms-transform: scale(1);
		-o-transform: scale(1);
		transform: scale(1)
	}
	60% {
		-webkit-transform: scale(1.6);
		-moz-transform: scale(1.6);
		-ms-transform: scale(1.6);
		-o-transform: scale(1.6);
		transform: scale(1.6)
	}
	100% {
		-webkit-transform: scale(1.5);
		-moz-transform: scale(1.5);
		-ms-transform: scale(1.5);
		-o-transform: scale(1.5);
		transform: scale(1.5)
	}
}

.cd-products-table .selected .top-info .check::after {
	opacity: 1
}

@media only screen and (min-width:1170px) {
	.cd-products-table.top-fixed .cd-products-columns>li,
	.cd-products-table.top-scrolling .cd-products-columns>li,
	.cd-products-table.top-fixed .features,
	.cd-products-table.top-scrolling .features {
		padding-top: 160px
	}
	.cd-products-table.top-fixed .top-info,
	.cd-products-table.top-scrolling .top-info {
		height: 160px;
		position: fixed;
		top: 0
	}
	.no-cssgradients .cd-products-table.top-fixed .top-info,
	.no-cssgradients .cd-products-table.top-scrolling .top-info {
		border-bottom: 1px solid #e6e6e6
	}
	.cd-products-table.top-fixed .top-info::after,
	.cd-products-table.top-scrolling .top-info::after {
		opacity: 1
	}
	.cd-products-table.top-fixed .top-info h3,
	.cd-products-table.top-scrolling .top-info h3 {
		-webkit-transform: translateY(-116px);
		-moz-transform: translateY(-116px);
		-ms-transform: translateY(-116px);
		-o-transform: translateY(-116px);
		transform: translateY(-116px)
	}
	.cd-products-table.top-fixed .top-info img,
	.cd-products-table.top-scrolling .top-info img {
		-webkit-transform: translateY(-62px) scale(.4);
		-moz-transform: translateY(-62px) scale(.4);
		-ms-transform: translateY(-62px) scale(.4);
		-o-transform: translateY(-62px) scale(.4);
		transform: translateY(-62px) scale(.4)
	}
	.cd-products-table.top-scrolling .top-info {
		position: absolute
	}
}
</style>

https://github.com/PrestaShop/PrestaShop-1.6/blob/1.6.1.24/themes/default-bootstrap/products-comparison.tpl

https://codyhouse.co/demo/products-comparison-table/index.html
compare list

    
<section class="cd-products-comparison-table">

<div class="cd-products-table">
<div class="features">
<div class="top-info" style="">Models</div>
<ul class="cd-features-list">
<li>Price</li>
<li>Customer Rating</li>
<li>Resolution</li>
<li>Screen Type</li>
<li>Display Size</li>
<li>Refresh Rate</li>
<li>Model Year</li>
<li>Tuner Technology</li>
<li>Ethernet Input</li>
<li>USB Input</li>
<li>Scart Input</li>
</ul>
</div> 
<div class="cd-products-wrapper">
<ul class="cd-products-columns">

  {if isset($products)}
 

   {foreach from=$products item=product}
<li class="product">

<a href="#"  data-id-product="{$product->id}"  class="remove_compare_button"> <i class="material-icons ">delete</i></a>
<div class="top-info" style="">
<div class="check"></div>


<h3>{$product->name};</h3>
 
        {* Get product description *}
        {*$c_product->description nofilter*};
</div> 
<ul class="cd-features-list">

<li>$600</li>
<li class="rate"><span>5/5</span></li>
<li>1080p</li>
<li>LED</li>
<li>47.6 inches</li>
<li>800Hz</li>
<li>2015</li>
<li>mpeg4</li>
<li>1 Side</li>
<li>3 Port</li>
<li>1 Rear</li>
</ul>
</li> 

        
    

  {/foreach}
</ul> 
</div> 

</div> 
</section>
{/if}

