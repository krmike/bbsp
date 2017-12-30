<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 13.05.2015
 * Time: 23:42
 */

class DailyLog {

    private $table = "daily_log";

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

    public function updateById($logId, $aData) {
        $this->data->updateById($this->table, $logId, $aData, "id");
    }

    public function getList() {
        return $this->data->getData(
            $this->table,
            array(),
            "date ASC, time ASC"
        );
    }

    public function getReport($dateFrom, $dateTo, $userId = null) {
        $SQL = "SELECT
                *
            FROM
                daily_log
            WHERE
                date >= '".$dateFrom."'
            AND
                date <= '".$dateTo."'
            ";
        if ($userId) {
            $SQL .= " AND user_id = '".$userId."' ";
        }
        $SQL .= "ORDER BY date ASC, time ASC";
        $result = $this->data->sql_query($SQL);
        for ($report = array(); $row = mysqli_fetch_assoc($result); $report[$row['id']] = $row);

        return $report;

    }

    public function getEditable() {
        $SQL = "SELECT
                *
            FROM
                daily_log
            WHERE   daily_log_timestamp > '".date("Y-m-d H:i:s", time() - 172800) ."' ";
        $SQL .= "ORDER BY date ASC, time ASC";
        $result = $this->data->sql_query($SQL);
        for ($report = array(); $row = mysqli_fetch_assoc($result); $report[] = $row);

        return $report;

    }

    public function getLogIncidents($logId) {
        $SQL = "SELECT
                *
            FROM
                incident
            WHERE
                daily_log_id = '".$logId."'
        ";
        $result = $this->data->sql_query($SQL);
        for ($incidents = array(); $row = mysqli_fetch_assoc($result); $incidents[] = $row);

        return $incidents;

    }

    public function add($aData) {
        $recordId =  $this->data->addData($this->table, $aData);
        $newRecord = $this->getById($recordId);
        $this->Logger->log($this->table, "add", null, $newRecord);
        return $recordId;
    }

}