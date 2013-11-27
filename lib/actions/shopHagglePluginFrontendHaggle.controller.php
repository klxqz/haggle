<?php

/**
 * @author Коробов Николай wa-plugins.ru <support@wa-plugins.ru>
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
        if ($features) {
            $product_features_model = new shopProductFeaturesModel();
            $sku_id = $product_features_model->getSkuByFeatures($product_id, $features);
        }

        $data = array('price' => (float) $price,
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
