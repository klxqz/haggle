<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopHagglePluginFrontendAddProductController extends waJsonController {

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


        $this->addCart();
        $this->response['message'] = 'Товар добавлен в корзину';
    }

    public function addCart() {
        $code = waRequest::cookie('shop_cart');
        if (!$code) {
            $code = md5(uniqid(time(), true));
            // header for IE
            wa()->getResponse()->addHeader('P3P', 'CP="NOI ADM DEV COM NAV OUR STP"');
            // set cart cookie
            wa()->getResponse()->setCookie('shop_cart', $code, time() + 30 * 86400, null, '', false, true);
        }
        $this->cart = new shopCart($code);
        $this->cart_model = new shopCartItemsModel();

        $data = waRequest::post();
        $this->is_html = waRequest::request('html');

        // add service
        if (isset($data['parent_id'])) {
            $this->addService($data);
            return;
        }

        // add sku
        $sku_model = new shopProductSkusModel();
        $product_model = new shopProductModel();
        if (!isset($data['product_id'])) {
            $sku = $sku_model->getById($data['sku_id']);
            $product = $product_model->getById($sku['product_id']);
        } else {
            $product = $product_model->getById($data['product_id']);
            if (isset($data['sku_id'])) {
                $sku = $sku_model->getById($data['sku_id']);
            } else {
                if (isset($data['features'])) {
                    $product_features_model = new shopProductFeaturesModel();
                    $sku_id = $product_features_model->getSkuByFeatures($product['id'], $data['features']);
                    if ($sku_id) {
                        $sku = $sku_model->getById($sku_id);
                    } else {
                        $sku = null;
                    }
                } else {
                    $sku = $sku_model->getById($product['sku_id']);
                    if (!$sku['available']) {
                        $sku = $sku_model->getByField(array('product_id' => $product['id'], 'available' => 1));
                    }

                    if (!$sku) {
                        $this->errors = _w('This product is not available for purchase');
                        return;
                    }
                }
            }
        }

        $quantity = waRequest::post('quantity', 1);
        if ($product && $sku) {
            // check quantity
            if (!wa()->getSetting('ignore_stock_count')) {
                $c = $this->cart_model->countSku($code, $sku['id']);
                if ($sku['count'] !== null && $c + $quantity > $sku['count']) {
                    $quantity = $sku['count'] - $c;
                    $name = $product['name'] . ($sku['name'] ? ' (' . $sku['name'] . ')' : '');
                    if (!$quantity) {
                        if ($sku['count'] > 0)
                            $this->errors = sprintf(_w('Only %d pcs of %s are available, and you already have all of them in your shopping cart.'), $sku['count'], $name);
                        else
                            $this->errors = sprintf(_w('Oops! %s just went out of stock and is not available for purchase at the moment. We apologize for the inconvenience.'), $name);
                        return;
                    } else {
                        $this->response['error'] = sprintf(_w('Only %d pcs of %s are available, and you already have all of them in your shopping cart.'), $sku['count'], $name);
                    }
                }
            }
            $services = waRequest::post('services', array());
            if ($services) {
                $variants = waRequest::post('service_variant');
                $temp = array();
                $service_ids = array();
                foreach ($services as $service_id) {
                    if (isset($variants[$service_id])) {
                        $temp[$service_id] = $variants[$service_id];
                    } else {
                        $service_ids[] = $service_id;
                    }
                }
                if ($service_ids) {
                    $service_model = new shopServiceModel();
                    $temp_services = $service_model->getById($service_ids);
                    foreach ($temp_services as $row) {
                        $temp[$row['id']] = $row['variant_id'];
                    }
                }
                $services = $temp;
            }
            $item_id = null;
            $item = $this->cart_model->getItemByProductAndServices($code, $product['id'], $sku['id'], $services);
            if ($item) {
                $item_id = $item['id'];
                $this->cart->setQuantity($item_id, $item['quantity'] + $quantity);
            }
            if (!$item_id) {
                $data = array(
                    'create_datetime' => date('Y-m-d H:i:s'),
                    'product_id' => $product['id'],
                    'sku_id' => $sku['id'],
                    'quantity' => $quantity,
                    'type' => 'product'
                );
                if ($services) {
                    $data_services = array();
                    foreach ($services as $service_id => $variant_id) {
                        $data_services[] = array(
                            'service_id' => $service_id,
                            'service_variant_id' => $variant_id,
                        );
                    }
                } else {
                    $data_services = array();
                }
                $item_id = $this->cart->addItem($data, $data_services);
            }

        } else {
            throw new waException('product not found');
        }
    }

}
