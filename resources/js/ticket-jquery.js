$(document).ready(function () {
    $('#toggle__more__menu').click(function () {
        $this = $(this);
        $('.sidebar').toggleClass('close');
        $('#page__main__header').toggleClass('close');
    });

    $('#checkbox__select__all').click(function (event) {
        if (this.checked) {
            $(':checkbox').prop('checked', true);
        } else {
            $(':checkbox').prop('checked', false);
        }
    });
    
    // Clear modal input field when closed
    $('#modalForm').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    });

    // $('#ticketCheckbox').hide();
    // $('#btnSelectTicket').click(() => {
    //     $('#ticketCheckbox').toggle('fast');
    //     $('#ticketCheckbox:checked').prop('checked', false);
    //     $('#btnSelectTicket').toggleClass('ticket__checked');
    //     $('.ticket__checkbox').show();
    // });

    // $('#btnSelectTicket').click(() => {
    //     $('#ticketCheckbox').toggle('fast');
    //     $('.ticket__checkbox').show();
    // });
});

