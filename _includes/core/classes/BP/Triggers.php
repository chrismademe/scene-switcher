<?php

/**
 * Class: Triggers
 *
 * @since 1.4.1
 * @updated 1.5.2
 */

namespace BP;

use Exception;

class Triggers {

    /**
     * Instance
     */
    private static $instance;

    /**
     * Triggers array
     */
    private $triggers = array();

    /**
     * Actions array
     */
    private $actions  = array();

    /**
     * Do Trigger
     */
    public function do_trigger( $trigger ) {

        // Get Args
        $args = func_get_args();

        // Remove Trigger ID
        unset($args[0]);

        // Reset args array
        $args = array_values($args);

        // Check this trigger has actions
        if ( array_key_exists($trigger, $this->actions) ) {

            // Loop through each action
            foreach ( $this->actions[$trigger] as $action ) {

                // Do the action
                call_user_func_array( array( $this, 'do_action' ), array_merge(array($action['action']), $args) );

            }

        }

    }

    /**
     * Get Triggers
     */
    public function get_triggers() {
        return $this->triggers;
    }

    /**
     * Trigger Exists
     */
    public function trigger_exists( $trigger ) {
        return array_key_exists($trigger, $this->triggers);
    }

    /**
     * Add Action
     */
    public function add_action( $trigger, $action ) {

        // Register action
        $this->actions[$trigger][] = array(
            'trigger'   => $trigger,
            'action'    => $action
        );

    }

    /**
     * Do Action
     */
    public function do_action( $action ) {

        // Get Args
        $args = func_get_args();

        // Remove Action ID
        unset($args[0]);

        // Reset args array
        $args = array_values($args);

        // Execute callback
        call_user_func_array($action, $args);

    }

    /**
     * Remove Action
     *
     * @param $action (string) Action to remove
     */
    public function remove_action( $trigger, $action ) {
        if ( array_key_exists( $action, $this->actions[$trigger] ) ) {
            unset($this->actions[$trigger][$action]);
        }
    }

    /**
     * Get Actions
     */
    public function get_actions() {
        return $this->actions;
    }

    /**
     * Get Instance
     *
     * @since 1.5.4
     */
    public static function get_instance() {

        if ( self::$instance === null ) {
            self::$instance = new Triggers();
        }

        return self::$instance;

    }

    /**
     * Fire [Static do_trigger()]
     *
     * @since 1.5.4
     */
    public static function fire( $trigger ) {
        $i = self::get_instance();
        $i->do_trigger( $trigger );
    }

    /**
     * Action [Static add_action()]
     *
     * @since 1.5.4
     */
    public static function register( $trigger, $action ) {
        $i = self::get_instance();
        $i->add_action( $trigger, $action );
    }

    /**
     * Remove [Static remove_action()]
     *
     * @since 1.5.4
     */
    public static function unregister( $trigger, $action ) {
        $i = self::get_instance();
        $i->remove_action( $trigger, $action );
    }

}
