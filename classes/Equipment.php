<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Equipment {

    private $table = "equipment";
    private $management = "equipment_management";
    private $patroller = "patroller_equipment";

    public function __construct()
    {
        $this->data = new Db();
        $this->Logger = new Logger();
    }

    public function getById($equipId){
        return $this->data->getById(
            $this->table,
            $equipId,
            "id"
        );
    }


    public function getPEById($equipId){
        return $this->data->getById(
            $this->patroller,
            $equipId,
            "id"
        );
    }

    public function getManagementById($managementId){
        return $this->data->getById(
            $this->management,
            $managementId,
            "id"
        );
    }

    public function getList() {
        $data = $this->data->getData(
            $this->table,
            array(),
            "name ASC"
        );
        $equipment = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $equipment[$item['id']] = $item;
            }
        }
        return $equipment;
    }

    public function getPEList() {
        $data = $this->data->getData(
            $this->patroller,
            array(),
            "name ASC"
        );
        $equipment = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $equipment[$item['id']] = $item;
            }
        }
        return $equipment;
    }

    public function add($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function addPE($aData) {
        return $this->data->addData($this->patroller, $aData);
    }

    public function addManagement($aData) {
        $recordId =  $this->data->addData($this->management, $aData);
        $newRecord = $this->getManagementById($recordId);
        $this->Logger->log($this->management, "add", null, $newRecord);
        return $recordId;
    }

    public function getManagement($dateFrom, $dateTo, $equipmentId = null, $return = null) {

        $SQL = "SELECT
                *
            FROM
                ".$this->management."
            WHERE
                date >= '".$dateFrom."'
            AND
                date <= '".$dateTo."' ";
        if ($equipmentId) {
            $SQL .= " AND equipment_id = '".$equipmentId."'
            ";
        }
        if ($return == '1' || $return == '0') {
            $SQL .= "  AND `return` = '".$return."' ";
        }
        $SQL .= " ORDER BY date ASC ";
        $result = $this->data->sql_query($SQL);
        for ($report = array(); $row = mysqli_fetch_assoc($result); $report[] = $row);

        return $report;

    }

    public function updateById($equipId, $aData) {
        $this->data->updateById(
            $this->table,
            $equipId,
            $aData,
            'id'
        );
    }

    public function updateManagementById($recId, $aData) {
        $this->data->updateById(
            $this->management,
            $recId,
            $aData,
            'id'
        );
    }

    public function updatePEById($equipId, $aData) {
        $this->data->updateById(
            $this->patroller,
            $equipId,
            $aData,
            'id'
        );
    }

    public function deleteById($equipId) {
        $this->data->deleteById($this->table,
            $equipId,
            "id"
            );
    }

    public function deleteManagementById($recordId) {
        $this->data->deleteById($this->management,
            $recordId,
            "id"
            );
    }

    public function deletePEById($equipId) {
        $this->data->deleteById($this->patroller,
            $equipId,
            "id"
        );
    }

    public function getConsumableReport($dateFrom, $dateTo, $type = null) {
        $SQL = "
            SELECT
                COUNT(incident_equipment.id),
                incident_equipment.equipment_id
            FROM
                incident_equipment
            LEFT JOIN
                incident
            ON
                incident_equipment.incident_id = incident.id
            LEFT JOIN
                equipment
            ON
                incident_equipment.equipment_id = equipment.id
            WHERE
                incident.incident_date >= '".$dateFrom."'
            AND
                incident.incident_date <= '".$dateTo."'
        ";
        if ($type != "all") {
            $SQL .= "
                AND equipment.consumable = ".$type."
            ";
        }
        $SQL .= "GROUP BY incident_equipment.equipment_id";
        $result = $this->data->sql_query($SQL);
        for ($report = array(); $row = mysqli_fetch_assoc($result); $report[$row['equipment_id']] = $row['COUNT(incident_equipment.id)']);
        return $report;
    }

}