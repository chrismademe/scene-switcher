document.addEventListener('DOMContentLoaded', function() {

    var forEach = function (array, callback, scope) {
        for (var i = 0; i < array.length; i++) {
            callback.call(scope, i, array[i]); // passes back stuff we need
        }
    };

    // Add novalidate to forms to stop browser
    // default handling of errors
    forEach(document.querySelectorAll('[data-form]'), function (index, el) {
        el.setAttribute('novalidate', true);
    });

    // Get Form element
    var bpForm = document.querySelector('[data-form]');

    if ( bpForm ) {

        // Get Form
        bpForm.addEventListener('submit', function(e) {

            e.preventDefault();
            var id, data, form, formElement, button, buttonText;

            // Set the form
            form = e.target;

            // Get submit button
            button = form.querySelector('[type="submit"]');

            // Save submit button text
            buttonText = button.innerHTML;

            // Disabled the submit button
            button.innerHTML = 'Sending...';
            button.setAttribute('disabled', true);

            // Create Form Data
            data = new FormData(form);

            // Clear input highlighting
            var inputs = form.querySelectorAll('input, select, textarea');

            forEach(inputs, function(index, el) {
                el.classList.remove('is-invalid');
            });

            // Clear errors
            var errors = form.querySelectorAll('.form-error');

            forEach(errors, function(index, el) {
                el.parentNode.removeChild(el);
            });

            // Clear alerts
            var alerts = form.querySelector('.form-alerts');

            if ( alerts !== null ) {
                alerts.innerHTML = '';
            }

            // Validate
            fetch('/validate', {
                method: 'POST',
                body: data,
                cache: 'default'
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(response) {

                // All went OK
                if ( response.code == 200 ) {

                    // Submit the form
                    form.submit();

                // An error happened
                } else {

                    // Revert button text & re-enable
                    button.innerHTML = buttonText;
                    button.removeAttribute('disabled');

                    // Check for field validation errors
                    if ( response.hasOwnProperty('data') ) {

                        // Get errors for affected fields
                        for ( field in response.data.errors ) {

                            // Find the field
                            var current_field = form.querySelector('[name="'+ field +'"]');

                            // Check the input exists
                            if ( current_field !== null ) {

                                // Highlight it
                                current_field.classList.add('is-invalid');

                                // Create new p tag
                                var errorHTML = document.createElement('p');
                                errorHTML.className = 'form-error';
                                errorHTML.innerHTML = response.data.errors[field];

                                // Add an error below
                                current_field.parentNode.insertBefore(errorHTML, current_field.nextSibling);

                            }

                        }

                    // An Error happened but not with field validation
                    } else {

                        // Check for .form-alerts
                        if ( form.querySelector('.form-alerts').length == 0 ) {

                            // Create new element
                            var formAlerts = document.createElement('div');
                            formAlerts.className = 'form-alerts';
                            form.insertBefore(formAlerts, form.firstChild);

                        }

                        // Create an alert
                        var alert = document.createElement('div');
                        alert.classList.add('form-alert', 'negative');
                        alert.innerHTML = '<strong>Error:</strong> ' + response.message;

                        // Alert the user to what happened
                        if ( typeof formAlerts === 'undefined' ) {
                            var formAlerts = form.querySelector('.form-alerts');
                        }

                        // Get last alert in the list
                        var lastAlert = formAlerts.lastChild;

                        // Add this alert to the end of the list
                        formAlerts.insertBefore(alert, lastAlert.nextSibling);

                    }

                }

            });

        });

    }

});
