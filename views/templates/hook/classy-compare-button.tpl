{if $added_already == 1}
    <a data-id-product="{$id_product}" data-added="1" class="class_compare_button {$btn_class} added" href="javascript:void(0)">
        <i class="material-icons compare">compare</i> {$addes_compare_text}
    </a>
{else}
    <a data-id-product="{$id_product}" class="class_compare_button {$btn_class}" href="javascript:void(0)">
        <i class="material-icons compare">compare</i> {$compare_text}
    </a>
{/if}

<style>
    .class_compare_button.added{
        color: {$a_textcolor} !important;
        background-color: {$a_background} !important;
    }

    .class_compare_button {
        background-color: {$background} !important;
        color: {$textcolor} !important;
    }
</style>