<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:07
 */

class Injury {

    private $table = "injures";
    private $tableLocation = "injury_location";
    private $tableCategories = "injury_categories";
    private $tableTypes = "injury_types";

    public function __construct()
    {
        $this->data = new Db();
    }

    public function getById($injuryId){
        return $this->data->getById(
            $this->table,
            $injuryId,
            "id"
        );
    }

    public function getLocationById($locationId){
        return $this->data->getById(
            $this->tableLocation,
            $locationId,
            "id"
        );
    }

    public function getCategoryById($categoryId){
        return $this->data->getById(
            $this->tableCategories,
            $categoryId,
            "id"
        );
    }

    public function getTypeById($typeId){
        return $this->data->getById(
            $this->tableTypes,
            $typeId,
            "id"
        );
    }

    public function getList() {
        return $this->data->getData(
            $this->table,
            array(),
            "name ASC"
        );
    }

    public function getLocationList() {
        $data = $this->data->getData(
            $this->tableLocation,
            array(),
            "name ASC"
        );
        $locations = array();
        if (count($data) > 0) {
            foreach ($data as $location) {
                $locations[$location['id']] = $location;
            }
        }
        return $locations;
    }

    public function getCategoriesList() {
        $data = $this->data->getData(
            $this->tableCategories,
            array(),
            "name ASC"
        );
        $categories = array();
        if (count($data) > 0) {
            foreach ($data as $category) {
                $categories[$category['id']] = $category;
            }
        }
        return $categories;
    }

    public function getTypesList($categoryId = null) {
        $where = array();
        if ($categoryId) {
            $where['category_id'] = $categoryId;
        }
        $data = $this->data->getData(
            $this->tableTypes,
            $where,
            "name ASC"
        );
        $types = array();
        if (count($data) > 0) {
            foreach ($data as $type) {
                $types[$type['id']] = $type;
            }
        }
        return $types;
    }

    public function add($aData) {
        return $this->data->addData($this->table, $aData);
    }

    public function addLocation($aData) {
        return $this->data->addData($this->tableLocation, $aData);
    }

    public function addCategory($aData) {
        return $this->data->addData($this->tableCategories, $aData);
    }

    public function addType($aData) {
        return $this->data->addData($this->tableTypes, $aData);
    }

    public function updateById($roleId, $aData) {
        $this->data->updateById(
            $this->table,
            $roleId,
            $aData,
            'id'
        );
    }

    public function updateLocationById($locationId, $aData) {
        $this->data->updateById(
            $this->tableLocation,
            $locationId,
            $aData,
            'id'
        );
    }

    public function updateCategoryById($categoryId, $aData) {
        $this->data->updateById(
            $this->tableCategories,
            $categoryId,
            $aData,
            'id'
        );
    }

    public function updateTypeById($typeId, $aData) {
        $this->data->updateById(
            $this->tableTypes,
            $typeId,
            $aData,
            'id'
        );
    }

    public function deleteById($roleId) {
        $this->data->deleteById($this->table,
            $roleId,
            "id"
            );
    }

    public function deleteLocationById($locationId) {
        $this->data->deleteById($this->tableLocation,
            $locationId,
            "id"
        );
    }

    public function deleteCategoryById($categoryId) {
        $this->data->deleteById($this->tableCategories,
            $categoryId,
            "id"
        );
    }

    public function deleteTypeById($typeId) {
        $this->data->deleteById($this->tableTypes,
            $typeId,
            "id"
        );
    }

}