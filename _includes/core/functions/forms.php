<?php

/**
 * Has Form Data
 * Checks for POST and FILE arrays
 *
 * @since 1.0.0
 */
function is_form_data() {
    return $_POST || $_FILES;
}

/**
 * Form Data
 * Contains POST and FILES arrays
 *
 * @since 1.0.0
 */
function form_data() {
    return array_merge($_POST, $_FILES);
}
