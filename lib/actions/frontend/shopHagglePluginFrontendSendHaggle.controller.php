<?php

/**
 * @author  wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopHagglePluginFrontendSendHaggleController extends waJsonController {

    public function execute() {
        try {
            $app_settings_model = new waAppSettingsModel();
            $settings = $app_settings_model->get(shopHagglePlugin::$plugin_id);
            $product_id = waRequest::post('product_id', 0, waRequest::TYPE_INT);
            $haggle = waRequest::post('haggle', array(), waRequest::TYPE_ARRAY);
            $price = waRequest::post('price');

            $haggle = $this->prepareFields($haggle);

            $haggle_model = new shopHagglePluginModel();
            $data = array(
                'datetime' => date('Y-m-d H:i:s'),
                'product_id' => $product_id,
                'price' => $price,
                'additional_fields' => json_encode($haggle),
                'contact_id' => wa()->getUser()->getId(),
            );
            $id = $haggle_model->insert($data);

            if (!empty($settings['email_notification'])) {
                $data['id'] = $id;
                $this->sendNotification($data);
            }

            $this->response = $settings['after_send'];
        } catch (waException $ex) {
            $this->setError($ex->getMessage());
        }
    }

    private function prepareFields($fields = array()) {
        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get(shopHagglePlugin::$plugin_id);
        $form_fields = array();
        if (!empty($settings['form_fields'])) {
            $form_fields = json_decode($settings['form_fields'], true);
        }

        $result = array();
        foreach ($fields as $index => $field) {
            if (!empty($form_fields[$index]['name'])) {
                $name = $form_fields[$index]['name'];
                $result[$name] = $field;
            }
        }
        return $result;
    }

    private function sendNotification($request) {
        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get(shopHagglePlugin::$plugin_id);

        $general = wa('shop')->getConfig()->getGeneralSettings();

        if (!empty($settings['email'])) {
            $email = $settings['email'];
        } else {
            $email = $general['email'];
        }
        $product_model = new shopProductModel();
        $request['product'] = $product_model->getById($request['product_id']);

        $request['additional_fields'] = json_decode($request['additional_fields'], true);

        $template_path = wa()->getAppPath('plugins/haggle/templates/Notification.html', 'shop');
        $view = wa()->getView();
        $view->assign('request', $request);
        $notification = $view->fetch($template_path);
        $message = new waMailMessage('Заявка торговаться №' . $request['id'], $notification);
        $message->setTo($email);
        $from = $general['email'];
        $message->setFrom($from, $general['name']);
        $message->send();
    }

}
