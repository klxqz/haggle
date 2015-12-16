<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopHagglePlugin extends shopPlugin {

    public static $plugin_id = array('shop', 'haggle');

    public function frontendHead() {
        if ($this->getSettings('status')) {
            if ($this->getSettings('modal_type') == 'jquery_ui') {
                waSystem::getInstance()->getResponse()->addCss('wa-content/css/jquery-ui/jquery-ui-1.7.2.custom.css');
                waSystem::getInstance()->getResponse()->addCss('plugins/haggle/css/themes/' . $this->getSettings('theme') . '/jquery.ui.theme.css', 'shop');

                waSystem::getInstance()->getResponse()->addJs('wa-content/js/jquery-ui/jquery.ui.core.min.js');
                waSystem::getInstance()->getResponse()->addJs('wa-content/js/jquery-ui/jquery.ui.widget.min.js');
                waSystem::getInstance()->getResponse()->addJs('wa-content/js/jquery-ui/jquery.ui.mouse.min.js');
                waSystem::getInstance()->getResponse()->addJs('wa-content/js/jquery-ui/jquery.ui.position.min.js');
                waSystem::getInstance()->getResponse()->addJs('wa-content/js/jquery-ui/jquery.ui.button.min.js');
                waSystem::getInstance()->getResponse()->addJs('wa-content/js/jquery-ui/jquery.ui.dialog.min.js');
            } else {
                waSystem::getInstance()->getResponse()->addCss('plugins/haggle/css/basic.css', 'shop');
                waSystem::getInstance()->getResponse()->addJs('plugins/haggle/js/jquery.simplemodal.js', 'shop');
            }
            waSystem::getInstance()->getResponse()->addJs('plugins/haggle/js/haggle.js', 'shop');

            $html = "<script type=\"text/javascript\">
                            $(function () {
                                $.haggle.init({
                                    'haggle_url': '" . wa()->getRouteUrl('shop/frontend/haggle') . "',
                                    'modal_type': '" . $this->getSettings('modal_type') . "'
                                });
                            });
                    </script>";

            return $html;
        }
    }

    public function frontendProduct($product) {
        if ($this->getSettings('default_output')) {
            return array($this->getSettings('frontend_product') => self::display(array('product_id' => $product->id)));
        }
    }

    public static function display($data) {
        $app_settings_model = new waAppSettingsModel();
        if ($app_settings_model->get(self::$plugin_id, 'status')) {
            $html = '<a class="haggle_link" data-json="' . htmlspecialchars(json_encode($data)) . '" href="#">' . $app_settings_model->get(self::$plugin_id, 'text_button') . '</a>';
            return $html;
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
