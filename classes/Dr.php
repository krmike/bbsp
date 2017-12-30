<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Dr {

    private $table = "dr";

    public function __construct()
    {
        $this->data = new Db();
    }

    public function getById($drId){
        return $this->data->getById(
            $this->table,
            $drId,
            "id"
        );
    }

    public function getList() {
        $data = $this->data->getData(
            $this->table,
            array(),
            "name ASC"
        );
        $dr = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $dr[$item['id']] = $item;
            }
        }
        return $dr;
    }

    public function add($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function updateById($drId, $aData) {
        $this->data->updateById(
            $this->table,
            $drId,
            $aData,
            'id'
        );
    }

    public function deleteById($drId) {
        $this->data->deleteById($this->table,
            $drId,
            "id"
            );
    }

}