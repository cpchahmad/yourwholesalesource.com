$(document).ready(function () {

    // Paypal Wallet Top-up
    $('.paypal-wallet-pay-btn').click(function() {
        var amount = parseFloat($('#bank_transfer_modal').find('.amount-to-be-added').val());

        $('#bank_transfer_modal').modal('hide');

        $('#paypal_pay_trigger').find('.amount-to-be-paid').text(amount.toFixed(2));
        $('#paypal_pay_trigger').modal('show');
    });

    // Stripe Order Payment
    var $form = $(".require-validation");

    $('form.require-validation').bind('submit', function(e) {
        var $form = $(".require-validation"),
            inputSelector = ['input[type=email]', 'input[type=password]',
                'input[type=text]', 'input[type=file]',
                'textarea'
            ].join(', '),
            $inputs = $form.find('.required').find(inputSelector),
            $errorMessage = $form.find('div.error'),
            valid = true;
        $errorMessage.hide();
        $('.pay-btn').prop('disabled', true);
        $('.pay-btn').text('Processing..');
        $('.has-error').removeClass('has-error');
        $inputs.each(function(i, el) {
            var $input = $(el);
            if ($input.val() === '') {
                $input.parent().addClass('has-error');
                $errorMessage.removeClass('hide');
                e.preventDefault();
            }
        });
        if (!$form.data('cc-on-file')) {
            e.preventDefault();
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
        }
    });

    function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.error')
                .show()
                .find('.alert')
                .text(response.error.message);

            $('.pay-btn').prop('disabled', false);
            $('.pay-btn').text('Pay Now');
        } else {
            /* token contains id, last4, and card type */
            var token = response['id'];
            var $form = $(".require-validation");
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }

    $('body').on('click','.see-more-block',function () {
        $('.after12').show();
        $(this).hide();
    });
    $('body').on('click','.see-less-block',function () {
        $('.after12').hide();
        $('.see-more-block').show();

    });

    $(document).on('click', '.add-product-details-tab-btn', function() {
        var id = $(this).attr('id');
        $(this).closest('.product-details-body').append(`
              <tr class="single-product-details">
                    <td class="">
                        <input type="text" class="form-control" name="sku[]" value="" placeholder="Enter sku here..">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="option[]" placeholder="Enter name of options here..">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="inventory[]" placeholder="Enter product inventory here..">
                    </td>
                    <td class="text-center">
                        <input type="file" class="form-control" name="image[]">
                    </td>
                    <td class="text-right">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-sm btn-primary add-product-details-tab-btn">+</button>
                            <button type="button" class="btn btn-sm btn-danger remove-product-details-tab-btn">-</button>
                        </div>
                    </td>
              </tr>
        `);

    });

    $(document).on('click', '.remove-product-details-tab-btn', function() {
        $(this).closest('.single-product-details').empty();
    });

    // warehouse selection on order details page
    $('.warehouse-selector').change(function(){
        var data = $(this).val().split(",");
        var id = data[0];
        var product = data[1];
        var order = data[2];
        var line_item = data[3];


        $.ajax({
            url: `/get-warehouse/shipping-price`,
            type: 'GET',
            data: {
                product: product,
                id: id,
                order: order,
                line_item: line_item,
            },
            success:function (response) {

                $('.js-warehouse-shipping').empty().append(response);
            }
        });

    });

    // Grapgh checkbox
    $('body').on('change','#graph_checkbox',function () {
        if($(this).is(':checked')){
            $('#canvas-graph-two-users').show();
            $('#canvas-graph-one-users').hide();
        }
        else{
            $('#canvas-graph-two-users').hide();
            $('#canvas-graph-one-users').show();
        }
    });

    $('.show-product-modal').click(function () {

        $('#browse_product_modal').modal('show');

        $.ajax({
            url: `/users/get/admin/products`,
            type: 'GET',
            success: function(res) {

                $('#browse_product_modal').find('#product-section').empty();
                $('#browse_product_modal').find('#product-section').append(res);
            }
        });
    });


    $('.show-dropship-product-modal').click(function () {

        $('#browse_dropship_product_modal').modal('show');

        $.ajax({
            url: `/users/get/dropship/products`,
            type: 'GET',
            success: function(res) {

                $('#browse_dropship_product_modal').find('#dropship-product-section').empty();
                $('#browse_dropship_product_modal').find('#dropship-product-section').append(res);
            }
        });
    });

    $('.custom-order-btn').click(function () {
        $(this).text('Processing, Please Wait');
        $(this).prop('disabled', true);
    });

    /*Letting the non-shopify-user switch to store mode*/
    $('.shop-log-btn').click(function() {
        var shop = $(this).find('.shop-domain-name').html();
        $('.shop-domain-input').val(shop);
        $('.shop-login-form').submit();
    });

    $('.settings-shop-log-btn').click(function() {
        var shop = $(this).find('.shop-domain-name').val();
        $('.shop-domain-input').val(shop);
        $('.shop-login-form').submit();
    });

    $('.settings-woo-shop-log-btn').click(function() {
        var shop = $(this).find('.woo-shop-domain-name').val();
        $('.woo-shop-domain-input').val(shop);
        $('.woo-shop-login-form').submit();
    });



    /*Order Bulk Pay*/
    /*BULK ORDER PAY*/
    $('.check-order-all-user').change(function () {
        unset_bulk_array();
        set_bulk_array();

        if($(this).is(':checked')){
            $('.bulk-div').show();
        }
        else{
            $('.bulk-div').hide();

        }

    });
    $('.check-order-user').change(function () {
        if($(this).is(':checked')){
            $('.bulk-div').show();
            unset_bulk_array();
            set_bulk_array();
        }
        else{
            unset_bulk_array();
            set_bulk_array();
            if($('.check-order-user:checked').length === 0){
                $('.bulk-div').hide();
            }

        }

    });
    function set_bulk_array() {
        var values = [];
        $('.check-order-user:checked').each(function () {
            values.push($(this).val());
        });
        // $('#bulk-fullfillment').find('input:hidden[name=orders]').val(values);

    }
    function unset_bulk_array() {
        // $('#bulk-fullfillment').find('input:hidden[name=orders]').val('');

    }
    /*Popup Code*/
    // var url_string = window.location.href;
    // var url = new URL(url_string);
    // var c = url.searchParams.get("ftl");
    // if(c !== null){
    //     $.ajax({
    //         url:$('#questionnaire_modal').data('route'),
    //         type: 'GET',
    //         data:{
    //             user : $('#questionnaire_modal').data('user'),
    //         },
    //         success:function (response) {
    //             if(response.popup === 'yes'){
    //                 $('#questionnaire_modal').modal();
    //             }
    //         }
    //
    //     });
    // }


    $('body').on('change','#change-view-store',function () {

        if($(this).val() != ''){
            window.location.href = $(this).val();
        }

    });

    $('body').on('click','.calculate_shipping_btn',function () {
        var button = $(this);
        $.ajax({
            url:$(this).data('route'),
            type: 'GET',
            data:{
                product: $(this).data('product'),
                warehouse_id : $(this).data('warehouse')
            },
            success:function (response) {
                var modal = button.data('target');
                $(modal).find('.loader-div').hide();
                $(modal).find('.drop-content').empty();
                $(modal).find('.drop-content').append(response.html);

            }
        });
    });
    $('body').on('change','.shipping_country_select',function () {
        $(this).parents('.modal').find('.drop-content').hide();
        $(this).parents('.modal').find('.loader-div').show();
        var select = $(this);
        $.ajax({
            url:$(this).data('route'),
            type: 'GET',
            data:{
                product: $(this).data('product'),
                country :$(this).val(),
            },
            success:function (response) {
                var modal = '#'+select.parents('.modal').attr('id');
                console.log(modal);
                $(modal).find('.loader-div').hide();
                $(modal).find('.drop-content').empty();
                $(modal).find('.drop-content').append(response.html);
                $(modal).find('.drop-content').show();

            }
        });
    });
    $('body').on('change','.shipping_price_radio',function () {
        if($(this).is(':checked')){
            $(this).parents('.block-content').find('.drop-shipping').text($(this).data('price'));
            $(this).parents('.block-content').find('.calculate_shipping_btn').text($(this).data('country'));
        }
    });


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

    $('.product-search-button').click(function() {

        $('#product-section').empty();
        $('#product-section').append(`
            <div class="text-center">
                <p>Processing...</p>
            </div>
        `);

        $.ajax({
            url: '/search/products',
            type: 'GET',
            data: {
                search: $('#product-search-field').val(),
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
    });

    $('.dropship-product-search-button').click(function() {

        $('#dropship-product-section').empty();
        $('#dropship-product-section').append(`
            <div class="text-center">
                <p>Processing...</p>
            </div>
        `);

        $.ajax({
            url: '/search/dropship/products',
            type: 'GET',
            data: {
                search: $('#dropship-product-search-field').val(),
            },
            success: function(response) {
                if(response.html !== ""){

                    $('#dropship-product-section').empty();
                    $('#dropship-product-section').append(response.html);
                }
                else{
                    $('#dropship-product-section').empty();
                    $('#dropship-product-section').append('<p class="text-center" style="padding:10px"> No Product Found</p>');
                }

            },
        });
    });

    //
    // $('#product-search-field').keyup(function(){
    //     $.ajax({
    //         url: '/search/products',
    //         type: 'GET',
    //         data: {
    //             search:$(this).val(),
    //         },
    //         success: function(response) {
    //             if(response.html !== ""){
    //
    //                 $('#product-section').empty();
    //                 $('#product-section').append(response.html);
    //             }
    //             else{
    //                 $('#product-section').empty();
    //                 $('#product-section').append('<p class="text-center" style="padding:10px"> No Product Found</p>');
    //             }
    //
    //         },
    //     });
    //
    // });

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

        $('.total-final-badge').empty();
        $('.total-final-badge').append(price.toFixed(2) + ' USD');
    }



    $('body').on('submit','#get-selection-form',function (e) {
        e.preventDefault();
        if($(this).find('.variant-checkbox:checked').length > 0){
            alertify.success('Processing Your Selection. Wait!');
            $('.add-to-order-btn').text('Processing');
            $('.add-to-order-btn').prop('disabled', true);
            $.ajax({
                url: $(this).attr('action'),
                type: 'GET',
                data:$(this).serialize(),
                success: function(response) {
                    $('#browse_product_modal').modal('hide');
                    if(response.html !== ""){
                        $('body').find('#variant_selection_check').val('1');
                        $('.add-to-order-btn').text('Add to Order');
                        $('.add-to-order-btn').prop('disabled', false);
                        $('.selected-variant-section').empty();
                        $('.selected-variant-section').append(response.html);
                    }
                    else{
                        $('body').find('#variant_selection_check').val('0');
                        $('.add-to-order-btn').text('Add to Order');
                        $('.add-to-order-btn').prop('disabled', false);
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
        else {
            $('.save-order-btn').text('Processing');
            $('.save-order-btn').prop('disabled', true);
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
        // var button = $(this);
        // Swal.fire({
        //     title: ' Are you sure?',
        //     html:'<div class="text-center"> <p>Subtotal: '+ $(this).data('subtotal')+' USD<br>WeFullFill Paypal Fee ('+$(this).data('percentage')+'%): '+ $(this).data('fee')+' USD <br>Total Cost : '+ $(this).data('pay')+'</p>  </div><p> A amount of '+ $(this).data('pay') +' will be deducted through your Paypal Account</p>',
        //     icon: 'warning',
        //     showCancelButton: true,
        //     confirmButtonColor: '#3085d6',
        //     cancelButtonColor: '#d33',
        //     confirmButtonText: 'Pay'
        // }).then((result) => {
        //     if (result.value) {
        //         Swal.fire(
        //             'Processing!',
        //             'You will be redirected to paypal in seconds!',
        //             'success'
        //         );
        //         window.location.href = button.data('href');
        //     }
        // });


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
                window.location.href = button.data('href')+"?cost_to_pay="+button.data('pay');
            }
        });
    });

    $('body').on('click','.import_button',function () {
        $('#import-file-input').trigger('click');
    });

    $('body').on('change','#import-file-input',function () {
        $('#import-form').submit();
    });

    if($('body').find('#canvas-graph-one-users').length > 0){
        console.log('ok');
        var config = {
            type: 'bar',
            data: {
                labels: JSON.parse($('#canvas-graph-one-users').attr('data-labels')),
                datasets: [{
                    label: 'Order Count',
                    backgroundColor: '#00e2ff',
                    borderColor: '#00e2ff',
                    data: JSON.parse($('#canvas-graph-one-users').attr('data-values')),
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Summary Orders Count'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };

        var ctx = document.getElementById('canvas-graph-one-users').getContext('2d');
        window.myBar = new Chart(ctx, config);
    }

    if($('body').find('#canvas-graph-two-users').length > 0){
        console.log('ok');
        var config = {
            type: 'line',
            data: {
                labels: JSON.parse($('#canvas-graph-two-users').attr('data-labels')),
                datasets: [{
                    label: 'Orders Sales',
                    backgroundColor: '#5c80d1',
                    borderColor: '#5c80d1',
                    data: JSON.parse($('#canvas-graph-two-users').attr('data-values')),
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Summary Orders Sales'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Sales'
                        }
                    }]
                }
            }
        };

        var ctx_2 = document.getElementById('canvas-graph-two-users').getContext('2d');
        window.myLine = new Chart(ctx_2, config);
    }

    if($('body').find('#canvas-graph-three-users').length > 0){
        console.log('ok');
        var config = {
            type: 'line',
            data: {
                labels: JSON.parse($('#canvas-graph-three-users').attr('data-labels')),
                datasets: [{
                    label: 'Refunds',
                    backgroundColor: '#d18386',
                    borderColor: '#d14d48',
                    data: JSON.parse($('#canvas-graph-three-users').attr('data-values')),
                    fill: 'start',
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Summary Orders Refunds'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Refunds'
                        }
                    }]
                }
            }
        };

        var ctx_3 = document.getElementById('canvas-graph-three-users').getContext('2d');
        window.myLine = new Chart(ctx_3, config);
    }

    if($('body').find('#canvas-graph-four-users').length > 0){
        console.log('ok');
        var config = {
            type: 'line',
            data: {
                labels: JSON.parse($('#canvas-graph-four-users').attr('data-labels')),
                datasets: [{
                    label: 'Profit',
                    backgroundColor: '#61d154',
                    borderColor: '#61d154',
                    data: JSON.parse($('#canvas-graph-four-users').attr('data-values')),
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Summary Profit'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Profit'
                        }
                    }]
                }
            }
        };

        var ctx_4 = document.getElementById('canvas-graph-four-users').getContext('2d');
        window.myLine = new Chart(ctx_4, config);
    }


});
