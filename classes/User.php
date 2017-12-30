<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class User {

    private $table = "users";
    private $tableTypes = "user_types";

    public function __construct()
    {
        $this->data = new Db();
        $this->access = new Access();
    }

    public function getById($userId){
        return $this->data->getById(
            $this->table,
            $userId,
            "user_id"
        );
    }

    public function getName($userId) {
        $user = $this->data->getById(
            $this->table,
            $userId,
            "user_id"
        );
        $name = $user['name']." ".$user['surname'];
        return $name;
    }

    public function getTypesList() {
        $types = $this->data->getData(
            $this->tableTypes,
            array()
        );

        $typeList = array();
        foreach ($types as $type) {
            $typeList[$type['user_type_id']] = $type;
        }

        return $typeList;
    }

    public function getList($typeId = null) {
        $where = array();
        if ($typeId) {
            $where['user_type'] = $typeId;
        }
        $data = $this->data->getData(
            $this->table,
            $where,
            "user_type ASC, login ASC"
        );
        $users = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $users[$item['user_id']] = $item;
            }
        }
        return $users;
    }

    public function addUser($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function updateById($userId, $aData) {
        $this->data->updateById(
            $this->table,
            $userId,
            $aData,
            'user_id'
        );
    }

    public function deleteById($userId) {
        $this->data->deleteById($this->table,
            $userId,
            "user_id"
            );
    }

}