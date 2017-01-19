<?php
$f3 = require('lib/base.php');
$f3->route('GET /',
    function($f3) {
        echo Template::instance()->render('ui/index.html');
    }
);
$f3->run();
?>
