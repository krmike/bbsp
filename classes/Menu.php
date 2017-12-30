<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 19:03
 */

class Menu {

    private $table = "menu_items";

    public function __construct() {
        $this->data = new Db();
        $this->access = new Access();
    }

    public function getMenu($userTypeId) {
        $menu = $this->data->getData(
            $this->table,
            array(),
            "sort ASC"
            );
        $menus = array();
        foreach ($menu as $menuItem) {
            if ($this->access->canAccess($userTypeId, $menuItem['resource_id'])) {
                $menus[] = $menuItem;
            }
        }
        return $menus;
    }

    public function getPageName($itemId) {
        $result = $this->data->getById(
            $this->table,
            $itemId,
            "item_id"
        );
        $name = $result['item_name'];
        return $name;
    }
}