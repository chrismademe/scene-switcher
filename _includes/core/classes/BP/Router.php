<?php

namespace BP;

use Bramus\Router\Router as R;

/**
 * A simple wrapper class for the Bramus Router
 * class
 */
class Router {

    private $router;
    private static $instance;

    private function __construct() {
        $this->router = new R();
    }

    /**
     * Router Object
     */
    public function router() {
        return $this->router;
    }

    /**
     * Get Instance
     */
    public static function get_instance() {

        if ( self::$instance === null ) {
            self::$instance = new self();
        }

        return self::$instance;

    }

}