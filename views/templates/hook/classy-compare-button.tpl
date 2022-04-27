{if $added_already == 1}
    <a data-id-product="{$id_product}" data-added="1" class="class_compare_button {$btn_class} added" href="javascript:void(0)">
        <i class="material-icons compare">compare</i> {$addes_compare_text}
    </a>
{else}
    <a data-id-product="{$id_product}" class="class_compare_button {$btn_class}" href="javascript:void(0)">
        <i class="material-icons compare">compare</i> {$compare_text}
    </a>
{/if}

