<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Transport {

    private $table = "transport";

    public function __construct()
    {
        $this->data = new Db();
    }

    public function getById($transportId){
        return $this->data->getById(
            $this->table,
            $transportId,
            "id"
        );
    }

    public function getList() {
        $data = $this->data->getData(
            $this->table,
            array(),
            "name ASC"
        );
        $transport = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $transport[$item['id']] = $item;
            }
        }
        return $transport;
    }

    public function add($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function updateById($transportId, $aData) {
        $this->data->updateById(
            $this->table,
            $transportId,
            $aData,
            'id'
        );
    }

    public function deleteById($transportId) {
        $this->data->deleteById($this->table,
            $transportId,
            "id"
            );
    }

}