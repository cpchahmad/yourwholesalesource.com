$(document).ready(function () {

    // Email Template filters
    $('body').on('change','#non-shopify-filter',function () {
        if($(this).is(':checked')){
            $('.non-shopify-users').attr('checked', true);
        }
        else{
            $('.non-shopify-users').attr('checked', false);
        }
    });

    $('body').on('change','#shopify-filter',function () {
        if($(this).is(':checked')){
            $('.shopify-users').attr('checked', true);
        }
        else{
            $('.shopify-users').attr('checked', false);
        }
    });

    $('body').on('change','#shopify-with-order-filter',function () {
        if($(this).is(':checked')){
            $('.shopify-with-orders').attr('checked', true);
        }
        else{
            $('.shopify-with-orders').attr('checked', false);
        }
    });

    $('body').on('change','#non-shopify-with-order-filter',function () {
        if($(this).is(':checked')){
            $('.non-shopify-with-orders').attr('checked', true);
        }
        else{
            $('.non-shopify-with-orders').attr('checked', false);
        }
    });

    $('body').on('change','#shopify-with-products-filter',function () {
        if($(this).is(':checked')){
            $('.shopify-with-products').attr('checked', true);
        }
        else{
            $('.shopify-with-products').attr('checked', false);
        }
    });

    $('body').on('change','#shopify-without-products-filter',function () {
        if($(this).is(':checked')){
            $('.shopify-without-products').attr('checked', true);
        }
        else{
            $('.shopify-without-products').attr('checked', false);
        }
    });


    // Import To Woocommerce Button Loader
    $('.import-btn').click(function () {
        $(this).text('Loading');

    });

    // Product Tab Edit button
    $('.edit-tab-button').click(function () {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var description = $(this).data('description');

        var modal = $('.edit_tab_modal');
        modal.find('.tab-id').val(id);
        modal.find('.tab-title').val(title);
        console.log(description, modal.find('.tab-description'));
        modal.find('.tab-description').val(description);
        modal.modal("show");
    });

    // Category Filter
    $('.parent-category').change(function(){
        var id = $(this).val();
        $('.sub-categories-section').html('Please Wait..');

        $.ajax({
            url: `/category/${id}/get/sub-categories`,
            type: 'GET',
            success: function(res) {
                $('.sub-categories-section').empty().html(res);
            }
        });
    });

    // Sub Category Filter
    $(document).on('change', '.child-category', function(){
        var id = $(this).val();
        $('.sub-sub-categories-section').html(`Please Wait..`);

        $.ajax({
            url: `/sub-category/${id}/get/sub-sub-categories`,
            type: 'GET',
            success: function(res) {
                $('.sub-sub-categories-section').empty().html(res);
            }
        });
    });


    // Product Tiered Price Feature
    $(document).on('click', '.add-price-row-btn', function() {
        var id = $(this).attr('id');
        $(this).parent().parent().parent().append(`
              <div class="row mb-3">
                <div class="col-md-2">
                    <input  type="number" class="form-control" name="min_qty${id}[]">
                </div>
                <div class="col-md-2">
                    <input  type="number" class="form-control" name="max_qty${id}[]">
                </div>
                <div class="col-md-3">
                    <select name="type${id}[]" id="" class="form-control">
                        <option value="fixed">Fixed</option>
                        <option value="discount">Discount</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input  type="number" step="any" class="form-control" name="tiered_price${id}[]"  placeholder="$0.0">
                </div>
                <div class="col-md-2 btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-sm btn-primary add-price-row-btn">+</button>
                    <button type="button" class="btn btn-sm btn-danger remove-price-row-btn">-</button>
                </div>
            </div>
        `);

    });

    $(document).on('click', '.add-single-product-price-row-btn', function() {
        var id = $(this).attr('id');
        $(this).parent().parent().parent().append(`
              <div class="row mb-3">
                <div class="col-md-2">
                    <input  type="number" class="form-control" name="min_qty[]">
                </div>
                <div class="col-md-2">
                    <input  type="number" class="form-control" name="max_qty[]">
                </div>
                <div class="col-md-3">
                    <select name="type[]" id="" class="form-control">
                        <option value="fixed">Fixed</option>
                        <option value="discount">Discount</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input  type="number" step="any" class="form-control" name="tiered_price[]"  placeholder="$0.0">
                </div>
                <div class="col-md-2 btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-sm btn-primary add-single-product-price-row-btn">+</button>
                    <button type="button" class="btn btn-sm btn-danger remove-price-row-btn">-</button>
                </div>
            </div>
        `);

    });

    $(document).on('click', '.remove-price-row-btn', function() {
        $(this).parent().parent().empty();
    });

    $(document).on('click', '.remove-price-row-from-db-btn', function() {
        var id = $(this).data('item');
        var row = $(this).parent().parent();

        $.ajax({
            url: `/products/${id}/remove/tiered/price`,
            type: 'GET',
            success: function(res) {
                var response = res.data;
                if(response == 'success') {
                    row.empty();
                }
            }
        });
    });

    // Warehouse Bulk Quantity Feture

    $(document).on('input', '#bulk-warehouse-qty', function() {
        var bulk_min_qty = $(this).val();
        $('.warhouse-qty-row').each(function(){
            $(this).val(bulk_min_qty);
        });
    });

    // Product Tiered Price Feature End

    // Bulk Tiered Pricing Feature Start

    $(document).on('input', '#bulk-min-qty', function() {
       var bulk_min_qty = $(this).val();
        $('.min-qty-row').each(function(){
            $(this).val(bulk_min_qty);
        });
    });

    $(document).on('input', '#bulk-max-qty', function() {
        var bulk_max_qty = $(this).val();
        $('.max-qty-row').each(function(){
            $(this).val(bulk_max_qty);
        });
    });

    $(document).on('change', '#bulk-type', function() {
        var selected_option = $(this).children("option:selected").val();
        $('.type-row').each(function(){
            $(this).find(`option[value=${selected_option}]`).attr('selected',true);
        });
    });

    $(document).on('input', '#bulk-price', function() {
        var bulk_price = $(this).val();
        $('.price-row').each(function(){
            $(this).val(bulk_price);
        });
    });

    // Bulk Tiered Pricing Feature End

    // Bulk Variant Pricing & Cost Feature Start

    $(document).on('input', '#bulk-var-price', function() {
        var bulk_price = $(this).val();
        $('.var-price-row').each(function(){
            $(this).val(bulk_price);
        });
    });

    $(document).on('input', '#bulk-var-cost', function() {
        var bulk_cost = $(this).val();
        $('.var-cost-row').each(function(){
            $(this).val(bulk_cost);
        });
    });

    // Bulk Variant Pricing & Cost Feature End

    var radioState;

    $('#example-radio-best-seller').on('click', function(e) {
        if (radioState === this) {
            this.checked = false;
            radioState = null;
        } else {
            radioState = this;
        }
    });

    $('#example-radio-winning-product').on('click', function(e) {
        if (radioState === this) {
            this.checked = false;
            radioState = null;
        } else {
            radioState = this;
        }
    });

    /*Admin Module - Rate Type Selection*/
    $('body').on('change','.rate_type_select',function () {
        if ($(this).val() !== 'flat') {
            $('.condition-div').show();
            // $('.max-condtion').attr('required','true');
            $('.min-condtion').attr('required','true');
        } else {
            // $('.max-condtion').attr('required','false');
            $('.min-condtion').attr('required','false');
            $('.condition-div').hide();
        }
    });
    /* Admin Module - Category Open JS */
    $('body').on('click','.category_down',function () {
        if($(this).data('value') === 0){
            $(this).find('i').addClass('fa-angle-down');
            $(this).find('i').removeClass('fa-angle-right');
            $(this).data('value',1);

        }
        else{
            $(this).find('i').removeClass('fa-angle-down');
            $(this).find('i').addClass('fa-angle-right');
            $(this).data('value',0);
        }
        $(this).next().next().toggle();
    });
    /* Admin Module - Sub Category Open JS */
    $('body').on('click','.sub_category_down',function () {
        if($(this).data('value') === 0){
            $(this).find('i').addClass('fa-angle-down');
            $(this).find('i').removeClass('fa-angle-right');
            $(this).data('value',1);

        }
        else{
            $(this).find('i').removeClass('fa-angle-down');
            $(this).find('i').addClass('fa-angle-right');
            $(this).data('value',0);
        }
        $(this).next().next().toggle();
    });
    /* Admin Module - Category Checkbox Selection JS */
    $('body').on('change','.category_checkbox',function () {
        if($(this).is(':checked')){
            //$(this).parent().next().find('input[type=checkbox]').prop('checked',true);
            $(this).parent().next().show();
        }
        else{
            $(this).parent().next().find('input[type=checkbox]').prop('checked',false);
            $(this).parent().next().hide();
        }
    });
    /* Admin Module - SubCategory Checkbox Selection JS */
    $('body').on('change','.sub_cat_checkbox',function () {

        if($(this).is(':checked')){
            $(this).parents('.product_sub_cat').prev().find('.category_checkbox').prop('checked',true);

        }
        else{
            console.log('sub-unch');
            var checked = $(this).parents('.product_sub_cat').find('input[type=checkbox]:checked').length;
            if(checked === 0){
                $(this).parents('.product_sub_cat').prev().find('.category_checkbox').prop('checked',false);
            }
        }
    });

    /* Admin Module - SubSubCategory Checkbox Selection JS */
    $('body').on('change','.sub_sub_cat_checkbox',function () {
        if($(this).is(':checked')){

            $(this).parents('.product_sub_sub_cat').prev().find('.sub_cat_checkbox').prop('checked',true);
            $(this).parents('.product_sub_cat').prev().find('.category_checkbox').prop('checked',true);
        }
        else{
            var checked = $(this).parents('.product_sub_sub_cat').find('input[type=checkbox]:checked').length;
            if(checked === 0){
                $(this).parents('.product_sub_sub_cat').prev().find('.sub_cat_checkbox').prop('checked',false);
                $(this).parents('.product_sub_cat').prev().find('.category_checkbox').prop('checked',false);
            }
        }
    });
    /* Admin Module - Dropzone Click JS */
    $('body').on('click','.dropzone',function () {
        $('.images-upload').trigger('click');
    });

    var storedFiles = [];
    /* Admin Module - Images UPLOAD JS */
    $('body').on('change','.images-upload',function (e) {
        var files = e.target.files;
        var filesArr = Array.prototype.slice.call(files);
        filesArr.forEach(function (f) {

            if (!f.type.match("image.*")) {
                return;
            }
            storedFiles.push(f);
            console.log(storedFiles);
            //$('.preview-drop').empty();
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.preview-drop').append(' <div class="col-lg-4 preview-image animated fadeIn">\n' +
                    '            <div class="img-fluid options-item">\n' +
                    '                <img class="img-fluid options-item" src="'+e.target.result+'" alt="">\n' +
                    '            </div>\n' +
                    '        </div>');

            }
            reader.readAsDataURL(f);
        });
    });

    /* Admin Module - Add Option of Variants JS */
    $('body').on('click','.add-option-div',function () {
        $(this).parent().hide();
        $(this).parent().next().show();
    });
    /* Admin Module - Delete Option of Variants JS */
    $('body').on('click','.delete-option-value',function () {
        $(this).parents('.div2').hide();
        $(this).parents('.div2').prev().show();
    });
    /*Admin Module - Remove Option of Variants JS*/
    $('body').on('click','.remove-option',function () {
        $(this).parents('.badge').hide();
        $('.variant-options-update-save').data('deleted','1');
        var new_val =  $(this).parents('.badge').find('span').text();
        var value = "";
        if($(this).data('option') == 'option1'){
            $('#variant-options-update').append('<input type="hidden" class="delete_option1" name="delete_option1[]" value="'+new_val+'">');
            $('.delete_option1').each(function () {
                if(value === ""){
                    value = $(this).val();
                }
                else{
                    value = value+', '+$(this).val();
                }
            });
            $('.variant-options-update-save').data('option1',value);
        }
        else if($(this).data('option') == 'option2'){
            $('#variant-options-update').append('<input type="hidden" class="delete_option2" name="delete_option2[]" value="'+new_val+'">');
            $('.delete_option2').each(function () {
                if(value === ""){
                    value = $(this).val();
                }
                else{
                    value = value+', '+$(this).val();
                }

            });
            $('.variant-options-update-save').data('option2',value);
        }
        else{
            $('#variant-options-update').append('<input type="hidden" class="delete_option3" name="delete_option3[]" value="'+new_val+'">');
            $('.delete_option3').each(function () {
                if(value === ""){
                    value = $(this).val();
                }
                else{
                    value = value+', '+$(this).val();
                }
            });
            $('.variant-options-update-save').data('option3',value);
        }
    });

    /*Admin Module - Save Options of Variants JS*/
    // $('body').on('click', '.variant-selected-options-update-save', function() {
    //
    //     var option1 = $(".js-tags-options-update").val();
    //     $('.old-option-1').val(option1);
    //   //  var option1Array = option1.split(',');
    //     $('.old-option1-update-form').submit();
    //
    //
    // });

    $('body').on('click', '.update-option-1-btn', function() {

        $('.old-option1-update-form').submit();


    });


    $('body').on('click','.variant-options-update-save',function () {
        if($(this).data('deleted') === '1'){
            $(this).next().trigger('click');
            var option1 = "";
            var option2 = "";
            var option3 = "";
            if($(this).data('option1') !== ""){
                option1 =  '<li style="width: max-content">Option1 : '+$(this).data("option1")+'</li>';
            }

            if($(this).data('option2') !== ""){
                option2 =  '<li style="width: max-content">Option2 : '+$(this).data("option2")+'</li>';

            }
            if($(this).data('option3') !== ""){
                option3 =  '<li style="width: max-content">Option3 : '+$(this).data("option3")+'</li>';

            }
            Swal.fire({
                title: ' Are you sure?',
                html:'<p>Deleting these options will cause permanent deletion of their related variant!</p>'+
                    '<ul style="margin: 0px 44px;">' +
                    option1+option2+option3+
                    '</ul>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Deleted!',
                        'Your options and its variants has been deleted.',
                        'success'
                    );
                    $('#variant-options-update').submit();
                }
                else{
                    location.reload();
                }
            });
        }
        else if($('.option-value').val() !== ""){
            $('.new-option-add').submit();
        }
        else{
            Swal.fire(
                'Alert!',
                'Please First Add or Delete SomeOption',
                'warning'
            )
        }

    });

    /*Admin Module - Product Images Save JS*/
    $('body').on('click', '.save-img', function() {
        console.log(324);
        $('.product-images-form').submit();
    });


    $('body').on('submit','.product-images-form',function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url : $(this).attr('action'),
            type : $(this).attr('method'),
            data : formData,
            cache:false,
            contentType: false,
            processData: false,
        });
    });
    /*Admin Module - Update Product  Save JS*/
    $('.submit_all').click(function () {
        $('.pre-loader').css('display','flex');
        if($('#forms-div').find('form').length > 0){
            let forms = [];
            $('#forms-div').find('form').each(function () {
                if($(this).hasClass('product-images-form')){
                    $(this).submit();
                }
                else{
                    forms.push({
                        'data' : $(this).serialize(),
                        'url' : $(this).attr('action'),
                        'method' : $(this).attr('method'),
                    });
                }

            });
            if($('.variants-div').find('form').length > 0) {
                $('.variants-div').find('form').each(function () {
                    forms.push({
                        'data': $(this).serialize(),
                        'url': $(this).attr('action'),
                        'method': $(this).attr('method'),
                    });
                });
            }
            ajaxCall(forms);
        }
    });
    /*Stack ajax*/
    function ajaxCall(toAdd) {
        if (toAdd.length) {
            var request = toAdd.shift();
            var data = request.data;
            var url = request.url;
            var type = request.method;

            $.ajax({
                url: url,
                type:type,
                data: data,
                success: function(response) {
                    ajaxCall(toAdd);
                },
                error:function () {
                    ajaxCall(toAdd);
                }
            });

        } else {
            $.ajax({
                url: $('#notification').data('route'),
                type:'GET',
            });
            window.location.reload();
        }
    }
    /*Admin Module - Variant Image Change JS*/
    $('body').on('click','.img-avatar-variant',function () {
        var target = $(this).data('form');
        $(target).find('input[type=file]').trigger('click');
    });
    $('.varaint_file_input').change(function () {
        $(this).parents('form').submit();
    });
    /*Admin Module - Image Delete JS*/
    $('body').on('click','.delete-file',function () {
        var $this = $(this);
        var file = $(this).data("file");
        $.ajax({
            url: $(this).data('route'),
            type: 'post',
            data: {
                _token: $(this).data('token'),
                type: $(this).data('type'),
                file: file,
            },
            success:function (data) {
                if(data.success === 'ok'){
                    $this.parents('.preview-image').remove();
                }
            }
        });
    });
   /*Admin Module - Product Status Change JS*/
    $('body').on('change','.status-switch',function () {
        var status = '';
        if($(this).is(':checked')){
            status = 1;
            $(this).next().text('Published')
        }
        else{
            status = 0;
            $(this).next().text('Draft')
        }
        $.ajax({
            url: $(this).data('route'),
            type: 'post',
            data:{
                _token: $(this).data('csrf'),
                type : 'status_update',
                status : status
            }
        })
    });

    /*Admin Module - Email Template Status Change JS*/
    $('body').on('change','.template-status-switch',function () {
        var status = '';
        if($(this).is(':checked')){
            status = 1;
            $(`.status-text_${$(this).data('template')}`).text('Published')
        }
        else{
            status = 0;
            $(`.status-text_${$(this).data('template')}`).text('Draft')
        }
        $.ajax({
            url: $(this).data('route'),
            type: 'post',
            data:{
                _token: $(this).data('csrf'),
                template : $(this).data('template'),
                status : status
            }
        })
    });

    /*Input Tag Script JS*/
    $('.js-tags-input').tagsInput({
        height: '36px',
        width: '100%',
        defaultText: 'Add tag',
        removeWithBackspace: true,
        delimiter: [',']
    });
    $('.js-tags-options').tagsInput({
        height: '36px',
        width: '100%',
        defaultText: 'Add tag',
        removeWithBackspace: true,
        onRemoveTag:function(){
            var option1 = $('input[type="text"][name="option1"]').val();
            var option2 = $('input[type="text"][name="option2"]').val();
            if(option1 === ''){
                $('input[type="text"][name="option2"]').val('');
                $('input[type="text"][name="option3"]').val('');
                $('.option_2').hide();
                $('.option_3').hide();
                $('.option_btn_2').hide();
                $('.option_btn_1').show();
                $('.variants_table').hide();
                $("tbody").empty();

            }
            if(option2 === ''){
                $('input[type="text"][name="option3"]').val('');
                $('.option_3').hide();
                $('.option_btn_2').show();


            }
        },
        onChange: function(){
            var price = $('input[type="number"][name="price"]').val();
            var cost = $('input[type="number"][name="cost"]').val();
            var sku = $('input[type="text"][name="sku"]').val();
            var quantity = $('input[type="number"][name="quantity"]').val();
            var option1 = $('input[type="text"][name="option1"]').val();
            console.log(option1);
            var option2 = $('input[type="text"][name="option2"]').val();
            var option3 = $('input[type="text"][name="option3"]').val();
            var substr1 = option1.split(',');
            var substr2 = option2.split(',');
            var substr3 = option3.split(',');
            $('.variants_table').show();
            $("tbody").empty();
            var title = '';
            jQuery.each(substr1, function (index1, item1) {
                title = item1;
                jQuery.each(substr2, function (index2, item2) {
                    if(item2 !== ''){
                        title = item1+'/'+item2;
                    }
                    jQuery.each(substr3, function (index3, item3) {

                        if(item3 !== ''){
                            title = item1+'/'+item2+'/'+item3;
                        }

                        $('tbody').append('   <tr>\n' +
                            '                                                    <td class="variant_title">' + title + '<input type="hidden" name="variant_title[]" value="' + title + '"></td>\n' +
                            '                                                    <td><input type="number" step="any" class="form-control" name="variant_price[]" placeholder="$0.00" value="' + price + '">\n' +
                            '                                                    </td>\n' +
                            '                                                    <td><input type="number" step="any" class="form-control" name="variant_cost[]" value="' + cost + '" placeholder="$0.00"></td>\n' +
                            '                                                    <td><input type="number" step="any" class="form-control" name="variant_quantity[]" value="'+quantity+'" placeholder="0"></td>\n' +
                            '                                                    <td><input type="text" class="form-control" name="variant_sku[]" value="' +sku+  '"></td>\n' +
                            '                                                    <td><input type="text" class="form-control" name="variant_barcode[]" placeholder=""></td>\n' +
                            '                                                </tr>');
                    });
                });
            });
        },
        delimiter: [',']
    });

    $('.js-tags-options1-update').tagsInput({
        height: '36px',
        width: '100%',
        defaultText: 'Add more tags to option',
        removeWithBackspace: true,
        onRemoveTag:function(){
            var option1 = $('input[type="text"][name="option1-update"]').val();
            if(option1 === ''){
                $('.variants_table').hide();
                $(".option-1-table-body").empty();
            }
        },
        onChange: function(){
            var price = $('input[type="number"][name="price"]').val();
            var cost = $('input[type="number"][name="cost"]').val();
            var sku = $('input[type="text"][name="sku"]').val();
            var quantity = $('input[type="number"][name="quantity"]').val();
            var option1 = $('input[type="text"][name="option1-update"]').val();
            console.log()
            var substr1 = option1.split(',');
            console.log(substr1);
            $('.variants_table').show();
            $(".option-1-table-body").empty();
            var title = '';
            jQuery.each(substr1, function (index1, item1) {
                title = item1;
                $('.option-1-table-body').append('   <tr>\n' +
                    '                                                    <td class="variant_title">' + title + '<input type="hidden" name="variant_title[]" value="' + title + '"></td>\n' +
                    '                                                    <td><input type="number" step="any" class="form-control" name="variant_price[]" placeholder="$0.00" value="' + price + '">\n' +
                    '                                                    </td>\n' +
                    '                                                    <td><input type="number" step="any" class="form-control" name="variant_cost[]" value="' + cost + '" placeholder="$0.00"></td>\n' +
                    '                                                    <td><input type="number" step="any" class="form-control" name="variant_quantity[]" value="'+quantity+'" placeholder="0"></td>\n' +
                    '                                                    <td><input type="text" class="form-control" name="variant_sku[]" value="' +sku+  '"></td>\n' +
                    '                                                    <td><input type="text" class="form-control" name="variant_barcode[]" placeholder=""></td>\n' +
                    '                                                </tr>');
            });
        },
        delimiter: [',']
    });


    $('input[type="checkbox"][name="variants"]').click(function () {
        if ($(this).prop("checked") == true) {
            $('.variant_options').show();
        } else if ($(this).prop("checked") == false) {
            $('.variant_options').hide();
        }
    });
    $('.option_btn_1').click(function () {
        if($(this).prev().find('.options-preview').val() !== ''){
            $('.option_2').show();
            $('.option_btn_1').hide();
        }
        else{
            alertify.error('The Option1 must have atleast one option value');
        }

    });
    $('.option_btn_2').click(function () {
        if($(this).prev().find('.options-preview').val() !== ''){
            $('.option_3').show();
            $('.option_btn_2').hide();
        }
        else{
            alertify.error('The Option2 must have atleast one option value');
        }
    });
    /*Fulfillment Control*/
    $('.fulfill_quantity').change(function () {
        if($(this).val() > $(this).attr('max')){
            $(this).val($(this).attr('max'));
            alertify.error('Please provide correct quantity of item!');
        }
        var total_fulfillable = 0;
        $('body').find('.fulfill_quantity').each(function () {
            total_fulfillable = total_fulfillable + parseInt($(this).val()) ;
        });
        $('.fulfillable_quantity_drop').empty();
        $('.fulfillable_quantity_drop').append(total_fulfillable+' of '+$('.fulfillable_quantity_drop').data('total'));
        if(total_fulfillable === 0) {
            $('.atleast-one-item').show();
            $('.fulfill_items_btn').attr('disabled',true);
            $('.bulk_fulfill_items_btn').attr('disabled',true);

        }
        else{
            $('.atleast-one-item').hide();
            $('.fulfill_items_btn').attr('disabled',false);
            $('.bulk_fulfill_items_btn').attr('disabled',false);

        }

    });

    $('.fulfill_items_btn').click(function () {
        var total_fulfillable = 0;
        $('.fulfill_quantity').each(function () {
            total_fulfillable = total_fulfillable + parseInt($(this).val()) ;
        });
        if(total_fulfillable > 0) {
           $('#fulfilment_process_form').submit();
        }
        else{
            $('.atleast-one-item').hide();
            $('.fulfill_items_btn').attr('disabled',false);
        }
    });

    /*Bulk Fulfillment*/
    $('.bulk_fulfill_items_btn').click(function () {
        var total_fulfillable = 0;
        $('.fulfill_quantity').each(function () {
            total_fulfillable = total_fulfillable + parseInt($(this).val()) ;
        });
        if(total_fulfillable > 0) {
            $('.pre-loader').css('display','flex');
            if($('.fulfilment_process_form').length > 0){
                let forms = [];
              $('.fulfilment_process_form').each(function () {
                    var total_fulfillable_form = 0;
                  $(this).find('.fulfill_quantity').each(function () {
                        total_fulfillable_form = total_fulfillable_form + parseInt($(this).val()) ;
                    });

                  if(total_fulfillable_form > 0){
                        forms.push({
                            'data' : $(this).serialize(),
                            'url' : $(this).attr('action'),
                            'method' : $(this).attr('method'),
                        });
                    }

                });
                console.log(forms);
                BulkAjaxCall(forms);
            }
        }
        else{
            $('.atleast-one-item').hide();
            $('.fulfill_items_btn').attr('disabled',false);
        }
    });
    function BulkAjaxCall(toAdd) {
        if (toAdd.length) {
            var request = toAdd.shift();
            var data = request.data;
            var url = request.url;
            var type = request.method;

            $.ajax({
                url: url,
                type:type,
                data: data,
                success: function(response) {
                    BulkAjaxCall(toAdd);
                },
                error:function () {
                    BulkAjaxCall(toAdd);
                }
            });

        } else {
            window.location.href = $('.bulk_fulfill_items_btn').attr('data-redirect');
        }
    }


    /*Select Photos From Existing*/
    $('.choose-variant-image').click(function () {
        var current = $(this);
        $.ajax({
            url: '/variant/'+$(this).data('variant')+'/change/image/'+$(this).data('image')+'?type='+$(this).data('type'),
            type: 'GET',
            success:function (response) {
                if(response.message === 'success'){
                    current.removeClass('bg-info');
                    current.addClass('bg-success');
                    current.text('Updated');
                    alertify.success('Variant image has been updated!');
                    current.parents('.modal').prev()
                        .attr('src', current.prev().attr('src'));
                }
                else{
                    alertify.error('Something went wrong!');
                }
            }
        })

    });
    /* Image Re-arrange JS */
    $('#image-sortable').sortable({
        update: function(event, ui) {
            var orders = [];
            $(this).find('.preview-image').each(function () {
                orders.push($(this).data('id'));
            });
            console.log(orders);
            $.ajax({
                url: $('#image-sortable').data('route'),
                method:'get',
                data:{
                    positions: orders,
                    product: $('#image-sortable').data('product'),
                },
                success:function (response) {
                    if(response.message === 'success'){
                        alertify.success('Image Position Changed Successfully!');
                    }
                    else{
                        alertify.error('Internal Server Error!');

                    }
                },
                error:function(){
                    alertify.error('Internal Server Error!');
                }
            });
        }
    });
    // $( "#image-sortable" ).disableSelection();

    $('#category-sortable').sortable({
        update: function(event, ui) {
            var orders = [];
            $(this).find('.preview-category').each(function () {
                orders.push($(this).data('id'));
            });
            console.log(orders);
            $.ajax({
                url: $('#category-sortable').data('route'),
                method:'get',
                data:{
                    positions: orders,
                    category: $('#category-sortable').data('category'),
                },
                success:function (response) {
                    if(response.message === 'success'){
                        alertify.success('Image Position Changed Successfully!');
                    }
                    else{
                        alertify.error('Internal Server Error!');

                    }
                },
                error:function(){
                    alertify.error('Internal Server Error!');
                }
            });
        }
    });

    /* Approve Bank Transfer JS */
    $('body').on('click','.approve-bank-transfer-button',function () {
        var button = $(this);
            Swal.fire({
                title: ' Are you sure?',
                html:'<p> A amount of '+ $(this).data('amount') +' will be added to wallet number '+ $(this).data('wallet')+' !</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Approved!',
                        'Amount added to wallet!',
                        'success'
                    );
                   window.location.href = button.data('route');
                }
            });
    });

    $('body').on('keyup','#search-create-input-stores-users',function () {
        $.ajax({
            url: $(this).data('route'),
            type: 'GET',
            data:{
              search : $(this).val(),
            },
            success:function (response) {
                if(response.message === 'success'){
                    $('.drop-content').empty();
                    $('.drop-content').append(response.html);
                }
            }
        })

    });


    $('body').on('keyup','#search-edit-input-stores-users',function () {
        $.ajax({
            url: $(this).data('route'),
            type: 'GET',
            data:{
                search : $(this).val(),
                id:$(this).data('manager'),
            },
            success:function (response) {
                if(response.message === 'success'){
                    $('.drop-content').empty();
                    $('.drop-content').append(response.html);
                }
            }
        })

    });

    $('body').on('change','.preference-check',function () {
        console.log(3);
        if($(this).val() === '0'){
            $(this).parents('.form-group').next().show();
            $(this).parents('.form-group').next().find('.shop-preference').attr('required',true);
        }
        else{
            $(this).parents('.form-group').next().hide();
            $(this).parents('.form-group').next().find('.shop-preference').attr('required',false);
        }
    });

    $('body').on('change','.preference-fixed',function () {
        console.log(34);
        if($(this).val() === '0'){
            $(this).parents('.form-group').next().show();
            $(this).parents('.form-group').next().find('.shop-preference').attr('required',true);
        }
        else{
            $(this).parents('.form-group').next().hide();
            $(this).parents('.form-group').next().find('.shop-preference').attr('required',false);
        }
    });


    if(!$('body').find('.rating-stars').hasClass('disabled')){
        /* 1. Visualizing things on Hover - See next part for action on click */
        $('body').on('mouseover','#stars li',function(){
            // $('#stars li').on('mouseover', function(){
            var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

            // Now highlight all the stars that's not after the current hovered star
            $(this).parent().children('li.star').each(function(e){
                if (e < onStar) {
                    $(this).addClass('hover');
                }
                else {
                    $(this).removeClass('hover');
                }
            });

        })
        $('body').on('mouseout','#stars li',function(){
            $(this).parent().children('li.star').each(function(e){
                $(this).removeClass('hover');
            });
        });


        /* 2. Action to perform on click */
        $('body').on('click','#stars li',function(){
            // $('#stars li').on('click', function(){
            $('#rating-input').val($(this).data('value'));
            var onStar = parseInt($(this).data('value'), 10); // The star currently selected
            var stars = $(this).parent().children('li.star');

            for (i = 0; i < stars.length; i++) {
                $(stars[i]).removeClass('selected');
            }

            for (i = 0; i < onStar; i++) {
                $(stars[i]).addClass('selected');
            }



        });
    }

    if($('body').find('input[name=rating]').length > 0){
        $('input[name=rating]').each(function () {
            var rating = $(this).val();
            $(this).closest('div').find('.star').each(function (index) {
                if(index < rating){
                    $(this).addClass('selected');
                }
            })
        });

    }



    if($('body').find('#canvas-graph-one').length > 0){
        console.log('ok');
        var config = {
            type: 'bar',
            data: {
                labels: JSON.parse($('#canvas-graph-one').attr('data-labels')),
                datasets: [{
                    label: 'Order Count',
                    backgroundColor: '#00e2ff',
                    borderColor: '#00e2ff',
                    data: JSON.parse($('#canvas-graph-one').attr('data-values')),
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

        var ctx = document.getElementById('canvas-graph-one').getContext('2d');
        window.myBar = new Chart(ctx, config);
    }

    if($('body').find('#canvas-graph-two').length > 0){
        console.log('ok');
        var config = {
            type: 'line',
            data: {
                labels: JSON.parse($('#canvas-graph-two').attr('data-labels')),
                datasets: [{
                    label: 'Orders Sales',
                    backgroundColor: '#5c80d1',
                    borderColor: '#5c80d1',
                    data: JSON.parse($('#canvas-graph-two').attr('data-values')),
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

        var ctx_2 = document.getElementById('canvas-graph-two').getContext('2d');
        window.myLine = new Chart(ctx_2, config);
    }

    if($('body').find('#canvas-graph-three').length > 0){
        console.log('ok');
        var config = {
            type: 'line',
            data: {
                labels: JSON.parse($('#canvas-graph-three').attr('data-labels')),
                datasets: [{
                    label: 'Refunds',
                    backgroundColor: '#d18386',
                    borderColor: '#d14d48',
                    data: JSON.parse($('#canvas-graph-three').attr('data-values')),
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

        var ctx_3 = document.getElementById('canvas-graph-three').getContext('2d');
        window.myLine = new Chart(ctx_3, config);
    }

    if($('body').find('#canvas-graph-four').length > 0){
        console.log('ok');
        var config = {
            type: 'line',
            data: {
                labels: JSON.parse($('#canvas-graph-four').attr('data-labels')),
                datasets: [{
                    label: 'Stores',
                    backgroundColor: '#61d154',
                    borderColor: '#61d154',
                    data: JSON.parse($('#canvas-graph-four').attr('data-values')),
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Summary New Stores'
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
                            labelString: 'Stores'
                        }
                    }]
                }
            }
        };

        var ctx_4 = document.getElementById('canvas-graph-four').getContext('2d');
        window.myLine = new Chart(ctx_4, config);
    }

    $('.check-order-all').change(function () {
        unset_bulk_array()
        set_bulk_array();

        if($(this).is(':checked')){
            $('.bulk-div').show();
        }
        else{
            $('.bulk-div').hide();

        }

    });
    $('.check-order').change(function () {
        if($(this).is(':checked')){
            $('.bulk-div').show();
            unset_bulk_array();
            set_bulk_array();
        }
        else{
            unset_bulk_array();
            set_bulk_array();
            if($('.check-order:checked').length === 0){
                $('.bulk-div').hide();
            }

        }

    });
    function set_bulk_array() {
        var values = [];
        $('.check-order:checked').each(function () {
            values.push($(this).val());
        });

        $('#bulk-fullfillment').find('input:hidden[name=orders]').val(values);

    }
    function unset_bulk_array() {
        $('#bulk-fullfillment').find('input:hidden[name=orders]').val('');

    }
    $('.bulk-fulfill-btn').click(function () {
       $('#bulk-fullfillment').submit();
    });

    $('body').on('change','#import-tracking',function () {
        Swal.fire({
            title: ' Are you sure?',
            html:'<p>You want to add tracking details in all pending orders</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Add it!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Processing!',
                    'All Pending Orders are under processed. Please wait on this page. Dont Refresh the page!',
                    'success'
                );
                setTimeout(function () {
                    Swal.close();
                    $('.pre-loader').css('display','flex');
                    $('#import-tracking').closest('form').submit();
                },2000);

            }
            else{
               $('#import-tracking').val('');
            }
        });
    });


    $('#custom_order_form').submit(function (e) {
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            url : $form.attr('action'),
            type : $form.attr('method'),
            data : $form.serialize(),
            success: function (data) {
                if(data.status == 'success'){
                    if(data.payment == 'paypal'){
                        $('#paypal_pay_trigger').html(data.popup);
                        $('.ajax_paypal_form_submit').html(data.form);
                        triggerPaypal(data.cost);
                        $('#paypal_pay_trigger').modal('show');
                    }else{
                        window.location.href = data.redirect_url;
                    }
                }else{
                    alert('something went wrong.');
                }
            },
            error: function () {
                alert('something went wrong');
            }
        });
    });
});


function triggerPaypal(price){
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: price
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                $('.ajax_paypal_form_submit').find('textarea').val(JSON.stringify(details));
                $('.ajax_paypal_form_submit form').submit();
            });
        }
    }).render('#paypal-button-container');
}

