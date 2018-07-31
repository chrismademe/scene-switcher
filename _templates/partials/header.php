<!doctype html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo get('page.title'); ?></title>

    <!-- Site wide stylesheets -->
    <?php if ( $stylesheets = get_stylesheets() ): ?>
        <?php foreach ( $stylesheets as $stylesheet ): ?>
            <link rel="stylesheet" href="<?php echo $stylesheet; ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <?php head(); ?>

</head>
<body class="page-<?php echo get('page.slug'); ?>" itemscope itemtype="http://schema.org/WebPage">
