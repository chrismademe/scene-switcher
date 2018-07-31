<?php

namespace BP;

use Exception;

class Assets {

    protected $assets = array();
    protected $protected; // Whether assets are protected
    protected $locked; // Whether assets are locked
    protected $path;

    /**
     * Construct
     */
    public function __construct( array $args = array() ) {

        // Set defaults
        $defaults = array(
            'protected' => false,
            'locked' => false,
            'load_theme_assets' => true
        );

        // Merge $args
        $args = array_merge($defaults, $args);

        /**
         * When the instance is protected, it
         * means that if an asset is added
         * with an existing ID, an Exception
         * will be thrown.
         */
        $this->protect($args['protected']);

        /**
         * When the instance is locked, assets
         * cannot be removed or added, so any
         * calls to add or remove will throw
         * an Exception
         */
        $this->lock($args['locked']);

        // Get current path
        $this->path = get('page.path');

        // Load assets from theme config
        if ( $args['load_theme_assets'] === true ) {
            $this->load_theme_assets();
        }

    }

    /**
     * Get
     *
     * Use property names to access assets arrays
     *
     * @param $type (string) Asset type
     */
    public function __get( $type ) {
        if ( ! array_key_exists($type, $this->assets) ) {
            return $this->assets[$type];
        }
    }

    /**
     * Load Site Assets
     */
    protected function load_theme_assets() {

        // Get Stylesheets
        if ( $stylesheets = get('site.stylesheets') ) {
            foreach ( $stylesheets as $name => $path ) {
                $this->add_asset( 'stylesheet', $name, $this->get_assets_dir($path) );
            }
        }

        // Get Scripts
        if ( $scripts = get('site.scripts') ) {
            foreach ( $scripts as $name => $path ) {
                $this->add_asset( 'script', $name, $this->get_assets_dir($path) );
            }
        }

    }

    /**
     * Get Assets Dir
     *
     * Replace ~ with assets directory location
     * when loading theme assets.
     *
     * @param $path (string) Path to replace
     */
    protected function get_assets_dir( $path ) {

        if ( substr($path, 0, 1) === '~' ) {
            return substr_replace($path, assets_dir(), 0, 1);
        }

        return $path;

    }

    /**
     * Protected
     *
     * Put this instance in to a protected state
     *
     * @param $state (bool) true/false
     */
    public function protect( $state ) {

        if ( !is_bool($state) ) {
            throw new Exception('Assets: Invalid protection state.');
        }

        $this->protected = $state;
    }

    /**
     * Is Protected
     */
    public function is_protected() {
        return $this->protected;
    }

    /**
     * Lock
     *
     * Put this instance in to a locked state
     *
     * @param $state (bool) true/false
     */
    public function lock( $state ) {

        if ( !is_bool($state) ) {
            throw new Exception('Assets: Invalid lock state.');
        }

        $this->locked = $state;

    }

    /**
     * Is Locked
     */
    public function is_locked() {
        return $this->locked;
    }

    /**
     * Add Asset
     *
     * @param $type (string) Asset type (script/stylesheet)
     * @param $id (string) Asset ID
     * @param $path (string) Path to asset
     * @param $paths (array) Array of paths to load this asset on
     */
    public function add_asset( $type, $id, $path, array $paths = array() ) {

        // Check $paths array
        if ( !empty($paths) ) {
            if ( in_array($this->path, $paths) ) {
                return $this->queue_asset( $type, $id, $path );
            } else {
                return false;
            }
        }

        // Queue for all paths
        return $this->queue_asset( $type, $id, $path );

    }

    /**
     * Add Assets
     *
     * Add an array of assets
     *
     * @param $type (string) Asset type
     * @param $assets (array) Array of assets to load
     */
    public function add_assets( $type, array $assets ) {

        // Check it's not empty
        if ( empty($assets) ) {
            return false;
        }

        // Add Assets
        foreach ( $assets as $id => $path ) {
            $this->add_asset( $type, $id, $path );
        }

    }

    /**
     * Queue Asset
     *
     * Takes an asset from add_asset() and adds
     * it to the respective queue
     */
    protected function queue_asset( $type, $id, $path ) {

        // Check if this instance is locked
        if ( $this->is_locked() ) {
            throw new Exception('Unable to add asset, Assets instance is locked.');
        }

        // If protected, check ID does not exist
        if ( $this->is_protected() && array_key_exists($id, $this->$type) ) {
            throw new Exception('Unable to overwrite existing asset, Assets instance is protected.');
        }

        // Queue asset
        $this->assets[$type][$id] = $this->get_assets_dir($path);

    }

    /**
     * Remove Asset
     *
     * Remove an asset from a queue
     */
    public function remove_asset( $type, $id, array $paths = array() ) {

        // Check if this instance is locked
        if ( $this->is_locked() ) {
            throw new Exception('Unable to remove asset, Assets instance is locked.');
        }

        // Conditionally remove based on path
        if ( !empty($paths) ) {
            if ( in_array($this->path, $paths) ) {
                return $this->unset_asset( $type, $id );
            } else {
                return false;
            }
        }

        // Remove Asset
        return $this->unset_asset( $type, $id );

    }

    /**
     * Unset Asset
     */
    protected function unset_asset( $type, $id ) {

        if ( $this->asset_exists($type, $id) ) {
            unset($this->assets[$type][$id]);
        }

    }

    /**
     * Get Assets
     *
     * @param $type (string) Asset type
     */
    public function get_assets( $type ) {

        // Check if type exists
        if ( !$this->type_exists($type) ) {
            return false;
        }

        return $this->assets[$type];
    }

    /**
     * Type Exists
     *
     * @param $type (string) Asset type to check
     */
    public function type_exists( $type ) {
        return array_key_exists($type, $this->assets);
    }

    /**
     * Asset Exists
     *
     * @param $type (string) Asset type
     * @param $id (string) Asset ID
     */
    public function asset_exists( $type, $id ) {

        // Check Type exists
        if ( !$this->type_exists($type) ) {
            return false;
        }

        return array_key_exists( $id, $this->assets[$type] );

    }

}
