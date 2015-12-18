<?php

/**
 * @author  wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopHagglePluginSettingsAction extends waViewAction {

    public function execute() {
        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get(shopHagglePlugin::$plugin_id);

        if (!empty($settings['form_fields'])) {
            $settings['form_fields'] = json_decode($settings['form_fields'], true);
        }

        $field_types = array(
            'comment' => 'Комментарий',
            'custom' => 'Дополнительное поле',
        );

        $this->view->assign('field_types', $field_types);
        $this->view->assign('settings', $settings);
    }

}
