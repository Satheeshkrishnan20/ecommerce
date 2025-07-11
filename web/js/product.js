
alert('hello');
console.log('JS loaded');

$(document).ready(function () {
    $('#toggleBtn').click(function () {
        $('#helloText').slideToggle();
    });

    // PJAX form submission
    $(document).on('submit', '#category-form', function (e) {
        e.preventDefault();
        $.pjax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            container: '#pjax-container',
            push: false,
            replace: false,
            timeout: 10000
        });
    });

    // Delete via AJAX
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let url = '/auth/admin/product/delete?id=' + id;

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $.pjax.reload({ container: '#pjax-container', timeout: 10000 });
                    alert(res.message);
                } else {
                    alert(res.message || 'Delete failed. Please try again.');
                }
            },
            error: function () {
                alert('Server error. Please try again.');
            }
        });
    });

    // Image preview modal
    $(document).on('click', '.preview-trigger', function () {
        const imgUrl = $(this).closest('a').data('image');
        $('#previewImage').attr('src', imgUrl);
        $('#imagePreviewModal').modal('show');
    });
});
