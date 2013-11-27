<?php

/**
 * @author Коробов Николай wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
return array(
    'status' => array(
        'title' => 'Статус',
        'description' => '',
        'value' => '1',
        'control_type' => waHtmlControl::SELECT,
        'options' => array(
            '0' => 'Выключен',
            '1' => 'Включен',
        )
    ),
    'text_button' => array(
        'title' => 'Текст кнопки "Нашли дешевле?"',
        'description' => '',
        'value' => 'Нашли дешевле?',
        'control_type' => waHtmlControl::INPUT,
    ),
    'text_title' => array(
        'title' => 'Текст заголовка "Нашли дешевле?"',
        'description' => '',
        'value' => 'Нашли дешевле?',
        'control_type' => waHtmlControl::INPUT,
    ),
    'text_link' => array(
        'title' => 'Текст "Ссылка на сайт, где дешевле"',
        'description' => '',
        'value' => 'Ссылка на сайт, где дешевле',
        'control_type' => waHtmlControl::INPUT,
    ),
    'text_user_price' => array(
        'title' => 'Текст "Своя цена"',
        'description' => '',
        'value' => 'Своя цена',
        'control_type' => waHtmlControl::INPUT,
    ),
    'text_message' => array(
        'title' => 'Текст "Сообщение"',
        'description' => '',
        'value' => 'Сообщение',
        'control_type' => waHtmlControl::INPUT,
    ),
);
