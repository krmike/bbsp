<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Radio {

    private $table = "radio";

    public function __construct()
    {
        $this->data = new Db();
    }

    public function getById($radioId){
        return $this->data->getById(
            $this->table,
            $radioId,
            "id"
        );
    }

    public function getList() {
        return $this->data->getData(
            $this->table,
            array(),
            "name ASC"
        );
    }

    public function add($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function updateById($radioId, $aData) {
        $this->data->updateById(
            $this->table,
            $radioId,
            $aData,
            'id'
        );
    }

    public function deleteById($radioId) {
        $this->data->deleteById($this->table,
            $radioId,
            "id"
            );
    }

}