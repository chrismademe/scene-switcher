<?php get_partial('analytics'); ?>

<!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<?php if ( $scripts = get_scripts() ): ?>
    <?php foreach ( $scripts as $id => $script ): ?>
        <script id="<?php echo $id; ?>" src="<?php echo $script; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ( get('site.environment') == 'prod' ): ?>
<script>document.addEventListener("DOMContentLoaded",function(){cookieChoices.showCookieConsentBar("<?php echo get('site.company'); ?> uses cookies to give you the best experience.","OK","Learn more","http://www.allaboutcookies.org")});</script>
<?php endif; ?>

<?php footer(); ?>

</body>
</html>
