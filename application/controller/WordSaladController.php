<?php

    /**
     * Created by PhpStorm.
     * User: Nathan
     * Date: 9/21/2015
     * Time: 9:09 AM
     */
    class WordSaladController extends Controller{

        private $MatrixModel = null;
        private $BackupLogModel = null;

        function __construct(){
            require APP . 'model/ResourceModel.php';
            $this->css = new CssModel();
            $this->js = new JsModel();

            require APP . 'model/MatrixModel.php';
            $this->MatrixModel = new MatrixModel();
            require APP . 'model/BackupLogModel.php';
            $this->BackupLogModel = new BackupLogModel();
            require APP . 'model/WordSaladModel.php';
            $this->model = new WordSaladModel();
        }

        public function initializeMatrix(){
            $this->MatrixModel->InitializeMatrix();
            $this->BackupLogModel->InitializeCurrent();
            $this->BackupLogModel->InsertBackupLog();
            $this->BackupLogModel->BackupMatrix($this->MatrixModel->GetMatrix(), $this->MatrixModel->GetHeader());
            var_dump(URL);
            header('location: ' . URL . 'MatrixController');
            exit();

        }

        public function trainMatrix(){

            // TODO change so backup happens after train?
            $trainInfo = $this->model->TrainMatrix();
            $this->BackupLogModel->InsertBackupLog($trainInfo['Threshold'],$trainInfo['Percentage']);
            $this->BackupLogModel->BackupMatrix($this->MatrixModel->GetMatrix(), $this->MatrixModel->GetHeader());
            $this->MatrixModel->UpdateMatrix($trainInfo['Matrix']);
            $this->BackupLogModel->UpdateCurrent($trainInfo['Threshold'],$trainInfo['Percentage']);
            header('location: ' . URL . 'MatrixController');

        }




    }
