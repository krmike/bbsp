<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Visibility {

    private $table = "visibility";

    public function __construct()
    {
        $this->data = new Db();
    }

    public function getById($visibilityId){
        return $this->data->getById(
            $this->table,
            $visibilityId,
            "id"
        );
    }

    public function getList() {
        $data = $this->data->getData(
            $this->table,
            array(),
            "name ASC"
        );
        $visibility = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $visibility[$item['id']] = $item;
            }
        }
        return $visibility;
    }

    public function add($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function updateById($visibilityId, $aData) {
        $this->data->updateById(
            $this->table,
            $visibilityId,
            $aData,
            'id'
        );
    }

    public function deleteById($visibilityId) {
        $this->data->deleteById($this->table,
            $visibilityId,
            "id"
            );
    }

}