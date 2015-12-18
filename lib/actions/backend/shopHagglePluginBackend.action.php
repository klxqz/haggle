<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopHagglePluginBackendAction extends waViewAction {

    public function execute() {
        $this->setLayout(new shopHagglePluginBackendLayout());
    }

}
