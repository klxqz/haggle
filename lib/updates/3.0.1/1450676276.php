<?php

try {
    $files = array(
        'plugins/haggle/lib/actions/shopHagglePluginFrontendHaggle.controller.php',
        'plugins/haggle/lib/config/settings.php',
        'plugins/haggle/templates/FrontendProduct.html',
        'plugins/haggle/css/basic.css',
        'plugins/haggle/css/basic_ie.css',
        'plugins/haggle/js/jquery.simplemodal.js',
        'plugins/haggle/img/basic/x.png',
        'plugins/haggle/img/basic/',
    );

    foreach ($files as $file) {
        waFiles::delete(wa()->getAppPath($file, 'shop'), true);
    }
} catch (Exception $e) {
    
}
