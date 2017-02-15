<?php

/**
 * @author  wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopHagglePluginSettingsAction extends waViewAction {

    public function execute() {
        $field_types = array(
            'comment' => 'Комментарий',
            'custom' => 'Дополнительное поле',
        );
        $this->view->assign(array(
            'plugin' => wa()->getPlugin('haggle'),
            'field_types' => $field_types,
            'templates' => shopHaggleHelper::getTemplates(),
        ));
    }

}
