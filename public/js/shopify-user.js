$(document).ready(function () {
    $('.de-associate-button').click(function () {
        var $this = $(this);
        Swal.fire({
            title: ' Are you sure?',
            html:'<p>You want to De-associate this store !</p>',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'De-associate'
        }).then((result) => {
            if (result.value) {
                window.location.href = $(this).data('href');
            }
            else{
                location.reload();
            }
        });
    });

    $('#product-search-field').keyup(function(){
        $.ajax({
            url: '/search/products',
            type: 'GET',
            data: {
                search:$(this).val(),
            },
            success: function(response) {
                if(response.html !== ""){

                    $('#product-section').empty();
                    $('#product-section').append(response.html);
                }
                else{
                    $('#product-section').empty();
                    $('#product-section').append('<p class="text-center" style="padding:10px"> No Product Found</p>');
                }

            },
        });
        0
    });

    $('body').on('change','.product-checkbox',function(){
        var related = $(this).data('related');
        if($(this).is(':checked')){
            $(related).find('.variant-checkbox').attr('checked',true);
        }
        else{
            $(related).find('.variant-checkbox').attr('checked',false);
        }

    });
    $('body').on('change','.variant-checkbox',function(){
        var related = $(this).data('related');
        let $parent;
        if ($(this).is(':checked')) {
            $(related).attr('checked', true);
        } else {
            $parent = $(related).data('related');
            if ($($parent).find('.variant-checkbox:checked').length === 0) {
                $(related).attr('checked', false);
            }
        }

    });
    $('body').on('click','.delete-row',function () {
        $(this).closest('tr').remove();
        if($('.variants-body').find('tr').length === 0){
            $('body').find('#variant_selection_check').val('0');
            $('.selected-variant-section').empty();
        }
    });

    $('body').on('change','.line-item-quantity',function () {
        set_price();
    });

    function set_price(){
        let price = 0;
        $('.line-item-quantity').each(function () {
            price = price + $(this).val() * $(this).data('price');
        });
        $('.total-cost-badge').empty();
        $('.total-cost-badge').append(price.toFixed(2) + ' USD');
    }



    $('body').on('submit','#get-selection-form',function (e) {
        e.preventDefault();
        if($(this).find('.variant-checkbox:checked').length > 0){
            alertify.success('Processing Your Selection. Wait!');
            $.ajax({
                url: $(this).attr('action'),
                type: 'GET',
                data:$(this).serialize(),
                success: function(response) {
                    $('#browse_product_modal').modal('hide');
                    if(response.html !== ""){
                        $('body').find('#variant_selection_check').val('1');
                        $('.selected-variant-section').empty();
                        $('.selected-variant-section').append(response.html);
                    }
                    else{
                        $('body').find('#variant_selection_check').val('0');
                        $('.selected-variant-section').empty();
                        $('.selected-variant-section').append('<p class="text-center" style="padding:10px"> No Selected Variants Found</p>');
                    }
                },
            });
        }
        else{
            alertify.error('Select at least one variant');
        }
    });

    $('body').on('submit','#custom_order_form',function (e) {
        if ($(this).find('#variant_selection_check').val() === '0') {
            e.preventDefault();
            alertify.error('Select at least one variant!');
        }
    });

    $('body').on('change','#customer_selection_drop',function (e) {
        if ($(this).val() === '') {
        }
        else{
            $('input[name=c_first_name]').val($(this).find(':selected').data('first'));
            $('input[name=email]').val($(this).find(':selected').data('email'));
            $('input[name=c_last_name]').val($(this).find(':selected').data('last'));
        }
    });

    $('body').on('change','#country-selection',function (e) {
        var form = $('#custom_order_form');
        $.ajax({
            url: $(this).data('route'),
            type: 'POST',
            data: form.serialize(),
            success:function (response){
                if(response.message === 'success'){
                    let ship_price = response.rate;
                    let price = 0;
                    $('.line-item-quantity').each(function () {
                        price = price + $(this).val() * $(this).data('price');
                    });

                    $('.total-cost-badge').empty();
                    $('.total-cost-badge').append(price.toFixed(2) + ' USD');

                    $('.total-ship-badge').empty();
                    $('.total-ship-badge').append(ship_price.toFixed(2) + ' USD');
                    $('#shipping_price_input').val(ship_price);

                    let total = price + ship_price;
                    $('.total-final-badge').empty();
                    $('.total-final-badge').append(total.toFixed(2) + ' USD');

                }
            },
        })
    });
    /*Paypal Order Payment Button JS*/
    $('body').on('click','.paypal-pay-button',function () {
        var button = $(this);
        Swal.fire({
            title: ' Are you sure?',
            html:'<p> A amount of '+ $(this).data('pay') +' will be deducted through your Paypal Account</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Pay'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Processing!',
                    'You will be redirected to paypal in seconds!',
                    'success'
                );
                window.location.href = button.data('href');
            }
        });


    });

    /*Wallet Order Payment Button JS*/
    $('body').on('click','.wallet-pay-button',function () {
        var button = $(this);
        Swal.fire({
            title: ' Are you sure?',
            html:'<p> A amount of '+ $(this).data('pay') +' will be deducted through your wallet </p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Pay'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Processing!',
                    'Payment Processing Please Wait!',
                    'success'
                );
                window.location.href = button.data('href');
            }
        });


    });

    });
