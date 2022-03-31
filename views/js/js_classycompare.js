$(document).ready(function () {
    $('.class_compare_button').on('click', function () {
        var $this = $(this);
        if (typeof classy_product_compare === 'undefined') {
            return false;
        }
        var id_product = $this.attr("data-id-product");
        var added_already = $this.attr("data-added");
        if(!added_already){
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: classy_product_compare,
                cache: false,
                data: { id_product: id_product , add_compare : 1},
                success: function (data) {
                    if (data.success) {
                        $('.compare-products-count').html(data.count);
                        $this.html('<i class="material-icons compare">compare</i>'+added_text);
                        $this.attr("data-added",1);
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
            return false;
        }
    });
    $('.remove_compare_button').on('click', function () {
      
        console.log( $(this).parent('.product'));
       $(this).parent('.product').remove();
       
        var psemailsubscriptionForm = $(this);
        if (typeof classy_product_compare === 'undefined') {
            return true;
        }
        var id_product = $(this).attr("data-id-product");
       // $('.block_newsletter_alert').remove();
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: classy_product_compare,
            cache: false,
            data: { id_product: id_product , remove_compare : 1},
            success: function (data) {
                console.log(data);
                if (data.success) {
                    $('.compare-products-count').html(data.count);
                } else {
                    psemailsubscriptionForm.prepend('<p class="alert alert-success block_newsletter_alert">' + data.msg + '</p>');
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
        return false;
    });
});