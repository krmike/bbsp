<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 11.05.2015
 * Time: 10:16
 */

class Access {

    private $table = "permissions";

    public function __construct() {
        $this->data = new Db();
    }

    public function getResources() {
        return $this->data->getData(
            "resources",
            array(),
            'resource_name'
        );
    }

    public function getPermissions() {
        $SQL = "SELECT * FROM ".$this->table;
        $result = $this->data->sql_query($SQL);

        $permissions = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $permissions[$row['resource_id']][$row['user_type_id']] = $row['access'];
        }

        return $permissions;
    }

    public function savePermissions($aPermissions) {
        $SQL = "TRUNCATE TABLE ".$this->table;
        $this->data->sql_query($SQL);

        foreach ($aPermissions as $resourceId => $userData) {
            foreach ($userData as $userTypeId=>$value) {
                $permissionData = array(
                    'resource_id' => $resourceId,
                    'user_type_id'  => $userTypeId,
                    'access' => 1
                );

                $this->data->addData(
                    $this->table,
                    $permissionData
                );
            }
        }
    }

    public function checkPageAccess($userTypeId, $resourceId) {
        $SQL = "SELECT * FROM ".$this->table." WHERE resource_id = ".$resourceId." AND user_type_id = ".$userTypeId." AND access = 1 ";
        $result = $this->data->sql_query($SQL);
        if( mysqli_num_rows($result) > 0) {
            return true;
        } else {
            $this->page404();
        }
    }

    public function canAccess($userTypeId, $resourceId) {
        $SQL = "SELECT * FROM ".$this->table." WHERE resource_id = ".$resourceId." AND user_type_id = ".$userTypeId." AND access = 1 ";
        $result = $this->data->sql_query($SQL);
        if( mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function page404() {
        header("location:/404.php");
    }

}