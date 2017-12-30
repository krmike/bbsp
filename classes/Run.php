<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Run {

    private $table = "runs";

    public function __construct()
    {
        $this->data = new Db();
    }

    public function getById($runId){
        return $this->data->getById(
            $this->table,
            $runId,
            "id"
        );
    }

    public function getList($liftId = null) {
        $where = array();
        if ($liftId) {
            $where['lift_id'] = $liftId;
        }
        $data = $this->data->getData(
            $this->table,
            array(),
            "lift_id ASC, name ASC"
        );
        $list = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $list[$item['id']] = $item;
            }
        }
        return $list;
    }

    public function getActiveList() {
        $data = $this->data->getData(
            $this->table,
            array(
                'active'    => 1
            ),
            "lift_id ASC, name ASC"
        );
        $list = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $list[$item['lift_id']][$item['id']] = $item;
            }
        }
        return $list;
    }

    public function add($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function updateById($runId, $aData) {
        $this->data->updateById(
            $this->table,
            $runId,
            $aData,
            'id'
        );
    }

    public function deleteById($runId) {
        $this->data->deleteById($this->table,
            $runId,
            "id"
            );
    }

}