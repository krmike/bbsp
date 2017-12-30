<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 13.05.15
 * Time: 17:28
 */

class Logger {

    private $table = "actions_log";

    public function __construct() {
        $this->data = new Db();
    }

    public function log($record_type, $action_type, $old_data, $new_data) {

        $user_id = $_COOKIE[user_id];

        if ($action_type == "add") {
                $record_id = $new_data['id'];
            foreach ($new_data as $key => $value) {
                $this->data->addData(
                    $this->table,
                    array (
                        'user_id' => $user_id,
                        'record_type' => $record_type,
                        'record_id' => $record_id,
                        'action' => $action_type,
                        'changed_field' => $key,
                        'date_of_change' => date('Y-m-d H-i-s'),
                        'old_value' => "",
                        'new_value' => $value
                    )
                );
            }
        };
        if ($action_type == "edit") {
            foreach ($old_data as $key => $value) {
                    $record_id = $old_data['id'];
                if ($record_type == "training_patrollers") {
                    $record_id = $new_data['rec_id'];
                }
                if ($value !== $new_data[$key] ) {
                    $this->data->addData(
                        $this->table,
                        array (
                            'user_id' => $user_id,
                            'record_type' => $record_type,
                            'record_id' => $record_id,
                            'action' => $action_type,
                            'changed_field' => $key,
                            'date_of_change' => date('Y-m-d H-i-s'),
                            'old_value' => $value,
                            'new_value' => $new_data[$key]
                        )
                    );
                }
            }
        };
        if ($action_type == "delete") {
                $record_id = $old_data['id'];
            foreach ($old_data as $key => $value) {
                $this->data->addData(
                    $this->table,
                    array (
                        'user_id' => $user_id,
                        'record_type' => $record_type,
                        'record_id' => $record_id,
                        'action' => $action_type,
                        'changed_field' => $key,
                        'date_of_change' => date('Y-m-d H-i-s'),
                        'old_value' => $value,
                        'new_value' => ""
                    )
                );
            }
        };
    }

    public function get($record_type, $record_id) {
        return $this->data->getData(
            $this->table,
            array(
                "record_type" => $record_type,
                "record_id" => $record_id
            ),
            "date_of_change"
        );
    }

    public function getAll($record_type) {
        return $this->data->getData(
            $this->table,
            array(
                "record_type" => $record_type
            ),
            "date_of_change"
        );
    }


} 