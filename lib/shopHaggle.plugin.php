<?php

/**
 * @author Коробов Николай wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopHagglePlugin extends shopPlugin {

    public function frontendProduct() {
        if ($this->getSettings('status')) {
            $view = wa()->getView();
            $view->assign('settings', $this->getSettings());
            $html = $view->fetch('plugins/haggle/templates/FrontendProduct.html');
            return array('cart' => $html);
        }
    }

    public function orderCalculateDiscount($params) {
        if ($this->getSettings('status')) {
            $session = wa()->getStorage();
            $HagglePlugin = $session->read('HagglePlugin', array());
            $total_discount = 0;
            foreach ($params['order']['items'] as $item) {
                $index = $item['product_id'] . '_' . $item['sku_id'];
                if (isset($HagglePlugin[$index])) {
                    $HagglePlugin_item = $HagglePlugin[$index];
                    $orig_price = shop_currency($item['price'], $item['currency'], null, false);
                    $user_price = shop_currency($HagglePlugin_item['price'], $HagglePlugin_item['currency'], null, false);
                    $item_discount = $orig_price - $user_price;
                    if ($item_discount > 0) {
                        $total_discount += $item_discount * $item['quantity'];
                    }
                }
            }
            return $total_discount;
        }
    }

    public function orderActionCreate($params) {
        if ($this->getSettings('status')) {
            $session = wa()->getStorage();
            $HagglePlugin = $session->read('HagglePlugin', array());
            $order_id = $params['order_id'];
            $order_model = new shopOrderModel();
            $order = $order_model->getById($order_id);

            $order_items_model = new shopOrderItemsModel();
            $order_items = $order_items_model->getItems($order_id);
            $total_discount = 0;
            $add_comments = array();
            foreach ($order_items as $item) {
                $index = $item['product_id'] . '_' . $item['sku_id'];
                if (isset($HagglePlugin[$index])) {
                    $HagglePlugin_item = $HagglePlugin[$index];
                    $orig_price = $item['price'];
                    $user_price = $HagglePlugin_item['price'];
                    $item_discount = $orig_price - $user_price;
                    if ($item_discount > 0) {
                        $total_discount += $item_discount * $item['quantity'];
                        $add_comments[] = 'Товар ' . $item['name'] . '(' . $HagglePlugin_item['url'] . ') своя цена = ' . $user_price . ' Скидка составила: ' . $item_discount . ($HagglePlugin_item['message'] ? '. Комментарий: ' . $HagglePlugin_item['message'] : '');
                    }
                }
            }
            if ($add_comments) {
                $order['comment'] .= "Покупатель хочет поторговаться!\r\n" . implode("\r\n", $add_comments) . "\r\n" . 'Общая скидка по программе торговаться составила: ' . $total_discount;
                $order_model->updateById($order_id, $order);
            }
            $session->remove('HagglePlugin');
        }
    }

}
