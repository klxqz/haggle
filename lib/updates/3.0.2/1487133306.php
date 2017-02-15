<?php

$model = new waModel();
try {
    $sql = "ALTER TABLE `shop_haggle` ADD `currency` CHAR( 3 ) NOT NULL AFTER `product_id`";
    $model->query($sql);
} catch (waDbException $ex) {
    
}

$plugin_id = array('shop', 'haggle');
$app_settings_model = new waAppSettingsModel();
$app_settings_model->set($plugin_id, 'button_template', '<button class="button haggle-button" data-product-id="%d">%s</button>');
