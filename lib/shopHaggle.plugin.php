<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopHagglePlugin extends shopPlugin {

    public static $plugin_id = array('shop', 'haggle');

    public function backendMenu() {
        if ($this->getSettings('status')) {
            $model = new waModel();
            $sql = "SELECT count(*) FROM `shop_haggle` WHERE `read` = 0";
            $unread_count = (int) $model->query($sql)->fetchField();

            $html = '<li ' . (waRequest::get('plugin') == $this->id ? 'class="selected"' : 'class="no-tab"') . '>
                        <a href="?plugin=haggle">
                            Торговаться
                            ' . (waRequest::get('plugin') != $this->id && $unread_count ? '<sup style="display:inline" class="red">' . $unread_count . '</sup>' : '') . '
                        </a>
                    </li>';
            return array('core_li' => $html);
        }
    }

    public function frontendHead() {
        if ($this->getSettings('status')) {
            if ($this->getSettings('fancy_box')) {
                waSystem::getInstance()->getResponse()->addCss('plugins/haggle/vendor/fancyBox/jquery.fancybox.css', 'shop');
                waSystem::getInstance()->getResponse()->addJs('plugins/haggle/vendor/fancyBox/jquery.fancybox.pack.js', 'shop');
            }
            $haggle_url = wa()->getRouting()->getUrl('shop/frontend/haggle');
            return <<<HTML
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.haggle-button', function () {
            $.fancybox.open({
                href: '$haggle_url?product_id=' + $(this).data('product-id'),
                type: 'ajax'
            });
            return false;
        });
    });
</script>
HTML;
        }
    }

    public function frontendProduct($product) {
        if ($this->getSettings('frontend_product_cart')) {
            return array($this->getSettings('frontend_product_cart') => self::display($product));
        }
    }

    public static function display($product) {
        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get(self::$plugin_id);
        if ($settings['status']) {
            if (!empty($product['id'])) {
                $html = sprintf('<button class="button haggle-button" data-product-id="%d">%s</button>', $product['id'], $settings['button_text']);
            } else {
                $html = 'Задана некорректная переменная $product';
            }
            return $html;
        }
    }

}
