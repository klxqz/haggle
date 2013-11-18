<?php

class shopHagglePlugin extends shopPlugin {

    public function frontendProduct() {
        $view = wa()->getView();
        $html = $view->fetch('plugins/haggle/templates/FrontendProduct.html');
        return array('cart' => $html);
    }

}
