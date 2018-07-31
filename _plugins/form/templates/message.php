<?php

// Get Form ID
$id = get('form.id');

// Get This Form
$form = get('site.forms.' . $id);

?>
<div class="message-body" style="color: #333; font-family: sans-serif; font-size: 16px; margin: 0 auto; max-width: 520px; padding: 40px">
    <?php if ( isset($form['logo']) ): ?>
        <p style="padding-bottom: 20px;">
            <img src="<?php echo $form['logo']; ?>" height="80">
        </p>
    <?php endif; ?>
    <p style="font-size: 18px;"><?php echo get('form.input.name') ?> sent a new message sent from your website.</p>
    <hr style="border: 1px solid #ddd">
    <p style="margin-top: 30px;">
        <?php foreach ( get('form.input') as $key => $field ): ?>
            <span style="display: inline-block; padding-top: 4px;"><strong><?php echo $form['fields'][$key]['label']; ?>: </strong><?php echo nl2br($field); ?></span><br>
        <?php endforeach; ?>
    </p>
</div>
