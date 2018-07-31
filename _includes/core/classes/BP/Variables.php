<?php

/**
 * Variables:
 * Variable object for theme/template variables.
 */

namespace BP;

class Variables {

    /**
     * Site Config
     */
    private $site_config = array();

    /**
     * Variables
     */
    private $variables = array();

    /**
     * Core Variables
     * These could be required throughout your app
     * and so are not settable from outside
     * of the class
     */
    private $core = array();

    /**
     * Construct
     * Define core variables
     */
    public function __construct(array $site_config) {
        $this->site_config = $site_config;

        // Define Site Variables
        $this->set('site', $site_config, true);
    }

    /**
     * Get
     */
    public function get( $key = null ) {

        /**
         * Get All
         */
        if ( is_null($key) ) {
            return $this->variables;
        }

        $parsed = explode('.', $key);

        $variable = $this->variables;

        while ($parsed) {
            $next = array_shift($parsed);

            if (isset($variable[$next])) {
                $variable = $variable[$next];
            } else {
                return null;
            }
        }

        return $variable;

    }

    /**
     * Add Variable
     */
    public function set( $variable, $value = false, $core = false ) {

        if ( is_array($variable) ) {
            foreach( $variable as $var => $val ) {
                $this->set($var, $val, $core);
            }
        } elseif ( $value !== false ) {

            if ( !in_array($variable, $this->core) ) {

                $parsed = explode('.', $variable);

                $var =& $this->variables;

                while (count($parsed) > 1) {
                    $next = array_shift($parsed);

                    if ( !isset($var[$next]) || !is_array($var[$next])) {
                        $var[$next] = array();
                    }

                    $var =& $var[$next];
                }

                $var[array_shift($parsed)] = $value;

                if ($core === true) {
                    $this->core[] = $variable;
                }
            }

        }
    }

    /**
     * Remove Variable
     */
    public function remove( $variable ) {
        if ( is_array($variable) ) {
            foreach ( $variable as $var ) {
                $this->remove($var);
            }
        } else {
            if ( !in_array($variable, $this->core) && array_key_exists($variable, $this->variables) ) {
                unset($this->variables[$variable]);
            }
        }
    }

}
