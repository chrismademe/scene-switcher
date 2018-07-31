<?php

/**
 * Router
 * 
 * Returns the singleton instance of
 * the Router class for managing routes
 * 
 * @since 2.4.0
 */
function router() {
    $router = BP\Router::get_instance();
    return $router->router();
}