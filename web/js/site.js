$(document).ready(function () {
    $(document).off('click', '.minus-btn').on('click', '.minus-btn', function () {
        const input = $(this).siblings('.quantity-input');
        let val = parseInt(input.val());
        let min = parseInt(input.attr('min')) || 1;

        if (val > min) {
            input.val(val - 1);
        }
    });

    $(document).off('click', '.plus-btn').on('click', '.plus-btn', function () {
        const input = $(this).siblings('.quantity-input');
        let val = parseInt(input.val());
        let max = parseInt(input.attr('max')) || 999;

        if (val < max) {
            input.val(val + 1);
        }
    });

    // Category filter home

    $(document).on('change', '.category-checkbox', function () {
    const form = $('#category-filter-form');
    const data = form.serialize();

    $.pjax.reload({
        container: '#product-pjax',
        url: window.location.pathname + '?' + data,
        push: false,
        replace: false,
        timeout: 10000
    });
});

// signup

 $('#phone-input').on('input', function () {
        let val = $(this).val();
        if (val.length > 10) $(this).val(val.slice(0, 10));
    });

    $('#pincode-input').on('input', function () {
        let val = $(this).val();
        if (val.length > 6) $(this).val(val.slice(0, 6));
    });

   
    $('#signup-form').on('beforeSubmit', function () {
        $('#loader').fadeIn();
        return true;
    });

  
    $(document).ajaxError(function () {
        $('#loader').fadeOut();
    });



});