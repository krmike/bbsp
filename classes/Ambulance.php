<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Ambulance {

    private $table = "ambulance_destination";

    public function __construct()
    {
        $this->data = new Db();
    }

    public function getById($destinationId){
        return $this->data->getById(
            $this->table,
            $destinationId,
            "id"
        );
    }

    public function getList() {
        $data = $this->data->getData(
            $this->table,
            array(),
            "name ASC"
        );
        $ambulance = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $ambulance[$item['id']] = $item;
            }
        }
        return $ambulance;
    }

    public function add($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function updateById($destinationId, $aData) {
        $this->data->updateById(
            $this->table,
            $destinationId,
            $aData,
            'id'
        );
    }

    public function deleteById($destinationId) {
        $this->data->deleteById($this->table,
            $destinationId,
            "id"
            );
    }

}