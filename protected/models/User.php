<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Andrey
 */
class User extends CActiveRecord {

    public $id;
    public $username;
    public $password;
    public $email;
    public $jabber;
    //0-undefind, 1-user, 2-admin
    public $role = 0;
    //0-ждет подтверждения регистрации, 1-зарегистрирован, 2-заблокирован
    public $status = 0;
    //0-Не работаю, 1- Работаю, 2-Отошел, 3-свободен
    public $activity;
    public $activity_time;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'user';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return array(
            // логин, пароль не должны быть больше 128-и символов
            array('email, jabber', 'length', 'max' => 128),
            array('username, password', 'required'),
        );
    }

    public function safeAttributes()
    {
        return array('username', 'password', 'jabber', 'email');
    }

    public function attributeLabels()
    {
        return array(
            'username' => 'Логин',
            'password' => 'Пароль',
            'jabber' => 'жабер',
            'email' => 'email',
        );
    }

    public static function getStatus($code)
    {
        $statuses = array(
            '0' => 'Ожидает',
            '1' => 'Зарегистрирован',
            '2' => 'Заблокирован',
        );
        return $statuses[$code];
    }

    public static function getRole($code)
    {
        $roles = array(
            '0' => '',
            '1' => 'Пользователь',
            '2' => 'Администратор'
        );
        return $roles[$code];
    }

    public static function getUser()
    {
        $activity = array();
        $user = Yii::app()->db->createCommand()
                ->select('*')
                ->from('user')
                ->where('id=' . Yii::app()->user->id)
                ->queryRow();
        $activity['code'] = $user['activity'];
        $activity['value'] = User::getActivity($user['activity']);
        return $activity;
    }

    public static function getActivity($code = NULL)
    {
        $activities = array(
            '1' => 'Работаю',
            '2' => 'Отошел',
            '3' => 'Свободен',
            '4' => 'Не работаю',
        );
        if ($code == 0) {
            return 'Не работаю';
        }
        if ($code == NULL) {
            return $activities;
        }
        return $activities[$code];
    }

    public static function valReg($user, $check = 0)
    {
        if ($check == 0) {
            if ($user->username == '' || $user->email == '' || $user->password == '' || $user->jabber == '') {
                return 'Все поля должны быть заполнены';
            }
            if (!Sistem::val()) {
                return 'Не верный системный ключ';
            }
        }
        if (strlen($user->username) < 3 || strlen($user->username) > 20) {
            return 'Логин должен быть от 3 до 20 символов';
        }
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            return 'Не корректный email';
        }
        if (!filter_var($user->jabber, FILTER_VALIDATE_EMAIL)) {
            return 'Не корректный jabber';
        }

        return FALSE;
    }

    public static function haveTask()
    {
        Yii::app()->user->id;
    }

    public static function closeTask($id)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'user_id = ' . $id . ' and end_time = 0';
        $tasks = TaskTime::model()->findAll($criteria);
        if (!empty($tasks)) {
            foreach ($tasks as $task) {
                $task->end_time = time();
                $task->total = number_format(($task->end_time - $task->start_time) / 3600, 2);
                $task->save();
            }
        }
        return TRUE;
    }

    public static function getInfo()
    {
        $infos = array(
            '0' => 'Пароль успешно изменен',
            '1' => 'Данный пользователь назначен администратором',
            '2' => 'Статус пользователя изменен',
            '3' => 'Права администратора сняты',
        );
        if (isset($_GET['info']) && isset($infos[$_GET['info']])) {
            return $infos[$_GET['info']];
        }
        return FALSE;
    }

    public static function statActivity($user, $act)
    {
        if ($user->activity != 1 && $act == 1) {
            $work = new WorkTime();
            $work->user_id = Yii::app()->user->id;
            $work->date_mark = date("m-Y", time());
            $work->start_time = time();
            $work->save();
        }
        if ($user->activity == 1 && $act != 1) {
            $criteria = new CDbCriteria;
            $criteria->condition = 'user_id = ' . Yii::app()->user->id . ' and end_time = 0';
            $work = WorkTime::model()->find($criteria);
            $work->end_time = time();
            $work->total = number_format(($work->end_time - $work->start_time) / 3600, 2);
            $work->save();
        }
    }

    public static function workTime($id)
    {
        return Yii::app()->db->createCommand('SELECT date_mark, SUM(total) as time  '
                                . 'FROM work_time '
                                . 'WHERE user_id = ' . $id
                                . ' GROUP BY date_mark '
                                . 'ORDER BY date_mark DESC')
                        ->queryAll();
    }

    public static function isAdmin()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'id = ' . Yii::app()->user->id;
        $work = User::model()->find($criteria);
        if ($work->role == 2) {
            return TRUE;
        }
        return FALSE;
    }

}
