<?php

/**
 * Environment Checks
 *
 * Below are some checks that run before almost
 * anything else on Boilerplate to make sure
 * it's bring run in a safe environment.
 *
 * It's recommended you leave this on because in
 * the case of something being wrong, it will stop
 * Boilerplate from running.
 *
 * If you really need to turn it off, which I don't
 * recommend you do, comment out the require_once
 * line on index.php for this file.
 *
 */

if ( get('site.environment') !== 'dev' ) {

    // Check for sensitive data in config
    if ( file_exists( ROOT_DIR . '/.config.yml' ) && get('site.db') ) {
        throw new Exception( 'Sensitive data found in your config. Move the config file up 1 directory, outside of the public root.' );
    }

}
