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
});
