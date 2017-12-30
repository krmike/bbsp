<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Penthrane {

    private $table = "penthrane_stock";

    public function __construct()
    {
        $this->data = new Db();
    }

    public function add($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function getReport($dateFrom, $dateTo, $userId = null, $operation = null) {
        $SQL = "SELECT * FROM " . $this->table . "
            WHERE `date` >= '" . $dateFrom . "00:00:00" . "' 
            AND `date` <= '" . $dateTo . "00:00:00" . "'
        ";
        if ($userId) {
            $SQL .= " AND user_id = " . intval($userId). " ";
        }
        if ($operation) {
            $SQL .= " AND operation = " . intval($operation) . " ";
        }
        $SQL .= "ORDER BY date DESC";

        $result = $this->data->sql_query($SQL);
        for ($data = array(); $row = mysqli_fetch_assoc($result); $data[] = $row);

        $report = array(
            'stock' => $data,
        );

        $SQL = "SELECT SUM(qty) as qty, operation FROM " . $this->table. " 
            WHERE `date` < '" . $dateFrom . "00:00:00" . "' 
            GROUP BY operation;
        ";
        $res = $this->data->sql_query($SQL);
        for ($data = array(); $row = mysqli_fetch_assoc($res); $data[$row['operation']] = $row['qty']);

        $summary = $data['1'] - $data['2'] - $data['3'];
        $report['summary'] = $summary;
        return $report;
    }

}