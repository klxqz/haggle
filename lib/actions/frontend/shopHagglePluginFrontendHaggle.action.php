<?php

/**
 * @author  wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopHagglePluginFrontendHaggleAction extends waViewAction {

    public function execute() {
        if ($product_id = waRequest::get('product_id', 0, waRequest::TYPE_INT)) {
            $plugin = wa()->getPlugin('haggle');
            $product = new shopProduct($product_id);
            $this->view->assign(array(
                'settings' => $plugin->getSettings(),
                'product' => $product,
                'haggle_css_url' => shopHaggleHelper::getTemplateUrl('haggle_css'),
            ));
        } else {
            throw new waException('Не определен product_id');
        }
        $FrontendHaggle_tmp = shopHaggleHelper::getTemplates('FrontendHaggle');
        $this->setTemplate($FrontendHaggle_tmp['template_path']);
    }

}
