$(document).on('submit', 'form[data-confirmation]', function (event) {
    var $form = $(this),
        $confirm = $('#confirmation-modal');

    if ($confirm.data('result') !== 'yes') {
        //cancel submit event
        event.preventDefault();

        $confirm
            .off('click', '#btn-yes')
            .on('click', '#btn-yes', function () {
                $confirm.data('result', 'yes');
                $form.find('input[type="submit"]').attr('disabled', 'disabled');
                $form.submit();
            })
            .modal('show');
    }
});