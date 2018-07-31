<?php

get_header(); ?>

    <?php if ( $alerts = get('form.alerts') ): ?>
    <div class="container">
        <?php foreach ( $alerts as $alert ): ?>
            <div class="cf-alert <?php echo $alert['type']; ?>">
                <?php echo $alert['message']; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

<?php get_footer(); ?>
