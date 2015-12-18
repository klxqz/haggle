<?php

/**
 * @author  wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
$plugin_id = array('shop', 'haggle');
$app_settings_model = new waAppSettingsModel();
$app_settings_model->set($plugin_id, 'status', '1');
$app_settings_model->set($plugin_id, 'frontend_product_cart', 'cart');
$app_settings_model->set($plugin_id, 'fancy_box', '1');
$app_settings_model->set($plugin_id, 'button_text', 'Торговаться');
$app_settings_model->set($plugin_id, 'email_notification', '1');
$app_settings_model->set($plugin_id, 'email', '');
$app_settings_model->set($plugin_id, 'title', 'Ваша цена?');
$app_settings_model->set($plugin_id, 'show_product_name', '1');
$form_fields = array(
    array(
        'name' => 'Ваша цена',
        'required' => 1,
        'type' => 'price',
        'disabled' => 1,
    ),
    array(
        'name' => 'Ваше имя',
        'required' => 0,
        'type' => 'custom',
        'disabled' => 0,
    ),
    array(
        'name' => 'Ваш телефон',
        'required' => 0,
        'type' => 'custom',
        'disabled' => 0,
    ),
    array(
        'name' => 'Ваш Email',
        'required' => 0,
        'type' => 'custom',
        'disabled' => 0,
    ),
    array(
        'name' => 'Сайт, где дешевле',
        'required' => 0,
        'type' => 'custom',
        'disabled' => 0,
    ),
    array(
        'name' => 'Комментарий',
        'required' => 0,
        'type' => 'comment',
        'disabled' => 0,
    ),
    array(
        'name' => 'Купить',
        'required' => 1,
        'type' => 'submit',
        'disabled' => 1,
    ),
);
$app_settings_model->set($plugin_id, 'form_fields', json_encode($form_fields));
$app_settings_model->set($plugin_id, 'after_send', "Ваше предложение отправлено!<br/>\nПосле рассмотрения заявки менеджер свяжется с Вами!");
