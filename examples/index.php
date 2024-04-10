<?php

use function Ilimic\Classnames\classnames;
use function Ilimic\Classnames\cn;

require_once __DIR__ . '/../vendor/autoload.php';

var_dump(classnames('c-article', 'c-article--small'));
// output: "c-article c-article--small"

var_dump(cn('c-article', 'c-article--small'));
// output: "c-article c-article--small"

var_dump(cn(['c-article', 'c-article--small' => false]));
// output: "c-article"

?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>
        <div class="<?php echo cn('c-box', 'c-box--small'); ?>">
            Small box.
        </div>
        <div class="<?php echo cn('c-content'); ?>">
            <p>Hello world! This is HTML5 Boilerplate.</p>
        </div>
    </body>
</html>
