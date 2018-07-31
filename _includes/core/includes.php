<?php

// Load Router, Filters & Triggers
require_once ROOT_DIR . '/_includes/core/functions/router.php';
require_once ROOT_DIR . '/_includes/core/functions/filters.php';
require_once ROOT_DIR . '/_includes/core/functions/triggers.php';

// Get Plugin functions.php
$_plugin_functions = glob('_plugins/**/functions.php');

/**
 *Get theme functions.php
 *
 * @NOTE This is optional but can be useful
 * for applying filters as they need to be
 * declared before they get executed!
 */
if ( file_exists('_templates/functions.php') ) {

    require_once ROOT_DIR .  '/_templates/functions.php';

    // Action: template.functions.loaded
    do_action( 'template.functions.loaded' );

}

// Get Functions
require_once ROOT_DIR . '/_includes/core/functions/forms.php';
require_once ROOT_DIR . '/_includes/core/functions/path.php';
require_once ROOT_DIR . '/_includes/core/functions/plugins.php';
require_once ROOT_DIR . '/_includes/core/functions/theme.php';

// Setup Global Variables
require_once ROOT_DIR . '/_includes/core/variables.php';

// Action: variables.loaded
do_action( 'variables.loaded' );

// Load Assets
require_once ROOT_DIR . '/_includes/core/functions/assets.php';

// Action: assets.loaded
do_action( 'assets.loaded', $_assets );

// Get available plugins
$_plugin_files = glob('_plugins/**/plugin.php');

// Include plugins
if ( is_array($_plugin_files) ) {

    foreach ( $_plugin_files as $plugin ) {
        require_once $plugin;
    }

    // Clean up
    // Unset $_plugin_files
    unset($_plugin_files);

}

// Action: plugins.loaded
do_action( 'plugins.loaded' );
