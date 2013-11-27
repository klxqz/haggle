<?php

/**
 * @author Коробов Николай wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
return array(
    'name' => 'Торговаться',
    'description' => 'Покупатель заказывает товар по своей цене',
    'vendor' => '985310',
    'version' => '1.0.0',
    'img' => 'img/haggle.png',
    'frontend' => true,
    'handlers' => array(
        'frontend_product' => 'frontendProduct',
        'order_calculate_discount' => 'orderCalculateDiscount',
        'order_action.create' => 'orderActionCreate',
    ),
);
//EOF
