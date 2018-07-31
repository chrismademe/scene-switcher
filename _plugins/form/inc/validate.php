<?php

use Form\Form;
use Form\JSON;

/**
 * This is accessed by sending post data
 * to /validate/<formid> and will return
 * a JSON object with validation info.
 */

// Get Form ID
$_id = ( isset( $_POST['bp-form-id'] ) ? $_POST['bp-form-id'] : get('page.index.1') );

// Remove ID from $_POST
if ( isset( $_POST['bp-form-id'] ) ) {
    unset($_POST['bp-form-id']);
}

// Remove ReCaptcha from $_POST
if ( isset( $_POST['g-recaptcha-response'] ) ) {
    unset($_POST['g-recaptcha-response']);
}

// Get Form
$_form = new Form( get('site'), $_id );

// Check for a Form ID
if ( !$_id || !$_form->form_exists($_id) ) {

    // Set an HTTP Bad Request code
    http_response_code(400);

    // Build Response
    JSON::parse( 400, 'bad-request', 'Invalid Form ID' );

}

// Check for POST Data
if ( !is_form_data() ) {

    // Set an HTTP Method Not Allowed code
    http_response_code(405);

    // Build Response
    JSON::parse( 405, 'method-not-allowed', 'POST requests only' );

}

// Run Validation
$_valid = $_form->validate( form_data() );

// If there are errors, return a 400 response
if ( !$_valid ) {

    // Set an HTTP Bad Request code
    http_response_code(400);

    // Build Response
    JSON::parse( 400, 'bad-request', 'Input validation failed', array(
        'errors' => $_form->errors()
    ) );

}

// Everything is OK
JSON::parse( 200, 'ok', 'Input validation OK' );
