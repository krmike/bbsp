<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Status {

    private $table = "statuses";

    public function __construct()
    {
        $this->data = new Db();
    }

    public function getById($statusId){
        return $this->data->getById(
            $this->table,
            $statusId,
            "id"
        );
    }

    public function getList() {
        $data = $this->data->getData(
            $this->table,
            array(),
            "id ASC "
        );
        $list = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $list[$item['id']] = $item;
            }
        }
        return $list;
    }

    public function add($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function updateById($statusId, $aData) {
        $this->data->updateById(
            $this->table,
            $statusId,
            $aData,
            'id'
        );
    }

    public function deleteById($statusId) {
        $this->data->deleteById($this->table,
            $statusId,
            "id"
            );
    }

}