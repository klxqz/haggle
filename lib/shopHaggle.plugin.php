<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopHagglePlugin extends shopPlugin {

    public static $templates = array(
        'FrontendHaggle' => array(
            'name' => 'FrontendHaggle.html',
            'tpl_path' => 'plugins/haggle/templates/actions/frontend/',
            'tpl_name' => 'FrontendHaggle',
            'tpl_ext' => 'html',
            'public' => false
        ),
        'haggle_css' => array(
            'name' => 'haggle.css',
            'tpl_path' => 'plugins/haggle/css/',
            'tpl_name' => 'haggle',
            'tpl_ext' => 'css',
            'public' => true
        ),
    );

    public function saveSettings($settings = array()) {
        parent::saveSettings($settings);

        $templates = waRequest::post('templates');
        foreach ($templates as $template_id => $template) {
            $s_template = self::$templates[$template_id];
            if (!empty($template['reset_tpl'])) {
                $tpl_full_path = $s_template['tpl_path'] . $s_template['tpl_name'] . '.' . $s_template['tpl_ext'];
                $template_path = wa()->getDataPath($tpl_full_path, $s_template['public'], 'shop', true);
                @unlink($template_path);
            } else {
                $tpl_full_path = $s_template['tpl_path'] . $s_template['tpl_name'] . '.' . $s_template['tpl_ext'];
                $template_path = wa()->getDataPath($tpl_full_path, $s_template['public'], 'shop', true);
                if (!file_exists($template_path)) {
                    $tpl_full_path = $s_template['tpl_path'] . $s_template['tpl_name'] . '.' . $s_template['tpl_ext'];
                    $template_path = wa()->getAppPath($tpl_full_path, 'shop');
                }
                $content = file_get_contents($template_path);
                if (!empty($template['template']) && strcmp(str_replace("\r", "", $template['template']), str_replace("\r", "", $content)) != 0) {
                    $tpl_full_path = $s_template['tpl_path'] . $s_template['tpl_name'] . '.' . $s_template['tpl_ext'];
                    $template_path = wa()->getDataPath($tpl_full_path, $s_template['public'], 'shop', true);
                    $f = fopen($template_path, 'w');
                    if (!$f) {
                        throw new waException('Не удаётся сохранить шаблон. Проверьте права на запись ' . $template_path);
                    }
                    fwrite($f, $template['template']);
                    fclose($f);
                }
            }
        }
    }

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
        $plugin = wa()->getPlugin('haggle');
        if ($plugin->getSettings('status')) {
            if (!empty($product['id'])) {
                $html = sprintf($plugin->getSettings('button_template'), $product['id'], $plugin->getSettings('button_text'));
            } else {
                $html = 'Задана некорректная переменная $product';
            }
            return $html;
        }
    }

}
