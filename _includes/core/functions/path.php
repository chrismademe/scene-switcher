<?php

/**
 * Check page
 * Returns true is
 * current page matches
 * input string
 *
 * Will check for homepage
 * if no argument
 *
 * @since 1.0.0
 *
 * $check: (string) path to check
 */
function is_page( $check = null ) {
    global $_path;
    $page = (is_null($check) ? 'index' : $check);
    return $page === str_replace('/', '-', $_path);
}

/**
 * Check to see if
 * current page is
 * homepage
 *
 * @since 1.0.0
 */
function is_home() {
    return is_page();
}

/**
 * Check path
 *
 * @since 1.0.0
 *
 * $check: (string) path to check
 */
function is_path( $check ) {
    global $_path;
    return $check === $_path;
}

/**
 * Path Contains
 *
 * Check to see if path
 * contains a given string
 * at any place.
 *
 * @since 1.0.0
 *
 * $string: (string) string to check for
 */
function path_contains( $string ) {
    global $_path;
    return preg_match('/' . preg_quote($string, '/') . '/', $_path);
}

/**
 * Is Index
 *
 * Check the first index
 *
 * @since 1.0.0
 *
 * $check: (string) index key to check
 */
function is_index( $check = null ) {
    global $_index;
    return $check == $index[0];
}
