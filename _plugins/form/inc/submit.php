<?php

use Form\Form;
use Form\JSON;

/**
 * This is accessed by sending post data
 * to /submit/<formid> and will set
 * alerts using get('form.alerts')
 *
 * On success, an alert will be set. If the
 * 'location.success' option is set in the config
 * they will redirected to that URL
 */

// Get Form ID
$_id = ( isset( $_POST['bp-form-id'] ) ? $_POST['bp-form-id'] : get('page.index.1') );

// Remove ID from $_POST
if ( isset( $_POST['bp-form-id'] ) ) {
    unset($_POST['bp-form-id']);
}

// Get Form
$_form = new Form( get('site'), $_id );

// Check for a Form ID
if ( !$_id || !$_form->form_exists($_id) ) {

    // Set Alert
    set( 'form.alerts.invalid', array(
        'type'      => 'negative',
        'message'   => 'Invalid Form ID'
    ) );

} else {

    // Check for POST Data
    if ( !is_form_data() ) {

        // Set Alert
        set( 'form.alerts.bad-data', array(
            'type'      => 'negative',
            'message'   => 'No form input'
        ) );

    } else {

        // Run Validation
        $_valid = $_form->validate( form_data() );

        // Check ReCaptcha response
        if ( $_form->recaptcha ) {

            // Build Request
            $context  = stream_context_create(array(
                'http' => array(
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => http_build_query( array(
                        'secret' => get('site.recaptcha.secret'),
                        'response' => $_POST['g-recaptcha-response']
                    ) )
                )
            ));

            // Send Request
            $result = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
            $result = json_decode($result, true);

            // Check if the response was a success
            if ( $result['success'] ) {
                $_valid = true;
            }

        }

        // If there are errors, set an alert
        if ( !$_valid ) {

            // Create HTML
            $_html = '';

            // Create HTML Errors
            foreach ( $_form->errors() as $name => $error ) {
                $_html .= sprintf('<p>%s</p>', $error);
            }

            // Build Response
            set( 'form.alerts.errors', array(
                'type'      => 'negative',
                'message'   => $_html
            ) );

        } else {

            if ( $_form->process( $_valid ) ) {

                // Check if we should redirect
                if ( $_form->location ) {
                    header( sprintf( 'location: %s', $_form->location ) );
                }

                // Build Response
                set( 'form.alerts.success', array(
                    'type'      => 'positive',
                    'message'   => ( $_form->success_message ? $_form->success_message : 'Your message has been sent.' )
                ) );

            } else {

                // Create HTML
                $_html = '';

                // Create HTML Errors
                foreach ( $_form->errors() as $name => $error ) {
                    $_html .= sprintf('<p>%s</p>', $error);
                }

                // Build Response
                set( 'form.alerts.errors', array(
                    'type'      => 'negative',
                    'message'   => $_html
                ) );

            }

        }

    }

}

// Handle UI
$_template = $_theme->load($_path);

// If $_template is 404, use mine
if ( $_template == '404.php' ) {
    $_template = '/../_plugins/form/templates/submit.php';
}

// Render the page using plugin template
add_filter( 'theme/render', function() use ($_template) {
    return $_template;
} );
