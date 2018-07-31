<?php

use BP\Assets;

$_assets = new Assets();

/**
 * Add Asset
 *
 * @since 2.0.0
 *
 * @global $_assets
 *
 * @param $type (string) Asset type
 * @param $id (string) Asset ID
 * @param $path (string) Asset path
 * @param $paths (array) (optional) Paths to load asset on
 */
function add_asset( $type, $id, $path, $paths = array(), $instance = false ) {

    // Default instance
    global $_assets;

    // Set instance
    $instance = ($instance != false ? $instance : $_assets);

    // Get instance
    if ( !$instance instanceof Assets ) {
        throw new Exception('add_asset() requires an Assets instance.');
    }

    // Get Assets
    return $instance->add_asset($type, $id, $path, $paths);

}

/**
 * Add Script
 */
function add_script( $id, $path, $paths = array(), $instance = false ) {
    add_asset( 'script', $id, $path, $paths, $instance );
}

/**
 * Add Stylesheet
 */
function add_stylesheet( $id, $path, $paths = array(), $instance = false ) {
    add_asset( 'stylesheet', $id, $path, $paths, $instance );
}

/**
 * Remove Asset
 *
 * @global $_assets
 *
 * @param $type (string) Asset type
 * @param $id (string) Asset ID
 * @param $paths (array) (optional) Paths to remove from
 * @param $instance (Assets object) (optional) Assets instance
 */
function remove_asset( $type, $id, $paths = array(), $instance = false ) {

    // Default instance
    global $_assets;

    // Set instance
    $instance = ($instance != false ? $instance : $_assets);

    // Get instance
    if ( !$instance instanceof Assets ) {
        throw new Exception('remove_asset() requires an Assets instance.');
    }

    // Get Assets
    return $instance->remove_asset($type, $id, $paths);

}

/**
 * Remove Script
 *
 * @global $_assets
 *
 * @param $id (string) Asset ID
 * @param $paths (array) (optional) Paths to remove from
 * @param $instance (Assets object) (optional) Assets instance
 */
function remove_script( $id, $paths = array(), $instance = false ) {
    return remove_asset( 'script', $id, $paths, $instance );
}

/**
 * Remove Stylesheet
 *
 * @global $_assets
 *
 * @param $id (string) Asset ID
 * @param $paths (array) (optional) Paths to remove from
 * @param $instance (Assets object) (optional) Assets instance
 */
function remove_stylesheet( $id, $paths = array(), $instance = false ) {
    return remove_asset( 'stylesheet', $id, $paths, $instance );
}

/**
 * Get Assets
 *
 * @since 1.6.0
 *
 * @global $_assets
 *
 * @param $type (string) Asset type
 * @param $instance (Assets object) (optional) Instance of Assets
 */
function get_assets( $type, $instance = false ) {

    // Default instance
    global $_assets;

    // Set instance
    $instance = ($instance != false ? $instance : $_assets);

    // Get instance
    if ( !$instance instanceof Assets ) {
        throw new Exception('get_assets() requires an Assets instance.');
    }

    // Get Assets
    return $instance->get_assets($type);

}

/**
 * Get Scripts
 *
 * @since 1.6.0
 *
 * @param $instance (Assets object) (optional) Instance of Assets
 */
function get_scripts( $instance = false ) {
    return get_assets( 'script', $instance );
}

/**
 * Get Stylesheets
 *
 * @since 1.6.0
 *
 * @param $instance (Assets object) (optional) Instance of Assets
 */
function get_stylesheets( $instance = false ) {
    return get_assets( 'stylesheet', $instance );
}
