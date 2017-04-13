<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RebootController
 *
 * @author Andrey
 */
class RebootController extends Controller {

    public function actionDownload() {



        if (empty($_POST['date'])) {
            $this->render("download", array('info' => 'нет даты'));
            return;
        }
        if ($_FILES['uploadfile']['error'] != 0) {
            $this->render("download", array('info' => 'нет файла'));
            return;
        }
        if (isset($_FILES['uploadfile']) && !empty($_POST['date'])) {
            //$model = new Reboot();
            //var_dump($model);exit;
            $uploaddir = Yii::getPathOfAlias('webroot') . '/files/';
            $zipName = $_FILES['uploadfile']['name'];

//            var_dump($uploaddir . $zipName);
//            exit;

            if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploaddir . '/' . $_FILES['uploadfile']['name'])) {
                $zip = new ZipArchive;
                $res = $zip->open($uploaddir . $zipName, ZipArchive::CREATE);
                //var_dump($uploaddir);exit;
                if ($res) {
                    $zip->extractTo(Yii::getPathOfAlias('webroot') . '/files');
                    $zip->close();
                    //echo 'ok';
                } else {
                    echo 'failed';
                }
            } else {
                print "There some errors!";
            }


            $folder = str_replace(".zip", "", $zipName);


            $model = Reboot::makeModel($folder);
            $model->date = strtotime($_POST['date']);
            $model->save();


            unlink($uploaddir . $folder . '/log.log');
            rmdir($uploaddir . $folder);
            unlink($uploaddir . $zipName);
        }

        $this->render("download", array('info' => FALSE));
    }

    public function actionList() {
        $reboots=Reboot::model()->findAll();
        $this->render("list", array('reboots' => $reboots,'info' => FALSE));
    }

}
