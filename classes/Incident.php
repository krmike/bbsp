<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Incident {

    private $table = "incident";
    private $vitails = "incident_vitails";
    private $patrollers = "incident_patrollers";
    private $equipment = "incident_equipment";
    private $phones = "incident_phones";

    public function __construct()
    {
        $this->data = new Db();
        $this->Log = new Logger();
    }

    public function getById($incidentId){
        $SQL = "SELECT
                *
            FROM
                incident
            WHERE
                incident.id = '".$incidentId."'
        ";
        $result = $this->data->sql_query($SQL);
        $incident = mysqli_fetch_assoc($result);
        return $incident;
    }

    public function getList() {
        $data = $this->data->getData(
            $this->table,
            array(),
            "name ASC"
        );
        $dr = array();
        if (count($data) > 0) {
            foreach ($data as $item) {
                $dr[$item['id']] = $item;
            }
        }
        return $dr;
    }

    public function getPatrollerIncidents($dateFrom, $dateTo, $patrollerId) {
        $SQL = "
            SELECT
                incident_patrollers.incident_id,
                incident_patrollers.patroller_id,
                incident_patrollers.role_id,
                incident.name,
                incident.incident_date,
                incident.incident_time
            FROM
                incident_patrollers
            LEFT JOIN
                incident
            ON
                incident_patrollers.incident_id = incident.id
            WHERE
                incident.incident_date >= '".$dateFrom."'
            AND
                incident.incident_date <= '".$dateTo."'
            AND
                incident_patrollers.patroller_id = '".$patrollerId."'
        ";
        $result = $this->data->sql_query($SQL);
        for ($incidents = array(); $row = mysqli_fetch_assoc($result); $incidents[] = $row);
        return $incidents;
    }

    public function getPatrollerNCEquipment($dateFrom, $dateTo, $patrollerId) {
        $SQL = "
            SELECT
                incident_equipment.equipment_id,
                incident.id as incident_id,
                incident.incident_date,
                incident.incident_time,
                incident_patrollers.patroller_id,
                incident.name as incident_name,
                equipment.name as equipment_name
            FROM
                incident_equipment
            LEFT JOIN
                incident_patrollers
            ON
                incident_equipment.incident_id = incident_patrollers.incident_id
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
            AND
                incident_patrollers.patroller_id = '".$patrollerId."'
            AND
                equipment.consumable = 0
        ";
        $result = $this->data->sql_query($SQL);
        for ($equipment = array(); $row = mysqli_fetch_assoc($result); $equipment[] = $row);
        return $equipment;
    }

    public function getIncidentPatrollers($incidentId) {
        $SQL = "SELECT * FROM incident_patrollers WHERE incident_id = '".$incidentId."' ";
        $result = $this->data->sql_query($SQL);
        for ($patrollers = array(); $row = mysqli_fetch_assoc($result); $patrollers[] = $row);
        return $patrollers;
    }

    public function getIncidentVitals($incidentId) {
        $SQL = "SELECT * FROM incident_vitails WHERE incident_id = '".$incidentId."' ";
        $result = $this->data->sql_query($SQL);
        for ($vitals = array(); $row = mysqli_fetch_assoc($result); $vitals[] = $row);
        return $vitals;
    }

    public function getIncidentInjures($incidentId) {
        $SQL = "SELECT * FROM injures WHERE incident_id = '".$incidentId."' ";
        $result = $this->data->sql_query($SQL);
        for ($injures = array(); $row = mysqli_fetch_assoc($result); $injures[] = $row);
        return $injures;
    }

    public function getIncidentInjuryTypes($incidentId) {
        $SQL = "SELECT DISTINCT type_id FROM injures WHERE incident_id = '".$incidentId."' ";
        $result = $this->data->sql_query($SQL);
        for ($injures = array(); $row = mysqli_fetch_assoc($result); $injures[] = $row);
        return $injures;
    }

    public function getIncidentEquipment($incidentId){
        $SQL = "SELECT * FROM incident_equipment WHERE incident_id = '".$incidentId."' ";
        $result = $this->data->sql_query($SQL);
        for ($equipment = array(); $row = mysqli_fetch_assoc($result); $equipment[] = $row);
        return $equipment;
    }

    public function getIncidentLeftEquipment($incidentId){
        $SQL = "SELECT * FROM equipment_management WHERE incident_id = '".$incidentId."' ";
        $result = $this->data->sql_query($SQL);
        for ($equipment = array(); $row = mysqli_fetch_assoc($result); $equipment[] = $row);
        return $equipment;
    }

    public function getIncidentPhones($incidentId){
        $SQL = "SELECT * FROM incident_phones WHERE incident_id = '".$incidentId."' ";
        $result = $this->data->sql_query($SQL);
        for ($equipment = array(); $row = mysqli_fetch_assoc($result); $equipment[] = $row);
        return $equipment;
    }

    public function getReport($dateFrom, $dateTo, $injuryTypeId = null, $injuryLocationId = null, $activityId = null, $outcomeId = null) {
        $SQL = "SELECT
                incident.id,
                incident.name,
                incident.first_name,
                incident.last_name,
                incident.incident_date,
                incident.incident_time,
                incident.incident_weekday,
                incident.map_coordinates,
                incident.description,
                incident.activity_id,
                incident.referral_outcome_id
            FROM
                incident ";
        if ($injuryTypeId || $injuryLocationId) {
            $SQL .= "
            LEFT JOIN
                injures
            ON
                incident.id = injures.incident_id ";
        }
        $SQL .= "
            WHERE
                incident.incident_date >= '".$dateFrom."'
            AND
                incident.incident_date <= '".$dateTo."'
        ";
        if ($injuryTypeId) {
            $SQL .= " AND injures.type_id = '".$injuryTypeId."' ";
        }
        if ($injuryLocationId) {
            $SQL .= " AND injures.location_id = '".$injuryLocationId."' ";
        }
        if ($activityId) {
            $SQL .= " AND incident.activity_id = '".$activityId."' ";
        }
        if ($outcomeId) {
            $SQL .= " AND incident.referral_outcome_id = '".$outcomeId."' ";
        }
        $SQL .= " ORDER BY incident.incident_date ASC, incident.incident_time ASC ";
        $result = $this->data->sql_query($SQL);
        for ($report = array(); $row = mysqli_fetch_assoc($result); $report[] = $row);
        $res['report'] = $report;

        $SQL = "SELECT
                COUNT(*)
            FROM
                incident ";
        if ($injuryTypeId || $injuryLocationId) {
            $SQL .= "
            LEFT JOIN
                injures
            ON
                incident.id = injures.incident_id ";
        }
        $SQL .= "
            WHERE
                incident.incident_date >= '".$dateFrom."'
            AND
                incident.incident_date <= '".$dateTo."'
        ";
        if ($injuryTypeId) {
            $SQL .= " AND injures.type_id = '".$injuryTypeId."' ";
        }
        if ($injuryLocationId) {
            $SQL .= " AND injures.location_id = '".$injuryLocationId."' ";
        }
        if ($activityId) {
            $SQL .= " AND incident.activity_id = '".$activityId."' ";
        }
        if ($outcomeId) {
            $SQL .= " AND incident.referral_outcome_id = '".$outcomeId."' ";
        }
        $SQL .= " ORDER BY incident.incident_date ASC, incident.incident_time ASC ";
        $result = $this->data->sql_query($SQL);
        $count = mysqli_fetch_assoc($result);
        $count = $count['COUNT(*)'];
        $res['count'] = $count;

        return $res;

    }

    public function getEntonoxReport($dateFrom, $dateTo, $patrollerId = null) {
        $SQL = "
            SELECT
                incident.id as in_id,
                incident.name,
                incident.incident_date,
                incident.incident_time,
                incident.entonox_start_time,
                incident.entonox_start_amount,
                incident.entonox_end_amount,
                incident.witness_id,
                incident.signature_id ";
        if ($patrollerId) {
            $SQL .= ",
                incident_patrollers.patroller_id ";
        }
        $SQL .= "
            FROM
                incident ";
        if ($patrollerId) {
            $SQL .= "            LEFT JOIN
                incident_patrollers
            ON
                incident.id = incident_patrollers.incident_id ";
        }
        $SQL .= "
            WHERE
                incident.entonox = 1
            AND
                incident.incident_date >= '".$dateFrom."'
            AND
                incident.incident_date <= '".$dateTo."'
        ";
        if ($patrollerId) {
            $SQL .= " AND incident_patrollers.patroller_id = '".$patrollerId."' ";
        }
        $result = $this->data->sql_query($SQL);
        for ($incidents = array(); $row = mysqli_fetch_assoc($result); $incidents[] = $row);
        return $incidents;
    }

    public function getPenthraneReport($dateFrom, $dateTo, $patrollerId = null) {
        $SQL = "
            SELECT
                incident.id as in_id,
                incident.name,
                incident.incident_date,
                incident.incident_time,
                incident.penthrane,
                incident.signature_id ";
        if ($patrollerId) {
            $SQL .= ",
                incident_patrollers.patroller_id ";
        }
        $SQL .= "
            FROM
                incident ";
        if ($patrollerId) {
            $SQL .= "            LEFT JOIN
                incident_patrollers
            ON
                incident.id = incident_patrollers.incident_id ";
        }
        $SQL .= "
            WHERE
                incident.penthrane > 0
            AND
                incident.incident_date >= '".$dateFrom."'
            AND
                incident.incident_date <= '".$dateTo."'
        ";
        if ($patrollerId) {
            $SQL .= " AND incident_patrollers.patroller_id = '".$patrollerId."' ";
        }
        $result = $this->data->sql_query($SQL);
        for ($incidents = array(); $row = mysqli_fetch_assoc($result); $incidents[] = $row);
        return $incidents;
    }

    public function getWeekReport($dateFrom, $dateTo, $userId = null) {
        $SQL = "
            SELECT
                COUNT(incident.id),
                incident_weekday ";
        if ($userId) {
            $SQL .= ",
                incident_patrollers.patroller_id ";
        }
        $SQL .= "
            FROM
                incident ";
        if ($userId) {
            $SQL .= "
            LEFT JOIN
                incident_patrollers
            ON
                incident.id = incident_patrollers.incident_id ";
        }
        $SQL .= "
            WHERE
                incident_date >= '".$dateFrom."'
            AND
                incident_date <= '".$dateTo."' ";
        if ($userId) {
            $SQL .= " AND incident_patrollers.patroller_id = '".$userId."' ";
        }
        $SQL .= "
            GROUP BY
                incident_weekday
            ";
        $result = $this->data->sql_query($SQL);
        for ($report = array(); $row = mysqli_fetch_assoc($result); $report[$row['incident_weekday']] = $row['COUNT(incident.id)']);
        return $report;
    }

    public function getTimeReport($dateFrom, $dateTo, $timeFrom, $timeTo, $userId = null) {
        $SQL = "
            SELECT
                COUNT(incident.id) ";
        if ($userId) {
            $SQL .= ",
                incident_patrollers.patroller_id ";
        }
        $SQL .= "
            FROM
                incident ";
        if ($userId) {
            $SQL .= "
            LEFT JOIN
                incident_patrollers
            ON
                incident.id = incident_patrollers.incident_id ";
        }
        $SQL .= "
            WHERE
                incident_date >= '".$dateFrom."'
            AND
                incident_date <= '".$dateTo."'
            AND
                incident_time >= '".$timeFrom."'
            AND
                incident_time < '".$timeTo."'
            ";
        if ($userId) {
            $SQL .= " AND incident_patrollers.patroller_id = '".$userId."' ";
        }
        $result = $this->data->sql_query($SQL);
        $row = mysqli_fetch_assoc($result);
        $report = $row['COUNT(incident.id)'];
        return $report;
    }

    public function getAmbulanceModeReport($dateFrom, $dateTo, $ambulance, $userId = null) {
        $SQL = "
            SELECT
                COUNT(incident.id) ";
        if ($userId) {
            $SQL .= ",
                incident_patrollers.patroller_id ";
        }
        $SQL .= "
            FROM
                incident ";
        if ($userId) {
            $SQL .= "
            LEFT JOIN
                incident_patrollers
            ON
                incident.id = incident_patrollers.incident_id ";
        }
        $SQL .= "
            WHERE
                incident_date >= '".$dateFrom."'
            AND
                incident_date <= '".$dateTo."'
            AND
                ambulance_mode = '".$ambulance."'
            ";
        if ($userId) {
            $SQL .= " AND incident_patrollers.patroller_id = '".$userId."' ";
        }
        $result = $this->data->sql_query($SQL);
        $row = mysqli_fetch_assoc($result);
        $report = $row['COUNT(incident.id)'];
        return $report;
    }

    public function getAgeReport($dateFrom, $dateTo, $ageFrom, $ageTo = null, $userId = null) {
        $SQL = "
            SELECT
                COUNT(incident.id) ";
        if ($userId) {
            $SQL .= ",
                incident_patrollers.patroller_id ";
        }
        $SQL .= "
            FROM
                incident ";
        if ($userId) {
            $SQL .= "
            LEFT JOIN
                incident_patrollers
            ON
                incident.id = incident_patrollers.incident_id ";
        }
        $SQL .= "
            WHERE
                incident_date >= '".$dateFrom."'
            AND
                incident_date <= '".$dateTo."'
            AND
                age >= '".$ageFrom."'
            ";
        if ($ageTo) {
            $SQL .= "
            AND age < '".$ageTo."'
            ";
        }
        if ($userId) {
            $SQL .= " AND incident_patrollers.patroller_id = '".$userId."' ";
        }
        $result = $this->data->sql_query($SQL);
        $row = mysqli_fetch_assoc($result);
        $report = $row['COUNT(incident.id)'];
        return $report;
    }

    public function getInjuryCategoriesReport($dateFrom, $dateTo, $userId = null) {
        $SQL = "
            SELECT
                COUNT(injures.injury_id),
                injures.category_id ";
        if ($userId) {
            $SQL .= ",
                incident_patrollers.patroller_id ";
        }
        $SQL .= "
            FROM
                injures
            LEFT JOIN
                incident
            ON
                injures.incident_id = incident.id ";
        if ($userId) {
            $SQL .= "
            LEFT JOIN
                incident_patrollers
            ON
                incident.id = incident_patrollers.incident_id ";
        }
        $SQL .= "
            WHERE
                incident.incident_date >= '".$dateFrom."'
            AND
                incident.incident_date <= '".$dateTo."'
            AND
                injures.category_id > 0 ";
        if ($userId) {
            $SQL .= " AND incident_patrollers.patroller_id = '".$userId."' ";
        }
        $SQL .= "
            GROUP BY
                injures.category_id
        ";
        $result = $this->data->sql_query($SQL);
        for ($report = array(); $row = mysqli_fetch_assoc($result); $report[$row['category_id']] = $row['COUNT(injures.injury_id)']);
        return $report;
    }

    public function getActivityInjuresReport($dateFrom, $dateTo, $userId = null) {
        $SQL = "
            SELECT
                COUNT(injures.injury_id),
                injures.type_id,
                incident.activity_id ";
        if ($userId) {
            $SQL .= ",
                incident_patrollers.patroller_id ";
        }
        $SQL .= "
            FROM
                injures
            LEFT JOIN
                incident
            ON
                injures.incident_id = incident.id ";
        if ($userId) {
            $SQL .= "
            LEFT JOIN
                incident_patrollers
            ON
                incident.id = incident_patrollers.incident_id ";
        }
        $SQL .= "
            WHERE
                incident.incident_date >= '".$dateFrom."'
            AND
                incident.incident_date <= '".$dateTo."'
            AND
                injures.type_id > 0 ";
        if ($userId) {
            $SQL .= " AND incident_patrollers.patroller_id = '".$userId."' ";
        }
        $SQL .= "
            GROUP BY
                incident.activity_id,
                injures.type_id
        ";
        $result = $this->data->sql_query($SQL);
        for ($report = array(); $row = mysqli_fetch_assoc($result); $report[$row['activity_id']][$row['type_id']] = $row['COUNT(injures.injury_id)']);
        return $report;
    }


    public function getActivityInjuryLocationReport($dateFrom, $dateTo, $userId = null) {
        $SQL = "
            SELECT
                COUNT(injures.injury_id),
                injures.location_id,
                incident.activity_id ";
        if ($userId) {
            $SQL .= ",
                incident_patrollers.patroller_id ";
        }
        $SQL .= "
            FROM
                injures
            LEFT JOIN
                incident
            ON
                injures.incident_id = incident.id ";
        if ($userId) {
            $SQL .= "
            LEFT JOIN
                incident_patrollers
            ON
                incident.id = incident_patrollers.incident_id ";
        }
        $SQL .= "
            WHERE
                incident.incident_date >= '".$dateFrom."'
            AND
                incident.incident_date <= '".$dateTo."'
            AND
                injures.location_id > 0 ";
        if ($userId) {
            $SQL .= " AND incident_patrollers.patroller_id = '".$userId."' ";
        }
        $SQL .= "
            GROUP BY
                incident.activity_id,
                injures.location_id
        ";
        $result = $this->data->sql_query($SQL);
        for ($report = array(); $row = mysqli_fetch_assoc($result); $report[$row['activity_id']][$row['location_id']] = $row['COUNT(injures.injury_id)']);
        return $report;
    }

    public function getLocationInjuresReport($dateFrom, $dateTo, $userId = null) {
        $SQL = "
            SELECT
                COUNT(injures.injury_id),
                injures.type_id,
                injures.location_id ";
        if ($userId) {
            $SQL .= ",
                incident_patrollers.patroller_id ";
        }
        $SQL .= "
            FROM
                injures
            LEFT JOIN
                incident
            ON
                injures.incident_id = incident.id ";
        if ($userId) {
            $SQL .= "
            LEFT JOIN
                incident_patrollers
            ON
                incident.id = incident_patrollers.incident_id ";
        }
        $SQL .= "
            WHERE
                incident.incident_date >= '".$dateFrom."'
            AND
                incident.incident_date <= '".$dateTo."'
            AND
                injures.type_id > 0 ";
        if ($userId) {
            $SQL .= " AND incident_patrollers.patroller_id = '".$userId."' ";
        }
        $SQL .= "
            GROUP BY
                injures.location_id,
                injures.type_id
        ";
        $result = $this->data->sql_query($SQL);
        for ($report = array(); $row = mysqli_fetch_assoc($result); $report[$row['location_id']][$row['type_id']] = $row['COUNT(injures.injury_id)']);
        return $report;
    }

    public function getHelmetReport($dateFrom, $dateTo, $helmet, $userId = null) {
        $SQL = "
            SELECT
                COUNT(incident.id) ";
        if ($userId) {
            $SQL .= ",
                incident_patrollers.patroller_id ";
        }
        $SQL .= "
            FROM
                incident ";
        if ($userId) {
            $SQL .= "
            LEFT JOIN
                incident_patrollers
            ON
                incident.id = incident_patrollers.incident_id ";
        }
        $SQL .= "
            WHERE
                incident_date >= '".$dateFrom."'
            AND
                incident_date <= '".$dateTo."'
            AND
                helmet = '".$helmet."'
            ";
        if ($userId) {
            $SQL .= " AND incident_patrollers.patroller_id = '".$userId."' ";
        }
        $result = $this->data->sql_query($SQL);
        $row = mysqli_fetch_assoc($result);
        $report = $row['COUNT(incident.id)'];
        return $report;
    }

    public function getEditable() {
        $SQL = "SELECT * FROM incident WHERE incident_timestamp > '".date("Y-m-d H:i:s", time() - 172800) ." '";
        $result = $this->data->sql_query($SQL);
        for ($incidents = array(); $row = mysqli_fetch_assoc($result); $incidents[] = $row);
        return $incidents;
    }

    public function getFreeIncidents() {
        $SQL = "SELECT * FROM incident WHERE daily_log_id IS NULL";
        $result = $this->data->sql_query($SQL);
        $incidents = array();
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $incidents[$row['id']] = $row;
            }
        }
        return $incidents;
    }

    public function getEvidence($incidentId) {
        $SQL = "
        SELECT
            *
        FROM
            incident_evidence
        WHERE
            incident_id = '".$incidentId."'
        ";
        $result = $this->data->sql_query($SQL);
        for ($files = array(); $row = mysqli_fetch_assoc($result); $files[] = $row);
        return $files;
    }

    public function add($aData) {
        $incidentId = $this->data->addData($this->table, $aData);
        $newData = $this->getById($incidentId);
        $this->Log->log($this->table, "add", "", $newData);
        return $incidentId;
    }

    public function addVitail($aData) {
        return $this->data->addData($this->vitails, $aData);
    }

    public function addEquipment($aData) {
        return $this->data->addData($this->equipment, $aData);
    }

    public function addPatroller($aData) {
        return $this->data->addData($this->patrollers, $aData);
    }

    public function addPhone($aData) {
        return $this->data->addData($this->phones, $aData);
    }

    public function updateById($incidentId, $aData) {
        $oldData = $this->getById($incidentId);
        $this->data->updateById(
            $this->table,
            $incidentId,
            $aData,
            'id'
        );
        $newData = $this->getById($incidentId);
        $this->Log->log($this->table, "edit", $oldData, $newData);
    }

    public function deleteById($drId) {
        $this->data->deleteById($this->table,
            $drId,
            "id"
            );
    }

}