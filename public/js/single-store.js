$(document).ready(function () {
    $('body').on('click','.authenticate_user',function () {
        $('#authenticate_user_form').find('input[type=submit]').trigger('click');
    });
    $('body').on('submit','#authenticate_user_form',function (e) {
        e.preventDefault();
        $('.pre-loader').css('display','flex');
        var form  = $(this);
        $.ajax({
            url : form.attr('action'),
            type : 'post',
            data:form.serialize(),
            success:function (response) {
                $('.pre-loader').css('display','none');
                alertify.set('notifier','position', 'top-right');
                if(response.authenticate === true){
                    $('#associate_modal').modal('hide');
                    Swal.fire({
                        title: ' Are you sure?',
                        html:'<p>You want to Associate this store with this email ('+form.find('#user-email').val()+')</p>',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Associate it!'
                    }).then((result) => {
                        if (result.value) {
                            $('.pre-loader').css('display','flex');
                            $.ajax({
                                url : form.data('route'),
                                type:'post',
                                data:{
                                    _token :form.data('token'),
                                    store:form.data('store'),
                                    email :form.find('#user-email').val()
                                },
                                success:function (response) {
                                    $('.pre-loader').css('display','none');
                                    if(response.status === 'error'){
                                        alertify.error('Assigning Process Failed');
                                    }
                                    else{
                                        if(response.status === 'already_assigned'){
                                            alertify.message('Store ALready Assigned To Given Credentials');
                                        }
                                        else{
                                            Swal.fire(
                                                'Associated!',
                                                'Your store associated with given authentic credentials',
                                                'success'
                                            );
                                        }
                                        location.reload();
                                    }

                                }
                            })
                        }
                        else{
                            location.reload();
                        }
                    });
                }
                else{
                    alertify.error('Credentials Not Correct');
                }
            },
        });
    });

    $('body').on('click','.see-more-block',function () {
        $('.after12').show();
        $(this).hide();
    });
    $('body').on('click','.see-less-block',function () {
        $('.after12').hide();
        $('.see-more-block').show();

    });

    $('.js-tags-input').tagsInput({
        height: '36px',
        width: '100%',
        defaultText: 'Add tag',
        removeWithBackspace: true,
        delimiter: [',']
    });
    /*Retailer Module - Images Update JS*/
    $('body').on('click','.delete-file',function () {
        var $this = $(this);
        var file = $(this).data("file");
        $.ajax({
            url: $(this).data('route'),
            type: 'post',
            data: {
                _token: $(this).data('token'),
                request_type: $(this).data('type'),
                file: file,
            },
            success:function (data) {
                if(data.success === 'ok'){
                    $this.parents('.preview-image').remove();
                }
            }
        });
    });
    $('body').on('click','.img-avatar-variant',function () {
        var target = $(this).data('form');
        $(target).find('input[type=file]').trigger('click');
    });
    $('.varaint_file_input').change(function () {
        $(this).parents('form').submit();
    });

    /* Admin Module - Images UPLOAD JS */
    $('body').on('click','.dropzone',function () {
        $(this).next().trigger('click');
    });
    $('body').on('change','.images-upload',function (e) {
        var $this = $(this);
        var files = e.target.files;
        var filesArr = Array.prototype.slice.call(files);
        filesArr.forEach(function (f) {
            $this.parent().find('.preview-drop').empty();
            var reader = new FileReader();
            reader.onload = function (e) {
                $this.parent().find('.preview-drop').append(' <div class="col-lg-4 preview-image animated fadeIn">\n' +
                    '            <div class="img-fluid options-item">\n' +
                    '                <img class="img-fluid options-item" src="'+e.target.result+'" alt="">\n' +
                    '            </div>\n' +
                    '        </div>');

            }
            reader.readAsDataURL(f);
        });
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

    /*Ajax Forms Save*/
    /*Admin Module - Update Product  Save JS*/
    $('.btn_save_retailer_product').click(function () {
        $('.pre-loader').css('display','flex');
        var forms_div =  $(this).data('tabs');
        console.log($(forms_div).find('form').length);
        if($(forms_div).find('form').length > 0){
            let forms = new Array();
            $(forms_div).find('form').each(function () {
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
            ajaxCall(forms);
        }
    });

    $('.btn_save_my_product').click(function () {
        $('.pre-loader').css('display','flex');
        var forms_div = '.my_product_form_div';
        console.log($(forms_div).find('form').length);
        if($(forms_div).find('form').length > 0){
            let forms = new Array();
            $(forms_div).find('form').each(function () {
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
            $('.pre-loader').css('display', 'none');
            Swal.fire(
                'Updated!',
                'Your Product has been Updated Successfully',
                'success'
            );
            setTimeout(function () {
                window.location.reload();
            },1000)

        }
    }

    $('body').on('change','.select_all_checkbox',function () {
        if($(this).is(':checked')){
            $('.select_one_checkbox').prop('checked','checked');
            onSelectAllCommon();
        }
        else{
            $('.select_one_checkbox').prop('checked','');
            display($('.product-count'),true);
            display($('.selected-product-count'),false);
            display($('.checkbox_selection_options'),false);
        }
    });

    $('body').on('change','.select_one_checkbox',function () {
        if ($(this).is(':checked')) {
            $('.select_all_checkbox').prop('checked','checked');
            onSelectAllCommon();
        }
        else{
            var checked = $('.select_one_checkbox:checked').length;
            $('.selected-product-count').empty();
            $('.selected-product-count').append('  <p style="font-size: 13px;font-weight: 600">  Selected  '+checked+' products </p>');
            if(checked === 0){
                $('.select_all_checkbox').prop('checked','');
                display($('.product-count'),true);
                display($('.selected-product-count'),false);
                display($('.checkbox_selection_options'),false);
            }
        }
    });

    $('body').on('click','.import_all_btn ',function () {
        $('.pre-loader').css('display','flex');
        let forms = new Array();
        if($('.select_one_checkbox:checked').length > 0){

            $('.select_one_checkbox:checked').each(function () {
                forms.push({
                        'url' : $(this).data('url'),
                        'method' : $(this).data('method'),
                    });
            });
            StackAjax(forms,'import');
        }
        else{
            $('.pre-loader').css('display','none');
            alertify.error('Please Select One Product To Import!');
        }
    });

    $('body').on('click','.remove_all_btn ',function () {
        $('.pre-loader').css('display','flex');
        let forms = new Array();
        if($('.select_one_checkbox:checked').length > 0){

            $('.select_one_checkbox:checked').each(function () {
                forms.push({
                    'url' : $(this).data('remove_url'),
                    'method' : $(this).data('method'),
                });
            });
            StackAjax(forms,'remove');
        }
        else{
            $('.pre-loader').css('display','none');
            alertify.error('Please Select One Product To Remove!');
        }
    });

    function display($this,$option) {
        if($option){
            $this.addClass('d-inline-block');
            $this.removeClass('d-none');
        }
        else{
            $this.addClass('d-none');
            $this.removeClass('d-inline-block');
        }

    }

    function onSelectAllCommon() {

        display($('.product-count'),false);
        var selected = $('.select_one_checkbox:checked').length;
        $('.selected-product-count').empty();
        $('.selected-product-count').append('  <p style="font-size: 13px;font-weight: 600">  Selected  '+selected+' products </p>');
        display($('.selected-product-count'),true);
        display($('.checkbox_selection_options'),true);
    }
    function StackAjax(toAdd,call) {
        if (toAdd.length) {
            var request = toAdd.shift();
            var url = request.url;
            var type = request.method;

            $.ajax({
                url: url,
                type:type,
                success: function(response) {
                    StackAjax(toAdd,call);
                },
                error:function () {
                    StackAjax(toAdd,call);
                }
            });

        } else {
            $('.pre-loader').css('display', 'none');
            if(call === 'import'){
                Swal.fire(
                    'Imported!',
                    'Your Products Has Been Imported To Your Store Successfully',
                    'success'
                );
            }
            else{
                Swal.fire(
                    'Deleted!',
                    'Your Products Has Been Deleted Successfully',
                    'success'
                );
            }

            setTimeout(function () {
                window.location.reload();
            },1000)

        }
    }
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
});
