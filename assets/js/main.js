$(document).ready(function () {

    if ($('.second').length > 0) {
        $('.second').hide();
    }

    function validate(val, msg = null) {
        if (msg === null) {
            msg = 'É necessário informar um valor';
        }

        if (val === '') {
            alert(msg)
            return false;
        }

        return true;
    }

    $('.first-validate').click(function () {
        let res = validate($('#dish').val(), 'É necessário informar um prato');

        if (res) {
            $('.first').hide();
            $('.second').show();

            $('#dish-text').text($('#dish').val());
        }
    });

    $('.first-cancel').click(function () {
        alert('É necessário informar um prato');
    });

    $('.second-validate').click(function () {
        let res = validate($('#category').val());

        if (res) {
            $('form').submit();
        }
    });

    $('.second-cancel').click(function () {
        $('.second').hide();
        $('.first').show();
    })
});