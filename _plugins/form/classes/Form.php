<?php

namespace Form;

use BP\Theme;
use Exception;
use GUMP;
use Medoo\Medoo;
use PHPMailer\PHPMailer\PHPMailer;

class Form {

    private     $config = array(); // Site config
    private     $id;    // Currently loaded form ID
    private     $form = array(); // Currently loaded form
    private     $input = array(); // Input array
    private     $errors = array(); // Errors array
    private     $db; // Medoo instance if Database is used

    /**
     * Construct
     */
    public function __construct( array $config, $id = false ) {

        // Check for existing session
        $this->session = ( isset($_SESSION['bp-form']) ? $_SESSION['bp-form'] : array() );

        // Set Config
        $this->config = $config;

        // If an ID is specified, load the form
        if ( $id ) {
            $this->get($id);
        }

    }

    /**
     * Get Property
     */
    public function __get( $key ) {
        return ( array_key_exists($key, $this->form) ? $this->form[$key] : false );
    }

    /**
     * Get
     *
     * @param $id (string) Form ID
     * @return (array) Form config
     */
    private function get( $id ) {

        // Check the form exists
        if ( !$this->form_exists($id) ) {
            return false;
        }

        // Filter: form_get
        $form = apply_filters( 'form_get', $this->config['forms'][$id] );

        // Save ID
        $this->id = $id;

        // Get form
        $this->form = $form;

        // Database connection?
        if ( isset($form['db']) && $form['db'] != false ) {
            $this->db = $this->connect( $this->config['db'] );
        }

    }

    /**
     * Validate
     *
     * @param $input (array) Input array
     * @return (array) in success, (bool) False on failure
     * Use $this->errors() to get errors on failure
     */
    public function validate( $input ) {

        // Filter: form_before_validate
        $input = apply_filters( 'form_before_validate', $input );

        // Save RAW Input
        $this->input['dirty'] = $input;

        // Create GUMP Instance
        $gump = new GUMP();

        // Sanitize input
        $this->input['clean'] = $gump->sanitize($input);

        // Filter: form_after_sanitize
        $this->input['clean'] = apply_filters( 'form_after_sanitize', $this->input['clean'] );

        // Build Validation & Filter Arrays
        foreach ( $this->form['fields'] as $name => $config ) {

            // Check for Validation rules
            if ( array_key_exists('validate', $config) ) {

                // Add rules
                $rules['validate'][$name] = $config['validate'];

            }

            // Check for Filter rules
            if ( array_key_exists('filter', $config) ) {

                // Add rules
                $rules['filter'][$name] = $config['filter'];

            }

            // Set Field Name
            $gump->set_field_name($name, $config['label']);

        }

        // Set Validation Rules
        $gump->validation_rules($rules['validate']);

        // Set Filter Rules
        $gump->filter_rules($rules['filter']);

        // Run
        $this->input['valid'] = $gump->run($this->input['clean']);

        // Save errors on failure
        if ( !$this->input['valid'] ) {
            $this->errors = $gump->get_errors_array();
        }

        // Filter: form_after_validate
        $this->input['valid'] = apply_filters( 'form_after_validate', $this->input['valid'] );

        // Return result
        return $this->input['valid'];

    }

    /**
     * Process
     *
     * This method will process the current form
     * based on the settings defined in the config.
     *
     * It will either send an email, save to a database
     * or both.
     *
     * @param $input (array) Input array, should be pre-validated
     */
    public function process( $input ) {

        // Filter: form_before_process
        $input = apply_filters( 'form_before_process', $input );

        // If we need to save to a database
        // do that first
        if ( isset($this->form['db']) ) {
            $this->save($input);
        }

        // If we need to send an email, send it
        if ( isset($this->form['email']) ) {
            $this->send($input);
        }

        // Check for errors
        return ( !empty($this->errors) ? false : true );

    }

