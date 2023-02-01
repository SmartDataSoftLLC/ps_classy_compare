$(document).ready(function () {
    $('.class_compare_button').on('click', function () {
        var $this = $(this);
        if (typeof classy_product_compare === 'undefined') {
            return false;
        }
        var id_product = $this.attr("data-id-product");
        var added_already = $this.attr("data-added");
        if (!added_already) {
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: classy_product_compare,
                cache: false,
                data: { id_product: id_product, add_compare: 1 },
                success: function (data) {
                    if (data.success) {
                        $('.compare-products-count').html(data.count);
                        $this.html('<i class="material-icons compare">compare</i>' + added_text);
                        $this.attr("data-added", 1);
                        $this.addClass("added")
                        $('#header').append('<div class="classy-compare-toast classy-compare-success-msg">' + data.msg + '<a class="compare-quicklink" href="' + compare_url + '" target="_blank">' + link_text + '</a></div>')
                        setTimeout(function () {
                            $('.classy-compare-success-msg').remove();
                        }, 3000);
                    } else {
                        $('#header').append('<div class="classy-compare-toast classy-compare-error-msg">' + data.msg + '</div>')
                        setTimeout(function () {
                            $('.classy-compare-error-msg').remove();
                        }, 3000);
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
        if (typeof classy_product_compare === 'undefined') {
            return true;
        }
        var id_product = $(this).attr("data-id-product");
        $(".compare-item-" + id_product).remove();
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: classy_product_compare,
            cache: false,
            data: { id_product: id_product, remove_compare: 1 },
            success: function (data) {
                console.log(data);
                if (data.success) {
                    $('.compare-products-count').html(data.count);
                    if (data.count == 0) {
                        $('#content').html();
                        $('#content').html('<div class="page-title"><h2>' + not_found_text + '</h2></div>');
                    }
                }
            },
            error: function (err) {
            }
        });
        return false;
    });
    $('input[type="checkbox"]').click(function () {
        if ($(this).prop("checked") == true) {
            $(".classy-compare-diff").addClass("highlighted");
        } else if ($(this).prop("checked") == false) {
            $(".classy-compare-diff").removeClass("highlighted");
        }
    });
});