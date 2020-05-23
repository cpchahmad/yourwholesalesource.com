$(document).ready(function () {

    $('body').on('click','.upload-manager-profile',function () {
        $('.manager-profile').trigger('click');
    });

    $('body').on('change','.manager-profile',function () {
        readURL(this);
    });
    $('body').on('submit','#change_password_manager_form',function (e) {
       if($('input[name=new_password]').val() === $('input[name=new_password_again]').val()){
           alertify.success('submitting......');
       }
       else{
           e.preventDefault();
           alertify.error('New Password Mismatched!');
       }
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.image-drop').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }

    }
});
