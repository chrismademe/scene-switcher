<?php

namespace BP;
use Exception;

class Theme {

    /**
     * Active Theme
     */
    private $active;

    /**
     * Template directory
     */
    private $dir;

    /**
     * Template file extension
     */
    private $ext;

    /**
     * Default file
     * For templates would be a 404
     * or for logic, could be a simple
     * file for setting default
     * code for every page
     */
    private $default;

    /**
     * Not Found
     *
     * True if the default
     * file is loaded
     */
    public $not_found = false;

    /**
     * Current path (URL)
     */
    private $path;

    /**
     * Current path index
     */
    private $index      = array();

    /**
     * Template variables
     */
    private $variables;

    /**
     * Set defaults
     */
    public function __construct(array $site_config, $path, array $index) {

        // Get Site Config
        $this->site_config = $site_config;

        // Default config
        $this->dir          = (isset($this->site_config['theme']) ? $this->site_config['theme'] : '_templates'); // Template directory
        $this->ext          = '.php'; // Template file extension
        $this->default      = '404'; // Default template name

        // Set Active Theme
        #
        // @NOTE: This property will probably be
        // removed in a future version of
        // Boilerplate.
        $this->active       = $this->dir;

        // Set path variables
        $this->path         = $path;
        $this->index        = $index;

        // Set defined templates
        $defined = ( array_key_exists( 'templates', $this->site_config ) ? $this->site_config['templates'] : array() );
        $this->defined_templates = $defined;

        // Check for index template
        if ( !is_readable($this->dir . '/index' . $this->ext ) ) {
            throw new Exception(sprintf(
                'Active theme (<code>%s</code>) does not contain an <code>index%s</code> template. This is required.',
                str_replace(ROOT_DIR, '', $this->dir),
                $this->ext
            ));
        }

    }

    /**
     * Get
     *
     * Returns the value of a theme variable.
     */
    public function __get( $property ) {
        return ( isset($this->variables[$property]) ? $this->variables[$property] : false );
    }

    /**
     * Isset
     *
     * Check if a variable exists
     */
    public function __isset( $property ) {
        return isset($this->variables[$property]);
    }

    /**
     * Get property
     *
     * Returns the value of a property.
     *
     * @since 1.0.2
     */
    public function prop( $property ) {
        return ( property_exists( $this, $property ) ? $this->$property : false );
    }

    /**
     * Set property
     */
    public function __set( $property, $value ) {
        $this->$property = $value;
    }

    /**
     * Render
     *
     * @version 1.0.2
     * @since 1.0.0
     */
    public function render( $template, array $variables, $print = true ) {

        /**
         * Store variables in object
         * for reference if needed
         */
        $this->variables = $variables;

        // Start Output Buffering
        ob_start();

        // Trigger: before_render_template
        do_action('before_render_template', $template);

        // Include Template File
        include $this->dir . '/' . $template;

        // Trigger: after_render_template
        do_action('after_render_template', $template);

        // Render Output
        if ( $print !== true ) {
            return ob_get_clean();
        }

        echo ob_get_clean();

    }

