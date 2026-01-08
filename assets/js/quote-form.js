/*
*
* Contact JS
* @ThemeEaster
*/
$(function() {
    // Fix para Lenis smooth scroll: Agrega data-lenis-prevent a las listas de nice-select para habilitar el scroll
    setTimeout(function() {
        $('.nice-select .list').attr('data-lenis-prevent', '');
    }, 500);

    // Get the form.
    var form = $('#ajax_quote_form');

    // Get the messages div.
    var formMessages = $('#q-form-messages');
    formMessages.hide();

    // Set up an event listener for the contact form.
    $(form).submit(function(event) {
        // Stop the browser from submitting the form.
        event.preventDefault();

        // Serialize the form data.
        var formData = $(form).serialize();
        // Submit the form using AJAX.
        $.ajax({
            type: 'POST',
            url: $(form).attr('action'),
            data: formData
        })
        .done(function(response) {
            // Make sure that the formMessages div has the 'success' class.
            $(formMessages).removeClass('alert-danger');
            $(formMessages).addClass('alert-success');

            // Set the message text.
            $(formMessages).text(response);

            formMessages.show();

            setTimeout(function(){
                formMessages.hide();
            }, 5000);

            // Clear the form.
            $('#q-nombre').val('');
            $('#q-telefono').val('');
            $('#q-empresa').val('');
            $('#q-estado').val('');
            $('#q-litros').val('');
            $('#q-municipio').val('');
            $('#q-email').val('');
            $('#q-mensaje').val('');
            
            // Resetear nice-selects visualmente
            $('select').niceSelect('update');
            $('.nice-select .list').attr('data-lenis-prevent', '');
        })
        .fail(function(data) {
            // Make sure that the formMessages div has the 'error' class.
            $(formMessages).removeClass('alert-success');
            $(formMessages).addClass('alert-danger');

            // Set the message text.
            if (data.responseText !== '') {
                $(formMessages).text(data.responseText);
            } else {
                $(formMessages).text('¡Ups! Ocurrió un error y no se pudo enviar tu mensaje.');
            }

            formMessages.show();

            setTimeout(function(){
                formMessages.hide();
            }, 5000);

        });

    });

});
