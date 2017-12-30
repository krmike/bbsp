<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 13.05.2015
 * Time: 23:42
 */

class SignOut {

    private $table = "sign_out";

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
        $SQL = "SELECT * FROM sign_in WHERE patroller_id = ".$patrollerId." AND date = '".date('Y-m-d')."'";
        $result = $this->data->sql_query($SQL);
        if( mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
}

}