<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Reboot
 *
 * @author Andrey
 */
class Reboot extends CActiveRecord {

    public $id;
    public $ip;
    public $st1;
    public $st2;
    public $date;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'reboot';
    }

    public function rules() {
        return array(
        );
    }

    public static function makeModel($str) {
        $model = new Reboot();
        $strArr = explode('_', $str);

        if (count($strArr) == 3) {
            $model->ip = $strArr[2];
            $model->st1 = $strArr[1];
            $model->st2 = $strArr[0];
            return $model;
        }

        if (count($strArr) == 2) {
            $model->ip = $strArr[1];

            if($strArr[0] == 'off') {
                $model->st1 = 'off';
                $model->st2 = 'empty';
                return $model;
            }

            if($strArr[0] == 'x') {
                
                $model->st1 = 'on';
                $model->st2 = 'x';
                return $model;
            }
        }
        return FALSE;
    }

}