    /**
     * Load Template
     */
    public function load( $name, $file = false ) {

        /**
         * Find required template
         */
        switch (true) {

            /**
             * If a defined template exists for this file
             * let's try to load it
             */
            case array_key_exists( $name, $this->defined_templates ) && file_exists($this->dir .'/'. $this->defined_templates[$name] . $this->ext):
                $template = $this->defined_templates[$name] . $this->ext;
                break;

            /**
             * If specific file is
             * entered, attempt
             * to load it.
             */
            case $file !== false && file_exists($this->dir .'/'. $file):
                $template = $file;
            break;

            /**
             * If template with
             * specified name exists,
             * attempt to load it.
             */
            case $file === false && file_exists($this->dir .'/'. $name . $this->ext):
                $template = $name . $this->ext;
            break;

            /**
             * If we find an index.php
             * in a sub folder, load it.
             */
            case $file === false && file_exists($this->dir .'/'. $name .'/index'. $this->ext):
                $template = $name .'/index'. $this->ext;
            break;

            /**
             * If template
             * where dashes replace
             * slashes exists, attempt
             * to load it.
             */
            case $file === false && file_exists($this->dir .'/'. str_replace('/', '-', $name) . $this->ext):
                $template = str_replace('/', '-', $name) . $this->ext;
            break;

            /**
             * Look for parent
             * templates in real
             * folders
             */
            case $file === false && !file_exists($this->dir .'/'. $name . $this->ext):
                $template = $this->find_template();
            break;

            /**
             * Finally, look for
             * parent templates
             * within naming convention
             *
             * e.g.: find-this-file.php
             */
            case $file === false && !file_exists($this->dir .'/'. str_replace('/', '-', $name) . $this->ext):
                $template = $this->find_template(true);
            break;

        }

        /**
         * Return it
         */
        if ( $template !== false && is_readable($this->dir .'/'. $template) ) {
            return $template;
        }

    }

    /**
     * Find Template
     */
    private function find_template( $replace = false ) {

        $implode = ($replace === false ? '/' : '-');

        $index_count = count($this->index) - 1;
        $index = $this->index;

        while ($index) {

            // Remove last index
            unset($index[$index_count]);

            // Generate filename
            $file = implode($implode, $index) . $this->ext;

            // If we found a match, use it
            if ( file_exists($this->dir .'/'. $file) ) {
                $template = $file;
                break;
            }

            // If not, move on to the next one
            $index_count--;

        }

        // Return template
        if ( isset($template) ) {
            return $template;
        } else {
            $this->not_found = true;
            return $this->default . $this->ext;
        }

    }

    /**
     * Return Not Found status
     */
    public function not_found() {
        return $this->not_found;
    }

    /**
     * Use theme
     *
     * Use specified theme instead
     * of default for this request
     */
    public function use_theme( $theme ) {

        // Update Dir
        $this->dir = $theme;

        // Active Theme
        $this->active = $theme;

        // Trigger: theme_changed
        do_action('theme_changed', $theme);

    }

    /**
     * Get Partial
     *
     * @param $part: (string) name of partial
     * @param $context: (optional) (mixed) context
     */
    public function get_partial( $part, $context = false ) {

        // Get Partial
        $partial = 'partials/' . $part . $this->ext;

        // Return false if not found
        if ( !is_readable($this->dir . '/' . $partial) ) {
            return false;
        }

        // Trigger: before_include_$part
        do_action('before_include_' . $part);

        include $this->dir . '/' . $partial;

        // Trigger: after_include_$part
        do_action('after_include_' . $part);

    }

    /**
     * Get Header
     *
     * @param $context: (optional) (mixed) context
     * @since 1.0.3
     * @updated 2.0.0
     */
    public function get_header( $context = false ) {
        return $this->get_partial('header', $context);
    }

    /**
     * Get Footer
     *
     * @param $context: (optional) (mixed) context
     * @since 1.0.3
     * @updated 2.0.0
     */
    public function get_footer( $context = false ) {
        return $this->get_partial('footer', $context);
    }

    /**
     * Get Sidebar
     *
     * @param $context: (optional) (mixed) context
     * @since 1.0.3
     * @updated 2.0.0
     */
    public function get_sidebar( $context = false ) {
        return $this->get_partial('sidebar', $context);
    }

    /**
     * Assets Dir
     * Return location of assets relative to
     * the ROOT_DIR.
     *
     * @since 1.0.3
     *
     * @param $prefix: (string) string to prepend to the returned value.
     * @param $print: (bool) print the value to the screen
     */
    public function assets_dir( $prefix = '/', $print = true ) {

        // Get Current Theme Location
        $location = $this->prop('dir');

        if ( $print === true ) {
            echo $prefix . $location . '/assets';
        } else {
            return $prefix . $location . '/assets';
        }

    }


}
