<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 13.05.2015
 * Time: 23:42
 */

class SignIn {

    private $table = "sign_in";
    private $signInType = 1;
    private $signOutType = 2;

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

    public function add($aData) {
        $recordId =  $this->data->addData($this->table, $aData);
        $newRecord = $this->getById($recordId);
        $this->Logger->log($this->table, "add", null, $newRecord);
        return $recordId;
    }

    public function getTodayRecord($patrollerId) {
        $SQL = "SELECT MAX(id) FROM ".$this->table." WHERE patroller_id = '".$patrollerId."' ";
        $result = $this->data->sql_query($SQL);
        if (mysqli_num_rows($result) > 0) {
            $sign = mysqli_fetch_assoc($result);
            $id = $sign['MAX(id)'];
            $SQL = "SELECT sign_type FROM ".$this->table." WHERE id = '".$id."' ";
            $result = $this->data->sql_query($SQL);
            $sign = mysqli_fetch_assoc($result);
            $signType = $sign['sign_type'];
        }

        if( $signType) {
            return $signType;
        } else {
            return false;
        }
    }

    public function getReport($dateFrom, $dateTo, $userId = null) {
        $SQL = "SELECT
                *
            FROM
                sign_in
            WHERE
                date >= '".$dateFrom."'
            AND
                date <= '".$dateTo."'
            ";
        if ($userId) {
            $SQL .= "AND patroller_id = '".$userId."' ";
        }
        $SQL .= " ORDER BY date ASC, time ASC";

        $result = $this->data->sql_query($SQL);
        for ($report = array(); $row = mysqli_fetch_assoc($result); $report[$row['id']] = $row);

        $data['report'] = $report;

        $SQL = "SELECT
                COUNT(*)
            FROM
                sign_in
            WHERE
                date >= '".$dateFrom."'
            AND
                date <= '".$dateTo."'
            AND sign_type = 1
            ";
        if ($userId) {
            $SQL .= "AND patroller_id = '".$userId."' ";
        }
        $SQL .= " ORDER BY date ASC, time ASC";
        $result = $this->data->sql_query($SQL);
        $count = mysqli_fetch_assoc($result);
        $count = $count['COUNT(*)'];

        $data['count'] = $count;

        return $data;
    }

}