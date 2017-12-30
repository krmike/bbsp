<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Trail {

    private $table = "trails";

    public function __construct()
    {
        $this->data = new Db();
    }

    public function getById($trailId){
        return $this->data->getById(
            $this->table,
            $trailId,
            "id"
        );
    }

    public function getList($activityId = null) {
        $where = array();
        if ($activityId) {
            $where['activity_id'] = $activityId;
        }
        $data = $this->data->getData(
            $this->table,
            $where,
            "activity_id ASC, name ASC"
        );

        $trails = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $trails[$item['activity_id']][$item['id']] = $item;
            }
        }
        return $trails;
    }

    public function add($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function updateById($trailId, $aData) {
        $this->data->updateById(
            $this->table,
            $trailId,
            $aData,
            'id'
        );
    }

    public function deleteById($trailId) {
        $this->data->deleteById($this->table,
            $trailId,
            "id"
            );
    }

}