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
    $('body').on('change','.category_checkbox',function () {
        if($(this).is(':checked')){
            $(this).parent().next().find('input[type=checkbox]').prop('checked',true);
        }
        else{
            $(this).parent().next().find('input[type=checkbox]').prop('checked',false);
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

            var reader = new FileReader();
            reader.onload = function (e) {
                $('.preview-drop').append(' <div class="col-lg-4 preview-image animated fadeIn">\n' +
                                    '            <div class="img-container fx-img-zoom-in fx-opt-slide-right">\n' +
                                    '                <img class="img-responsive" src="'+e.target.result+'" alt="">\n' +
                                    '                <div class="img-options">\n' +
                                    '                    <div class="img-options-content">\n' +
                                    '                        <a class="btn btn-sm btn-default delete-file" data-file="'+f.name+'"><i class="fa fa-times"></i> Delete</a>\n' +
                                    '                    </div>\n' +
                                    '                </div>\n' +
                                    '            </div>\n' +
                                    '        </div>');

            }
            reader.readAsDataURL(f);
        });
    });
    $('body').on('click','.delete-file',function () {
        var file = $(this).data("file");
        for(var i=0;i<storedFiles.length;i++) {
            if(storedFiles[i].name === file) {
                storedFiles.splice(i,1);
                break;
            }
        }
        $(this).parents('.preview-image').remove();
    });

});
