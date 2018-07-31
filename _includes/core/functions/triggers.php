<?php

/**
 * Do Action
 *
 * @since 1.6.0
 */
function do_action() {
    $triggers = BP\Triggers::get_instance();
    call_user_func_array( array( $triggers, 'do_trigger' ), func_get_args() );
}

/**
 * Add Action
 *
 * @since 1.0.2
 * @updated 1.5.4
 */
function add_action( $trigger, $action ) {
    $triggers = BP\Triggers::get_instance();

    $args = func_get_args();
    unset($args[0]);
    unset($args[1]);

    call_user_func_array(array($triggers, 'add_action'), array_merge(array(
        $trigger,
        $action
    ), $args));
}

/**
 * Remove Action
 *
 * @since 1.5.3
 * @updated 1.5.4
 */
function remove_action( $trigger, $action ) {
    $triggers = BP\Triggers::get_instance();
    return $triggers->remove_action($trigger, $action);
}
