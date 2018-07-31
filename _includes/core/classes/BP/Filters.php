<?php

namespace BP;

use Exception;

class Filters {

    /**
     * Instance
     *
     * Instance of Filters
     */
    private static $instance;

    /**
     * Hooks
     *
     * Array of active hooks
     */
    private $hooks = array();

    /**
     * Filters
     *
     * Array of filters
     */
    private $filters = array();

    /**
     * Construct
     */
    private function __construct() {}

    /**
     * Clone
     */
    private function __clone() {}

    /**
     * Add Hook
     */
    public function add_hook( $name ) {
        if ( !$this->hook_exists($name) ) {
            $this->hooks[] = $name;
        }
    }

    /**
     * Hook Exists
     */
    public function hook_exists( $name ) {
        return in_array( $name, $this->hooks );
    }

    /**
     * Add Filter
     */
    public function add_filter( $hook, $callback, $args = 1, $priority = 10 ) {

        // Check Hook Exists
        if ( !$this->hook_exists( $hook ) ) {
            $this->add_hook( $hook );
        }

        // Check $priority is a integer
        if ( !is_int( $priority ) ) {
            throw new Exception('<code>$priority</code> must be a valid integer.');
        }

        ############################

        // Create Filter Array
        $this->filters[$hook][] = array(
            'priority'  => $priority,
            'callback'  => $callback
        );

    }

    /**
     * Filter Exists
     */
    public function filter_exists( $hook, $name ) {
        return isset( $this->filters[$hook][$name] );
    }

    /**
     * Apply Filters
     */
    public function apply_filters( $hook, $input = null ) {

        // Check for active filters
        if ( empty($this->filters[$hook]) ) {
            return $input;
        }

        // Get Filters
        $filters = $this->filters[$hook];

        // Apply Each Filter
        foreach ( $filters as $filter ) {
            $input = $this->apply_filter(
                $hook,
                $filter['callback'],
                $input
            );
        }

        // Return Filtered Result
        return $input;

    }

    /**
     * Apply Filter
     */
    public function apply_filter( $hook, $callback, $input ) {

        // Check Filter Function Exists
        if ( !is_callable($callback) ) {
            throw new Exception('Invalid callback.');
        }

        ############################

        $input = call_user_func( $callback, $input );
        return $input;

    }

    /**
     * Get Instance
     */
    public static function get_instance() {

        if ( self::$instance === null ) {
            self::$instance = new Filters();
        }

        return self::$instance;

    }

    /**
     * Add Filter [Static]
     *
     * @since 1.5.4
     */
    public static function add( $hook, $name, $args = 1, $priority = 10 ) {
        $i = self::get_instance();
        $i->add_filter( $hook, $name, $args, $priority );
    }

    /**
     * Apply Filters [Static]
     *
     * @since 1.5.4
     */
    public static function apply( $hook, $name ) {
        $i = self::get_instance();
        return $i->apply_filters( $hook, $name );
    }

}
