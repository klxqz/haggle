<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
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
    'modal_type' => array(
        'title' => 'Тип всплывающих окон',
        'description' => 'Выбор типа программной библиотеки, которая используется для создания всплывающих окон',
        'value' => 'jquery_ui',
        'control_type' => waHtmlControl::SELECT,
        'options' => array(
            'jquery_ui' => 'jquery ui',
            'jquery_simplemodal' => 'jquery simplemodal',
        )
    ),
    'theme' => array(
        'title' => 'Тема оформления для jquery ui',
        'description' => '',
        'value' => 'base',
        'control_type' => waHtmlControl::SELECT,
        'options' => array(
            'base' => 'base',
            'black-tie' => 'black-tie',
            'blitzer' => 'blitzer',
            'cupertino' => 'cupertino',
            'dark-hive' => 'dark-hive',
            'dot-luv' => 'dot-luv',
            'eggplant' => 'eggplant',
            'excite-bike' => 'excite-bike',
            'flick' => 'flick',
            'hot-sneaks' => 'hot-sneaks',
            'humanity' => 'humanity',
            'le-frog' => 'le-frog',
            'mint-choc' => 'mint-choc',
            'overcast' => 'overcast',
            'pepper-grinder' => 'pepper-grinder',
            'redmond' => 'redmond',
            'smoothness' => 'smoothness',
            'south-street' => 'south-street',
            'start' => 'start',
            'sunny' => 'sunny',
            'swanky-purse' => 'swanky-purse',
            'trontastic' => 'trontastic',
            'ui-darkness' => 'ui-darkness',
            'ui-lightness' => 'ui-lightness',
            'vader' => 'vader'
        )
    ),
    'default_output' => array(
        'title' => 'Вывод плагина в стандартном месте в карточке товара',
        'description' => 'Для вывода используется хук frontend_product, если он отсутствует, плагин может не выводиться в карточке товара',
        'value' => '1',
        'control_type' => waHtmlControl::SELECT,
        'options' => array(
            '0' => 'Выключен',
            '1' => 'Включен',
        )
    ),
    'frontend_product' => array(
        'title' => 'Место вывода плагина',
        'description' => '',
        'value' => 'cart',
        'control_type' => waHtmlControl::SELECT,
        'options' => array(
            'menu' => 'Содержимое, добавляемое рядом со ссылками на дополнительные страницы товара.',
            'cart' => 'Содержимое, добавляемое рядом с кнопкой «В корзину»',
            'block_aux' => 'Блок дополнительной информации в боковой части страницы.',
            'block' => 'Блок дополнительной информации в основной части описания товара.'
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
