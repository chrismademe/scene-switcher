<?php

// Type is not an attribute
unset($context['type']);

// Value is not an attribute
$val = false;
if ( isset($context['value']) ) {
    $value = $context['value'];
    unset($context['value']);
}

?>
<textarea <?php foreach ( $context as $attr => $value ): printf('%s="%s" ', $attr, $value); endforeach; ?>><?= $val; ?></textarea>
