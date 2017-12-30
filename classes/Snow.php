<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Snow {

    private $table = "snow";

    public function __construct()
    {
        $this->data = new Db();
    }

    public function getById($snowId){
        return $this->data->getById(
            $this->table,
            $snowId,
            "id"
        );
    }

    public function getList() {
        $data = $this->data->getData(
            $this->table,
            array(),
            "name ASC"
        );
        $snow = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $snow[$item['id']] = $item;
            }
        }
        return $snow;
    }

    public function add($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function updateById($snowId, $aData) {
        $this->data->updateById(
            $this->table,
            $snowId,
            $aData,
            'id'
        );
    }

    public function deleteById($snowId) {
        $this->data->deleteById($this->table,
            $snowId,
            "id"
            );
    }

}