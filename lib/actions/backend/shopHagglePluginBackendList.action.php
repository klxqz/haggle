<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopHagglePluginBackendListAction extends waViewAction {

    public function execute() {
        $lazy = waRequest::get('lazy', 0, waRequest::TYPE_INT);
        $offset = waRequest::get('offset', 0, waRequest::TYPE_INT);
        $limit = 30;


        $model = new shopHagglePluginModel();

        $sql = "SELECT count(*) " . $this->getSql();
        $total_count = (int) $model->query($sql)->fetchField();

        $sql = "SELECT * " . $this->getSql() . " LIMIT $offset, $limit";
        $requests = $model->query($sql)->fetchAll();

        if ($requests) {
            $ids = array();
            foreach ($requests as $request) {
                $ids[] = $request['id'];
            }
            $sql = "UPDATE `shop_haggle` SET `read` = 1 WHERE `id` IN (" . implode(',', $ids) . ")";
            $model->exec($sql);
        }


        $requests = $this->prepareRequests($requests);

        $this->view->assign(array(
            'requests' => $requests,
            'offset' => $offset,
            'limit' => $limit,
            'count' => count($requests),
            'total_count' => $total_count,
            'lazy' => $lazy,
        ));
    }

    protected function prepareRequests($requests) {
        $product_model = new shopProductModel();
        foreach ($requests as &$request) {
            $request['additional_fields'] = json_decode($request['additional_fields'], true);
            $request['product'] = $product_model->getById($request['product_id']);
            if ($request['contact_id']) {
                $request['contact'] = new waContact($request['contact_id']);
            }
        }
        unset($request);
        return $requests;
    }

    protected function getSql() {
        $model = new waModel();
        $where = array();
        $sql = "FROM `shop_haggle`" . ($where ? " WHERE " . implode(" AND ", $where) : "") . " ORDER BY `id` DESC";
        return $sql;
    }

}
