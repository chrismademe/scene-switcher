<?php

namespace Form;

class JSON {

    /**
     * Parse
     */
    public static function parse( $code, $type, $message, $additional = null, $exit = true ) {

        /**
         * Validate Code
         */
        if ( !is_int($code) || !is_numeric($code) ) {
            throw new Exception('JSON code must be integer or numeric.');
        }

        /**
         * Validate Type
         */
        if ( !is_string($type) ) {
            throw new Exception('JSON type must be integer or numeric.');
        }

        /**
         * Validate Message
         */
        if ( !is_string($message) ) {
            throw new Exception('JSON message must be integer or numeric.');
        }

        /**
         * Build JSON Array
         */
        $json = array(
            'code'      => $code,
            'type'      => $type,
            'message'   => $message
        );

        /**
         * Add Additional data
         */
        if ( !is_null($additional) ) {
            $json['data'] = $additional;
        }

        header('Content-type: application/json');
        echo self::encode($json);

        if ( $exit ) {
            exit;
        }

    }

    /**
     * Encode
     */
    public static function encode( $json ) {

        if ( !is_array($json) && !is_object($json) ) {
            throw new Exception('JSON::encode requires an array or object');
        }

        return json_encode($json);

    }

}
