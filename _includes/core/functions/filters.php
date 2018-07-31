<?php

/**
 * Add Filter
 *
 * @since 1.0.0
 * @updated 1.5.4
 *
 * $hook: (string) name of the filter hook
 * $name: (string) name of the callback function
 * @deprecated $args: (int) number of arguments
 * $priority: (int) the execution priority (lower = run first)
 */
function add_filter( $hook, $name, $args = 1, $priority = 10 ) {
    return BP\Filters::add( $hook, $name, $args, $priority );
}

/**
 * Apply Filters
 *
 * @since 1.0.0
 * @updated 1.5.4
 *
 * $hook: (string) name of the filter hook
 * $input: (mixed) value to filter
 */
function apply_filters( $hook, $input ) {
    return BP\Filters::apply( $hook, $input );
}
