<?php

/**
 * @author  wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopHagglePluginFrontendHaggleAction extends waViewAction {

    public function execute() {
        if ($product_id = waRequest::get('product_id', 0, waRequest::TYPE_INT)) {
            $app_settings_model = new waAppSettingsModel();
            $settings = $app_settings_model->get(shopHagglePlugin::$plugin_id);

            if (!empty($settings['form_fields'])) {
                $settings['form_fields'] = json_decode($settings['form_fields'], true);
            }

            $this->view->assign('settings', $settings);
            $product = new shopProduct($product_id);
            $this->view->assign('product', $product);
        } else {
            throw new waException('Не определен product_id');
        }
    }

}
