<?php
$f3 = require('lib/base.php');

$f3->route('GET /',
    function($f3) {
        echo Template::instance()->render('ui/index.html');
    }
);

$f3->route('POST /api/deploy',
    function($f3) {
        $tag = 'evakili.com-master';
        $archive = $tag . '.zip';
        file_put_contents('../tmp/' . $archive, fopen("https://github.com/evakili/evakili.com/archive/master.zip", "r"));
        
        $zip = new ZipArchive();
        $zip->open('../tmp/' . $archive);
        if (!$zip->extractTo("../tmp/")) {
            echo $zip->status . "\n";
            echo $zip->statusSys . "\n";
        }
        $zip->close();

        $destination = realpath('../public_html/');
        if (file_exists($destination)) {
            $it = new RecursiveDirectoryIterator($destination, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            foreach($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
        }

        $source = realpath('../tmp/' . $tag);
        if (file_exists($source)) {
            $it = new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::SELF_FIRST);
            foreach($files as $file) {
                $destfile = str_replace($source, $destination, $file->getRealPath());
                if ($file->isDir()) {
                    mkdir($destfile);
                } else {
                    copy($file->getRealPath(), $destfile);
                }
            }
        }

    }
);

$f3->run();

?>
