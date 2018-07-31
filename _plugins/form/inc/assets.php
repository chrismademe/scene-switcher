<?php

// Set Extension depending on site environment
$_ext = ( get('site.environment') == 'prod' ? '.min.' : '.' );

// Plugin CSS
add_stylesheet( 'form-css', sprintf( '/_plugins/form/assets/css/form%scss?v=%s', $_ext, get('plugins.form.version') ) );

// Load Google ReCaptcha
add_script( 'form-recaptcha', 'https://www.google.com/recaptcha/api.js' );

// Plugin JS
add_script( 'form-js', sprintf( '/_plugins/form/assets/js/form%sjs?v=%s', $_ext, get('plugins.form.version') ) );
