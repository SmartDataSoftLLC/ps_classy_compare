<div id="_compare_cart">
    <div class="classy-compare-top-bt">
      <div class="header">
            <a href="{$compare_link}">
                <i class="material-icons compare">compare</i>
                <span class="compare-products-count">({$compare_products_count})</span>
            </a>
        </div>
    </div>
</div>
<style>
    .classy-compare-top-bt{
        background-color: {$cnav_background} !important;
    }

    .classy-compare-top-bt .header .compare-products-count, .classy-compare-top-bt .header i{
        color: {$cnav_textcolor} !important;
    }

    .classy-compare-top-bt:hover {
        background-color: {$cnav_a_background} !important;
    }

    .classy-compare-top-bt:hover .header .compare-products-count, .classy-compare-top-bt:hover .header i{
        color: {$cnav_a_textcolor} !important;
    }
</style>