    /**
     * Send
     *
     * @param $input (array) Input array, should be pre-validated
     */
    private function send( $input ) {

        // Create a new Theme Object
        $theme = new Theme( $this->config, get('page.path'), get('page.index') );

        // Filter: form_theme
        $dir = apply_filters( 'form_theme', plugin_dir() . '/form/templates' );

        // Set Theme directory
        $theme->use_theme($dir);

        // Save Input in global variables
        set('form.input', $input);

        // Save Form ID to global variables
        set('form.id', $this->id);

        // Build E-mail Message
        $message = new PHPMailer();
        $message->setFrom( apply_filters('form_message_from', $this->form['recipient']) );
        $message->addAddress( apply_filters('form_message_to', $this->form['recipient']) );
        $message->addReplyTo( apply_filters('form_message_replyto', $input['email']) );
        $message->isHTML(true);
        $message->Subject = apply_filters('form_message_subject', $this->form['subject']);
        $message->Body = $theme->render('message.php', get(), false);

        // SMTP setup
        if ( array_key_exists('smtp', $this->form) ) {
            $this->smtp( $message );
        }

        // Try to send main message
        try {
            $message->send();
        } catch( Exception $e ) {
            $this->errors['message-not-sent'] = ( $this->config['environment'] == 'dev' ? $message->ErrorInfo : 'Unable to send message e-mail.' );
        }

        if ( isset($this->form['autoresponder']) && $this->form['autoresponder'] != false ) {

            // Build E-mail Autoresponder
            $autoresponder = new PHPMailer();
            $autoresponder->setFrom( apply_filters('form_autoresponder_from', $this->form['recipient']) );
            $autoresponder->addAddress( apply_filters('form_autoresponder_to', $input['email']) );
            $autoresponder->isHTML(true);
            
            $autoresponder_subject = ( isset($this->form['subject_autoresponder']) ? $this->form['subject_autoresponder'] : 'Thank you' );
            $autoresponder->Subject = apply_filters('form_autoresponder_subject', $autoresponder_subject);
            $autoresponder->Body = $theme->render('autoresponder.php', get(), false);

            // SMTP setup
            if ( array_key_exists('smtp', $this->form) ) {
                $this->smtp( $autoresponder );
            }

            // Try to send autoresponder message
            try {
                $autoresponder->send();
            } catch( Exception $e ) {
                $this->errors['autoresponder-not-sent'] = ( $this->config['environment'] == 'dev' ? $autoresponder->ErrorInfo : 'Unable to send message e-mail.' );
            }

        }

    }

    /**
     * SMTP
     *
     * @param $mailer (PHPMailer instance)
     */
    private function smtp( PHPMailer $mailer ) {

        // Set some defaults
        $defaults = array(
            'debug'     => 2,
            'host'      => 'mail.' . str_replace(array('http://', 'https://', 'http://www.', 'https://www.'), '', $this->config['url']),
            'port'      => 25,
            'smtpauth'  => true,
            'email'     => $this->form['smtp']['username']
        );

        // Merge with config
        $config = array_merge( $defaults, $this->form['smtp'] );

        // Setup SMTP
        $mailer->isSMTP();
        $mailer->SMTPDebug = $config['debug'];      // Debug setting
        $mailer->Host = $config['host'];            // SMTP host
        $mailer->Port = $config['port'];            // SMTP port
        $mailer->SMTPAuth = $config['smtpauth'];    // Required auth?
        $mailer->Username = $config['username'];    // SMTP username
        $mailer->Password = $config['password'];    // SMTP password
        $mailer->setFrom($config['email']);         // Set the from address to that of the mailbox we're using

    }

    /**
     * Save
     *
     * Saves the form input to a database
     * @param $input (array) Input array
     */
    private function save( $input ) {

        // Make sure we have a connection
        if ( !$this->db instanceof Medoo ) {
            return false;
        }

        // Build Data to insert
        $data = array(
            'ip'        => $this->get_ip(),
            'formid'    => $this->id,
            'data'      => serialize($input)
        );

        // Save it!
        return $this->db->insert('form_submissions', $data);

    }

    /**
     * Connect
     *
     * Attempt to connect to a database
     * @param $credentials (array) Database credentials
     * @return (object) Medoo instance
     */
    private function connect( array $credentials ) {

        // Set some defaults
        $defaults = array(
            'database_type'     => 'mysql',
            'server'            => 'localhost',
            'username'          => 'root',
            'password'          => 'root'
        );

        // Try to create a connection
        try {
            return new Medoo( array_merge($defaults, $credentials) );
        } catch (Exception $e) {

            // Create message
            $message = 'Unable to connect to database.';

            // If in development - show the error
            if ( $this->config['environment'] == 'dev' ) {
                $message .= sprintf(' [%s]', $e->getMessage());
            }

            $this->errors['database-error'] = $message;

            return false;
        }

    }

    /**
     * Errors
     *
     * @return (array) Errors array
     */
    public function errors() {
        return $this->errors;
    }

    /**
     * Form Exists
     * @param $id (string) Form ID
     * @return (bool)
     */
    public function form_exists( $id ) {
        return array_key_exists( $id, $this->config['forms'] );
    }

    /**
     * Get IP
     * Get the users IP
     */
    private function get_ip() {

        if ( !empty($_SERVER['HTTP_CLIENT_IP']) ) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

}
