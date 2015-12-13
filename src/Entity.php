<?php
/**
 * Created by PhpStorm.
 * User: TaF
 * Date: 11/12/2015
 * Time: 18:05
 */

namespace Touffik;


class Entity
{

    public function save() {
        $query = new Query();
        $query->save($this);
    }
    public function delete() {
        $query = new Query();
        $query->delete($this);
    }

    public function getProperties() {
        return get_object_vars($this);
    }
}