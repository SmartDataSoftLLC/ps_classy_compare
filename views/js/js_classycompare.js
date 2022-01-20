$(document).ready(function () {
    $('.class_compare_button').on('click', function () {
      
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
            data: { id_product: id_product , add_compare : 1},
            success: function (data) {
                if (data.nw_error) {
                    psemailsubscriptionForm.prepend('<p class="alert alert-danger block_newsletter_alert">' + data.msg + '</p>');
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
    $('.remove_compare_button').on('click', function () {
      
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
                if (data.nw_error) {
                    psemailsubscriptionForm.prepend('<p class="alert alert-danger block_newsletter_alert">' + data.msg + '</p>');
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