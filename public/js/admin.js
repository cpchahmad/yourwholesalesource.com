$(document).ready(function () {
    $('body').on('change','.rate_type_select',function () {
        if ($(this).val() !== 'flat') {
            $('.condition-div').show();
            $('.max-condtion').attr('required','true');
            $('.min-condtion').attr('required','true');
        } else {
            $('.max-condtion').attr('required','false');
            $('.min-condtion').attr('required','false');
            $('.condition-div').hide();
        }
    });
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

    $('body').on('click','.dropzone',function () {
        $('.images-upload').trigger('click');
    });

    var storedFiles = [];
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
                    '            <div class="img-container fx-img-zoom-in fx-opt-slide-right">\n' +
                    '                <img class="img-responsive" src="'+e.target.result+'" alt="">\n' +
                    // '                <div class="img-options">\n' +
                    // // '                    <div class="img-options-content">\n' +
                    // // '                        <a class="btn btn-sm btn-default delete-file" data-file="'+f.name+'"><i class="fa fa-times"></i> Delete</a>\n' +
                    // // '                    </div>\n' +
                    // '                </div>\n' +
                    '            </div>\n' +
                    '        </div>');

            }
            reader.readAsDataURL(f);
        });
    });

    $('body').on('click','.add-option-div',function () {
        $(this).parent().hide();
        $(this).parent().next().show();
    });
    $('body').on('click','.delete-option-value',function () {
        $(this).parents('.div2').hide();
        $(this).parents('.div2').prev().show();
    });
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
    // $('body').on('click','.set_as_non_shopify_user',function () {
    //     $('#create_manager_form').find('#manager_name').val($(this).data('name'));
    //     $('#create_manager_form').find('#manager_email').val($(this).data('email'));
    //     $('#create_manager_form').submit();
    //
    // });

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

    $('body').on('click','.img-avatar-variant',function () {
        var target = $(this).data('form');
        $(target).find('input[type=file]').trigger('click');
    });
    $('.varaint_file_input').change(function () {
        $(this).parents('form').submit();
    });
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

});
