<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopHagglePluginFrontendHaggleController extends waJsonController {

    public function execute() {
        $price = waRequest::post('price');
        $product_id = waRequest::post('product_id');
        $quantity = waRequest::post('quantity');
        $sku_id = waRequest::post('sku_id');
        $features = waRequest::post('features');
        $url = waRequest::post('url');
        $message = waRequest::post('message');
        if (!$price || !$url) {
            $this->errors = 'Заполните обязательные поля';
            return;
        }
        if (!is_numeric($price)) {
            $this->errors = 'Цена должна быть числом';
            return;
        }
        if ($price < 0) {
            $this->errors = 'Цена должна быть больше нуля';
            return;
        }
        if ($features) {
            $product_features_model = new shopProductFeaturesModel();
            $sku_id = $product_features_model->getSkuByFeatures($product_id, $features);
        }
        
        $currency = wa('shop')->getConfig()->getCurrency(false);

        $data = array(
            'price' => (float) $price,
            'currency' => $currency,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'sku_id' => $sku_id,
            'features' => $features,
            'url' => $url,
            'message' => $message,
        );
        $session = wa()->getStorage();
        $HagglePlugin = $session->read('HagglePlugin', array());
        $HagglePlugin[$product_id . '_' . $sku_id] = $data;
        $session->write('HagglePlugin', $HagglePlugin);
    }

}
