<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserController
 *
 * @author Andrey
 */
class UserController extends Controller
{

    /**
     * Метод входа на сайт
     * 
     * Метод в котором мы выводим форму авторизации
     * и обрабатываем её на правильность.
     */
    public function actionIndex()
    {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
    }

    public function actionLogin()
    {
        $form = new User();
        if (!empty($_POST['User'])) {
            $form->attributes = $_POST['User'];
            $identity = new UserIdentity($form->username, $form->password);
            if ($identity->authenticate()) {
                Yii::app()->user->login($identity);
                if (Yii::app()->user->status == 2) {
                    Yii::app()->user->logout();
                    $this->render('login', array('form' => $form, 'info' => 'Аккаунт заблокирован'));
                    return;
                }
                if (Yii::app()->user->status == 0) {
                    Yii::app()->user->logout();
                    $this->render('login', array('form' => $form, 'info' => 'Регистрация не подтверждена'));
                } else {
                    if (isset($_GET['path'])) {
                        Yii::app()->request->redirect($this->createAbsoluteUrl('admin/default'));
                    }
                    Yii::app()->request->redirect($this->createUrl('site/index'));
                }
            } else {
                $this->render('login', array('form' => $form, 'info' => 'Неверный логин или пароль'));
            }
        } else {

            $this->render('login', array('form' => $form, 'info' => FALSE));
        }
    }

    /**
     * Метод выхода с сайта
     * 
     * Данный метод описывает в себе выход пользователя с сайта
     * Т.е. кнопочка "выход"
     */
    public function actionLogout()
    {
        
    }

    /**
     * Метод регистрации
     *
     * Выводим форму для регистрации пользователя и проверяем
     * данные которые придут от неё.
     */
    public function actionRegistration()
    {
        $form = new User();
        if (!empty($_POST['User'])) {
            //var_dump($_POST['key']);exit;
            $form->attributes = $_POST['User'];
            if ($info = User::valReg($form)) {
                $this->render("registration", array('form' => $form, 'info' => $info));
                return;
            }
            $form->password = md5($form->password);
            $form->save();
            $this->render("registration_ok");
        } else {

            $this->render("registration", array('form' => $form, 'info' => FALSE));
        }
    }

    public function actionSetActivity()
    {
        $user = User::model()->findByPk(Yii::app()->user->id);
        User::statActivity($user, $_POST['activity']);
        $user->activity = $_POST['activity'];
        $user->activity_time = time();
        $user->save();
        $this->redirect(Yii::app()->request->urlReferrer);
    }

    public function actionUpdate()
    {
        $id = Yii::app()->user->id;
        $user = User::model()->findByPk($id);
        if (!empty($_POST['User'])) {
            $user->username = $_POST['User']['username'];
            $user->email = $_POST['User']['email'];
            $user->jabber = $_POST['User']['jabber'];
            if ($info = User::valReg($user, 1)) {
                $this->render("update", array('user' => $user, 'info' => $info));
                return;
            }

            $user->save();
            $this->redirect(Yii::app()->request->urlReferrer);
        } else {
            $this->render("update", array('user' => $user, 'info' => FALSE));
        }
    }

    public function actionChPass()
    {
        $user = User::model()->findByPk(Yii::app()->user->id);
        if (!empty($_POST['User'])) {
            if ($_POST['User']['email'] == '' || $_POST['User']['username'] == '') {
                $this->render("change", array('user' => $user, 'info' => 'Все поля должны быть заполнены'));
            }
            if (md5($_POST['User']['username']) == $user->password) {
                $user->password = md5($_POST['User']['email']);
                $user->save();
                $this->render("change", array('user' => $user, 'info' => 'Пароль успешно изменен'));
            } else {
                $this->render("change", array('user' => $user, 'info' => 'Неверный пароль'));
            }
        } else {
            $this->render("change", array('user' => $user, 'info' => FALSE));
        }
    }

}
