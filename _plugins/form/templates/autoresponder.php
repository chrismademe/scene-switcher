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
    <p style="font-size: 18px;">Thanks for contacting <?php echo get('site.company'); ?></p>
    <hr style="border: 1px solid #ddd">
    <p style="margin-top: 30px;">
        Hi <?php echo get('form.input.name'); ?>,<br><br>
        Thanks for contacting <?php echo get('site.company') ?>. Your message has been sent and we'll be in contact soon.<br><br>
        Kind regards,<br>
        <?php echo get('site.company') ?>
    </p>
</div>
