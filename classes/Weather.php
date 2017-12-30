<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Weather {

    private $table = "weather";

    public function __construct()
    {
        $this->data = new Db();
    }

    public function getById($weatherId){
        return $this->data->getById(
            $this->table,
            $weatherId,
            "id"
        );
    }

    public function getList() {
        $data = $this->data->getData(
            $this->table,
            array(),
            "name ASC"
        );
        $weather = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $weather[$item['id']] = $item;
            }
        }
        return $weather;
    }

    public function add($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function updateById($weatherId, $aData) {
        $this->data->updateById(
            $this->table,
            $weatherId,
            $aData,
            'id'
        );
    }

    public function deleteById($weatherId) {
        $this->data->deleteById($this->table,
            $weatherId,
            "id"
            );
    }

}