<?php

/**
 * @author  wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopHagglePluginBackendDeleteController extends waJsonController {

    public function execute() {
        try {
            $model = new shopHagglePluginModel();
            if ($ids = waRequest::post('id', null, waRequest::TYPE_ARRAY_INT)) {
                foreach ($ids as $id) {
                    $model->deleteById($id);
                }
            } elseif ($id = waRequest::post('id', 0, waRequest::TYPE_INT)) {
                $model->deleteById($id);
            } else {
                throw new waException('Ошибка: Идентификатор неопределен');
            }
        } catch (Exception $ex) {
            $this->setError($ex->getMessage());
        }
    }

}
