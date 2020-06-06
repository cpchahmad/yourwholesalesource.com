$(document).ready(function () {

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
    /* Admin Module - Category Checkbox Selection JS */
    $('body').on('change','.category_checkbox',function () {
        if($(this).is(':checked')){
            $(this).parent().next().find('input[type=checkbox]').prop('checked',true);
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
            var checked = $(this).parents('.product_sub_cat').find('input[type=checkbox]:checked').length;
            if(checked === 0){
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
            $('.preview-drop').empty();
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
            let forms = new Array();
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
   /*Admin Module - Product STatus Change JS*/
    $('body').on('change','.status-switch',function () {
        var status = '';
        if($(this).is(':checked')){
            status = 1;
            $('.status-text').text('Published')
        }
        else{
            status = 0;
            $('.status-text').text('Draft')
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
        onChange: function(){
            var price = $('input[type="text"][name="price"]').val();
            var cost = $('input[type="text"][name="cost"]').val();
            var sku = $('input[type="text"][name="sku"]').val();
            var quantity = $('input[type="text"][name="quantity"]').val();
            var option1 = $('input[type="text"][name="option1"]').val();
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
                            '                                                    <td><input type="text" class="form-control" name="variant_price[]" placeholder="$0.00" value="' + price + '">\n' +
                            '                                                    </td>\n' +
                            '                                                    <td><input type="text" class="form-control" name="variant_cost[]" value="' + cost + '" placeholder="$0.00"></td>\n' +
                            '                                                    <td><input type="text" class="form-control" name="variant_quantity[]" value="'+quantity+'" placeholder="0"></td>\n' +
                            '                                                    <td><input type="text" class="form-control" name="variant_sku[]" value="' +sku+  '"></td>\n' +
                            '                                                    <td><input type="text" class="form-control" name="variant_barcode[]" placeholder=""></td>\n' +
                            '                                                </tr>');
                    });
                });
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
        $('.option_2').show();
        $('.option_btn_1').hide();
    });
    $('.option_btn_2').click(function () {
        $('.option_3').show();
        $('.option_btn_2').hide();
    });
    /*Fulfillment Control*/
    $('.fulfill_quantity').change(function () {
        if($(this).val() > $(this).attr('max')){
            $(this).val($(this).attr('max'));
            alertify.error('Please provide correct quantity of item!');
        }
        var total_fulfillable = 0;
        $('.fulfill_quantity').each(function () {
            total_fulfillable = total_fulfillable + parseInt($(this).val()) ;
        });
        $('.fulfillable_quantity_drop').empty();
        $('.fulfillable_quantity_drop').append(total_fulfillable+' of '+$('.fulfillable_quantity_drop').data('total'));
        if(total_fulfillable === 0) {
            $('.atleast-one-item').show();
            $('.fulfill_items_btn').attr('disabled',true);
        }
        else{
            $('.atleast-one-item').hide();
            $('.fulfill_items_btn').attr('disabled',false);
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
    /*Select Photos From Existing*/
    $('.choose-variant-image').click(function () {
        var current = $(this);
        $.ajax({
            url: '/variant/'+$(this).data('variant')+'/change/image/'+$(this).data('image')+'?type='+$(this).data('type'),
            type: 'GET',
            success:function (response) {
                if(response.message == 'success'){
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
});


