<?php

try {
    $files = array(
        'plugins/haggle/README.txt',
    );

    foreach ($files as $file) {
        waFiles::delete(wa()->getAppPath($file, 'shop'), true);
    }
} catch (Exception $e) {

}
