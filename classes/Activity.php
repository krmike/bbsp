<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Activity {

    private $table = "activity";

    public function __construct()
    {
        $this->data = new Db();
    }

    public function getById($activityId){
        return $this->data->getById(
            $this->table,
            $activityId,
            "id"
        );
    }

    public function getList() {
        $data = $this->data->getData(
            $this->table,
            array(),
            "name ASC"
        );
        $act = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $act[$item['id']] = $item;
            }
        }
        return $act;
    }

    public function add($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function updateById($activityId, $aData) {
        $this->data->updateById(
            $this->table,
            $activityId,
            $aData,
            'id'
        );
    }

    public function deleteById($activityId) {
        $this->data->deleteById($this->table,
            $activityId,
            "id"
            );
    }

}