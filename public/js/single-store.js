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
});
