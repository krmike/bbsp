<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Role {

    private $table = "roles";

    public function __construct()
    {
        $this->data = new Db();
    }

    public function getById($roleId){
        return $this->data->getById(
            $this->table,
            $roleId,
            "id"
        );
    }

    public function getList() {
        $data = $this->data->getData(
            $this->table,
            array(),
            "name ASC"
        );
        $role = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $role[$item['id']] = $item;
            }
        }
        return $role;
    }

    public function add($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function updateById($roleId, $aData) {
        $this->data->updateById(
            $this->table,
            $roleId,
            $aData,
            'id'
        );
    }

    public function deleteById($roleId) {
        $this->data->deleteById($this->table,
            $roleId,
            "id"
            );
    }

}