<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Training {

    private $logTable = "training_log";
    private $categoryTable = "training_categories";
    private $typeTable = "training_types";
    private $patrollersTable = "training_patrollers";

    public function __construct()
    {
        $this->data = new Db();
        $this->Log = new Logger();
    }

    public function getPatrollersById($recId){
        return $this->data->getById(
            $this->patrollersTable,
            $recId,
            "rec_id"
        );
    }

    public function getCategoryById($categoryId){
        return $this->data->getById(
            $this->categoryTable,
            $categoryId,
            "id"
        );
    }

    public function getTypeById($typeId){
        return $this->data->getById(
            $this->typeTable,
            $typeId,
            "id"
        );
    }

    public function getCategoriesList() {
        $data = $this->data->getData(
            $this->categoryTable,
            array(),
            "name ASC"
        );

        $categories = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $categories[$item['id']] = $item;
            }
        }
        return $categories;
    }

    public function getTypesList() {
        $data = $this->data->getData(
            $this->typeTable,
            array(),
            "name ASC"
        );

        $types = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $types[$item['id']] = $item;
            }
        }
        return $types;
    }

    public function getReport($dateFrom, $dateTo, $userId = null, $categoryId = null, $typeId = null){
        if ($userId) {
            $SQL = "SELECT
                training_log.id,
                training_log.date,
                training_log.user_id,
                training_log.category_id,
                training_log.type_id,
                training_log.training_type,
                training_patrollers.rec_id,
                training_patrollers.comment,
                training_patrollers.trainer_comment
            ";
        } else {
            $SQL = " SELECT
                * ";
        }


        if ($userId) {
            $SQL .= ", training_patrollers.rec_id as record ";
        }
        $SQL .= "
            FROM
                training_log ";
        if ($userId) {
            $SQL .= "
            LEFT JOIN
                training_patrollers
            ON
                training_log.id = training_patrollers.log_id
            ";
        }
        $SQL .="
            WHERE
                date >= '".$dateFrom."'
            AND
                date <= '".$dateTo."'
        ";
        if ($userId){
            $SQL .= " AND training_patrollers.patroller_id = '".$userId."' ";
        }
        if ($categoryId) {
            $SQL .= " AND category_id = '".$categoryId."' ";
        }
        if ($typeId) {
            $SQL .= " AND type_id = '".$typeId."' ";
        }
        $SQL .= "ORDER BY category_id DESC, type_id ASC, date ASC ";
        $result = $this->data->sql_query($SQL);

        if (mysqli_num_rows($result) > 0) {
            for ($report = array(); $row = mysqli_fetch_assoc($result); $report[$row['category_id']][] = $row);
        } else $report = array();

        return $report;

    }

    public function getLogPatrollers($logId) {
        $SQL = "SELECT
                *
            FROM
                training_patrollers
            WHERE
                log_id = '".$logId."'
                ";
        $result = $this->data->sql_query($SQL);
        for ($patrollers = array(); $row = mysqli_fetch_assoc($result); $patrollers[] = $row);

        return $patrollers;

    }

    public function addCategory($aData) {
        return $this->data->addData($this->categoryTable, $aData);
    }

    public function addPatroller($aData) {
        return $this->data->addData($this->patrollersTable, $aData);
    }

    public function addType($aData) {
        return $this->data->addData($this->typeTable, $aData);
    }

    public function addLog($aData) {
        return $this->data->addData($this->logTable, $aData);
    }

    public function updateCategoryById($categoryId, $aData) {
        $this->data->updateById(
            $this->categoryTable,
            $categoryId,
            $aData,
            'id'
        );
    }

    public function updateTypeById($typeId, $aData) {
        $this->data->updateById(
            $this->typeTable,
            $typeId,
            $aData,
            'id'
        );
    }

    public function updateTrainerComment ($recId, $comment) {
        $oldData = $this->getPatrollersById($recId);
        $SQL = "UPDATE
                training_patrollers
            SET
                trainer_comment = '".$comment."'
            WHERE
                rec_id = '".$recId."'
        ";
        $this->data->sql_query($SQL);
        $newData = $this->getPatrollersById($recId);
        $this->Log->log($this->patrollersTable, "edit", $oldData, $newData);
    }

    public function deleteCategoryById($categoryId) {
        $this->data->deleteById($this->categoryTable,
            $categoryId,
            "id"
            );
    }

    public function deleteTypeById($typeId) {
        $this->data->deleteById($this->typeTable,
            $typeId,
            "id"
        );
    }

}