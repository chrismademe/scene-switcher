<?php

/**
 * Pretty Exceptions
 */

?>
<!doctype html>
<html>
<head>

    <title>Boilerplate Exception: <?php echo $e->getMessage(); ?></title>
    <link rel="stylesheet" href="/_includes/core/ui/exception.css">

</head>
<body>

    <header class="header">
        <img src="/_includes/core/ui/images/logo.png" alt="Boilerplate Logo">
    </header>

    <div class="container">
        <h2 class="pre-title">Unhandled exception</h2>
        <h1><?php echo $e->getMessage(); ?></h1>

        <div class="trace">
        <?php foreach ( $e->getTrace() as $item ): ?>
            <div class="trace-item">

                <?php if ( isset($item['file']) ): ?>
                <header class="flex">
                    <div class="column left line">
                        <?php echo $item['line']; ?>
                    </div>
                    <div class="column file">
                        <?php echo str_replace( dirname(__DIR__), '', $item['file'] ); ?>
                    </div>
                </header>
                <?php endif; ?>

                <main>
                    <?php if ( isset($item['class']) ): ?>
                    <div class="flex info">
                        <div class="column left label">Class</div>
                        <div class="column text class"><?php echo $item['class']; ?></div>
                    </div>
                    <?php endif; ?>

                    <?php if ( isset($item['function']) ): ?>
                    <div class="flex info">
                        <div class="column left label">Function</div>
                        <div class="column text function"><?php echo $item['function']; ?>()</div>
                    </div>
                    <?php endif; ?>
                </main>

            </div>
        <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
