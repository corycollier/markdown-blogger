<?php
// Setup the local variables for the layout
extract($vars);
?>
<!doctype html>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <meta name="keywords" content="<?php echo implode(', ', $keywords); ?>" />
        <meta name="description" content="<?php echo $description; ?>" />
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    </head>

    <body>
        <?php echo $content, PHP_EOL; ?>
    </body>
</html>
