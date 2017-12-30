<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Referral {

    private $table = "referral";

    public function __construct()
    {
        $this->data = new Db();
    }

    public function getById($referralId){
        return $this->data->getById(
            $this->table,
            $referralId,
            "id"
        );
    }

    public function getList() {
        $data = $this->data->getData(
            $this->table,
            array(),
            "id ASC"
        );
        $referral = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $referral[$item['id']] = $item;
            }
        }
        return $referral;
    }

    public function add($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function updateById($referralId, $aData) {
        $this->data->updateById(
            $this->table,
            $referralId,
            $aData,
            'id'
        );
    }

    public function deleteById($referralId) {
        $this->data->deleteById($this->table,
            $referralId,
            "id"
            );
    }

}