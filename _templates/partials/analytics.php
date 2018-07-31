<?php if ( get('site.environment') == 'prod' ): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo get('site.analytics'); ?>"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}

    gtag( 'js', new Date() );
    gtag( 'config', '<?php echo get('site.analytics'); ?>' );
</script>
<?php endif; ?>
