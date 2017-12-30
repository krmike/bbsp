<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Lift {

    private $table = "lifts";

    public function __construct()
    {
        $this->data = new Db();
    }

    public function getById($liftId){
        return $this->data->getById(
            $this->table,
            $liftId,
            "id"
        );
    }

    public function getActiveList() {
        $data = $this->data->getData(
            $this->table,
            array(
                'active' => 1
            ),
            "name ASC"
        );
        $list = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $list[$item['id']] = $item;
            }
        }
        return $list;
    }

    public function getList() {
        $data = $this->data->getData(
            $this->table,
            array(),
            "name ASC"
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

    public function updateById($liftId, $aData) {
        $this->data->updateById(
            $this->table,
            $liftId,
            $aData,
            'id'
        );
    }

    public function deleteById($liftId) {
        $this->data->deleteById($this->table,
            $liftId,
            "id"
            );
    }

}