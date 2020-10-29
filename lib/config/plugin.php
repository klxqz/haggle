<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
return array(
    'name' => 'Торговаться',
    'description' => 'Покупатель предлагает свою цену за товар',
    'vendor' => '985310',
    'version' => '3.0.3',
    'img' => 'img/haggle.png',
    'shop_settings' => true,
    'frontend' => true,
    'handlers' => array(
        'backend_menu' => 'backendMenu',
        'frontend_head' => 'frontendHead',
        'frontend_product' => 'frontendProduct',
    ),
);
//EOF
