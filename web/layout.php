<?php
// Setup the local variables for the layout
extract($vars);
?>
<!doctype html>
<html>
    <head>
        <title><?php echo $blog_title; ?><?php if ($title) echo ' - ' . $title ; ?></title>
        <meta name="keywords" content="<?php echo $keywords; ?>" />
        <meta name="description" content="<?php echo $description; ?>" />
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    </head>

    <body>
        <h1><?php echo $blog_title; ?></h1>
        <?php
            if ($content) :
                echo $content;
            else :
                foreach ($latest as $blog) :
                    echo $blog->getSnippet();
                endforeach;
            endif;
        ?>

    </body>
</html>
