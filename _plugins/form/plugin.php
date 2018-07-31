<?php

use Form\Form;
use Form\JSON;

/**
 * @subpackage Form
 * @version 1.1.0
 * @package Boilerplate (2.0.0 or higher)
 * @author Chris Galbraith
 */

/********************************
 * @TODO
 *
 * - Add get_form() functionality
 *******************************/

// Set some plugin info
// @NOTE This is experimental!
// I plan to add a register_plugin function
// in future to store this information.
set( 'plugins.form', array(
    'namespace' => 'form',
    'name'      => 'Form',
    'version'   => '1.1.0'
) );

// Set Minimum Boilerplate Version
plugin_requires_version( 'form', '2.0.0' );

// This plugin doesn't play nice with
// the original Contact plugin, so check
// that it's not active and if it is
// throw an Exception
if ( plugin_is_active( 'contact' ) ) {
    throw new Exception( 'The Form plugin does not work with Contact enabled. Please disable it to continue.' );
}

// Make sure we have some config to work with
if ( !get('site.forms') ) {
    throw new Exception( 'There are no forms configured.' );
}

// Filter: form_init
set( 'site.forms', apply_filters( 'form.init', get('site.forms') ) );

// Load Assets
require_once __DIR__ . '/inc/assets.php';

// Include Dependencies
require_once __DIR__ . '/vendor/autoload.php';

// Include Form Classes
require_once __DIR__ . '/classes/Form.php';
require_once __DIR__ . '/classes/JSON.php';

// Validation
if ( path_contains('validate') ) {
    require_once __DIR__ . '/inc/validate.php';
}

// Submission
if ( !path_contains('validate') && path_contains('submit') || isset( $_POST['bp-form-id'] ) ) {
    require_once __DIR__ . '/inc/submit.php';
}
