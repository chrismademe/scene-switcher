<?php

/**
 * @package Boilerplate
 * @version 2.3.0
 * @author Chris Galbraith
 *
 * This file is used to include everything
 * needed for the site to function properly.
 *
 * If you need to make changes, you'll probably
 * find what you need in _templates and any
 * plugins will be in _plugins.
 *
 */

use BP\Controller;
use BP\Theme;

try {

    // Start a Session
    session_start();

    // Define Root Directory
    define('ROOT_DIR', __DIR__);

    // Define Version
    define('VERSION', '2.4.0');

    // Load Composer Dependencies
    $_autoload = ROOT_DIR . '/_includes/vendor/autoload.php';
    if ( file_exists($_autoload) ) {
        require_once $_autoload;
    }

    // Load Dependencies
    require_once ROOT_DIR . '/_includes/core/dependencies/spyc/Spyc.php';
    require_once ROOT_DIR . '/_includes/core/dependencies/router/Router.php';

    // Load BP Classes
    require_once ROOT_DIR . '/_includes/core/classes/BP/Router.php';
    require_once ROOT_DIR . '/_includes/core/classes/BP/Assets.php';
    require_once ROOT_DIR . '/_includes/core/classes/BP/Filters.php';
    require_once ROOT_DIR . '/_includes/core/classes/BP/Theme.php';
    require_once ROOT_DIR . '/_includes/core/classes/BP/Triggers.php';
    require_once ROOT_DIR . '/_includes/core/classes/BP/Variables.php';

    // Load Site Config
    $_config = ( is_readable(ROOT_DIR . '/../.config.yml') ? '/../.config.yml' : '/.config.yml' );
    $_config = Spyc::YAMLLoad(ROOT_DIR . $_config);

    // For Development, show errors
    if ( $_config['environment'] == 'dev' ) {
        error_reporting(E_ALL);
        ini_set('display_errors', 'on');
    }

    // Set Current Path
    // @NOTE: Defaults to 'index' when no path is specified (e.g. homepage)
    $_path   = (isset($_GET['path']) ? rtrim($_GET['path'], '/') : 'index');
    $_index  = explode('/', $_path);

    // Create Theme Object
    $_theme = new Theme($_config, $_path, $_index);

    // Include functions, classes & plugins
    require_once ROOT_DIR . '/_includes/core/includes.php';

    // Run Environment Checks
    require_once __DIR__ . '/_includes/core/environment-checks.php';

    // Run Router
    router()->run();

    // Catch 404
    add_filter( 'template.render', function($loaded) {
        global $_theme;

        if ( $loaded == '' || $loaded == '404.php' && !is_readable( $_theme->prop('dir') . '/404' . $_theme->prop('ext') ) ) {
            require_once ROOT_DIR . '/_includes/core/ui/404.php';
            exit;
        }

        return $loaded;

    } );

    // Render the Page
    $_theme->render(apply_filters('template.render', $_theme->load($_path)), get());

} catch (Exception $e) {

    // Show errors on screen for development environment.
    if ( $_config['environment'] !== 'prod' ) {
        require_once __DIR__ . '/_includes/core/ui/exception.php';
    } else {
        $error = file_get_contents(sprintf('http://tools.resknow.net/error/index.php?company=%s', $_config['company']));
        echo $error;
    }

}
