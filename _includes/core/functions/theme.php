<?php

/**
 * Head
 *
 * Runs template/head action
 */
function head() {
    do_action( 'template.head' );
}


/**
 * Footer
 *
 * Runs template/footer action
 */
function footer() {
    do_action( 'template.footer' );
}

/**
 * Get Partial
 * Get and render a partial template file.
 *
 * @since 1.0.0
 *
 * $part: (string) name of the partial, relative to
 * the _templates/partials directory.
 * $context (mixed) scoped variable available
 * inside the partial
 */
function get_partial( $part, $context = false ) {

    // Get theme object
    global $_theme;
    return $_theme->get_partial($part, $context);

}

/**
 * Get Header
 * Get header partial.
 *
 * @since 1.0.0
 *
 * $name: (string) name of custom header file.
 */
function get_header( $name = 'header' ) {
    return get_partial($name);
}

/**
 * Get Footer
 * Get footer partial.
 *
 * @since 1.0.0
 *
 * $name: (string) name of custom footer file.
 */
function get_footer( $name = 'footer' ) {
    return get_partial($name);
}

/**
 * Get Sidebar
 * Get sidebar partial.
 *
 * @since 1.0.0
 *
 * $name: (string) name of custom sidebar file.
 */
function get_sidebar( $name = 'sidebar' ) {
    return get_partial($name);
}

/**
 * Assets Dir
 * Return location of assets relative to
 * the ROOT_DIR.
 *
 * @since 1.0.1
 *
 * $prefix: (string) string to prepend to the returned value.
 */
function assets_dir( $prefix = '/' ) {

    // Get Theme
    global $_theme;

    // Get Current Theme Location
    $location = $_theme->prop('dir');

    return apply_filters( 'assets_dir', $prefix . $location . '/assets' );

}

/**
 * Use Theme
 *
 * @since 1.5.1
 *
 * @global $_theme
 * @param $theme (string) Directory of theme to use
 * @return void
 */
function use_theme( $theme ) {
    global $_theme;
    return $_theme->use_theme($theme);
}
