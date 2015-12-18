<?php

/**
 * @author  wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
return array(
    'shop_haggle' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'datetime' => array('datetime', 'null' => 0),
        'product_id' => array('int', 11, 'null' => 0),
        'price' => array('decimal', "15,4", 'null' => 0),
        'additional_fields' => array('text'),
        'contact_id' => array('int', 11, 'null' => 0),
        'read' => array('tinyint', 1, 'null' => 0, 'default' => '0'),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
);
