<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 18.02.2015
 * Time: 7:10
 */

class Db {

public function __construct() {
    $this->link = mysqli_connect('localhost', 'mikeagen_bbsp', 'YMynD{F1N#Z', 'mikeagen_mtbawbaw');
    $SQL = "SET NAMES utf8";
    mysqli_query($this->link, $SQL);
}

    public function sql_query($query) {
        if ($query !== "") {
            $result = mysqli_query($this->link, $query);
        }
        return $result;
    }

    public function addData($table, $data) {

        $SQL = "INSERT INTO ".$table;

        $fields = array();
        $values = array();
        foreach ($data as $field => $value) {
            $fields[] = $field;
            $values[] = "'".$value."'";
        }

        $SQL .= "(`".implode("`, `", $fields)."`) VALUES (".implode(", ",$values).")";

        $result = $this->sql_query($SQL);
        return mysqli_insert_id($this->link);

    }

    public function updateById($table, $id, $values = Array(), $idField = "") {
        if (is_array($values) && count($values) > 0) {
            $SQL = "UPDATE ".$table." SET ";
            foreach ($values as $field => $value) {
                $SQL .= $field." = '".$value."', ";
            }
            if ($idField == "") {
                $SQL  .= " id = ".$id." WHERE id = ".$id;
            }
            else {
                $SQL  .= $idField." = ".$id." WHERE ".$idField." = ".$id;
            }

            $this->sql_query($SQL);
        }
        else {
            exit('nothing to change');
        }
    }

    public function getById($table, $id, $idField = '') {
        $SQL = "SELECT * FROM ".$table." WHERE ";
        if ($idField != "") {
            $SQL .= $idField;
        }
        else {
            $SQL .= "id";
        }
        $SQL .= " = '".$id."'";
        $result = $this->sql_query($SQL);
        if (mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result);
        }
        else {
            $data = array();
        }
        return $data;
    }

    public function deleteById($table, $id, $idField = "id") {
        $SQL = "DELETE FROM ".$table." WHERE ".$idField." = ".$id;
        $this->sql_query($SQL);
    }

    public function getData($table, $aWhere = array(), $order = "") {
        $SQL = "SELECT * FROM ".$table;

        if (is_array($aWhere) && count($aWhere) > 0) {
            $SQL .= " WHERE ";

            $where = array();
            foreach ($aWhere as $field => $value) {
                $where[] = "`".$field."` = '".$value."' ";
            }

            $SQL .= implode(" AND ", $where);
        }

        if ($order != "") {
            $SQL .= " ORDER BY ".$order;
        }
        $result = $this->sql_query($SQL);
        for ($data = array(); $row = mysqli_fetch_assoc($result); $data[] = $row);
        
        return $data;
    }

}