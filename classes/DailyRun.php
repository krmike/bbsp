<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 13.05.2015
 * Time: 23:42
 */

class DailyRun {

    private $table = "daily_run";

    public function __construct()
    {
        $this->data = new Db();
        $this->Logger = new Logger();
    }

    public function getById($recordId){
        return $this->data->getById(
            $this->table,
            $recordId,
            "id"
        );
    }

    public function getList() {
        return $this->data->getData(
            $this->table,
            array(),
            "date ASC, time ASC"
        );
    }

    public function updateById($runId, $aData) {
        $this->data->updateById($this->table, $runId, $aData, "id");
    }

    public function getReport($dateFrom, $dateTo, $runId = null, $liftId = null, $userId = null){
        $SQL = "SELECT
                *
            FROM
                daily_run
            WHERE
                date >= '".$dateFrom."'
            AND
                date <= '".$dateTo."'
        ";
        if ($runId) {
            $SQL .= " AND run_id = '".$runId."' ";
        }
        if ($liftId) {
            $SQL .= " AND lift_id = '".$liftId."' ";
        }
        if ($userId) {
            $SQL .= " AND user_id = '".$userId."' ";
        }
        $SQL .= " ORDER BY date ASC, time ASC ";
        $result = $this->data->sql_query($SQL);
        for ($report = array(); $row = mysqli_fetch_assoc($result); $report[$row['date']][$row['time']][$row['lift_id']][$row['run_id']] = $row);
        return $report;

    }



    public function getEditable(){
        $SQL = "SELECT
                *
            FROM
                daily_run
            WHERE
                DATE = '".date("Y-m-d")."' ";
        $SQL .= " ORDER BY date ASC, time ASC ";
        $result = $this->data->sql_query($SQL);
        for ($report = array(); $row = mysqli_fetch_assoc($result); $report[$row['date']][$row['time']][$row['lift_id']][$row['run_id']] = $row);
        return $report;

    }

    public function add($aData) {
        $recordId =  $this->data->addData($this->table, $aData);
        $newRecord = $this->getById($recordId);
        $this->Logger->log($this->table, "add", null, $newRecord);
        return $recordId;
    }

}