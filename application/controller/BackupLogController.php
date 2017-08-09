<?php

    /**
     * Created by PhpStorm.
     * User: Nathan
     * Date: 9/18/2015
     * Time: 4:36 PM
     */
    class BackupLogController extends Controller{
        function __construct(){

            require APP . 'model/ResourceModel.php';
            $this->css = new CssModel();
            $this->js = new JsModel();

            require APP . 'model/BackupLogModel.php';
            $this->model = new BackupLogModel();
            $this->location = 'Backup Log';


        }

        public function index(){
            $resultLog = $this->model->GetAllLogs();
            $resultCurrent = $this->model->GetCurrent();
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/navigation.php';
            require APP . 'view/backuplog/index.php';
            require APP . 'view/_templates/footer.php';
        }

        public function logDetails($log_id){
            require APP . 'model/MatrixModel.php';
            $MatrixModel = new MatrixModel();
            $matrix = $this->model->GetBackupMatrix($log_id);
            $log = $this->model->GetLogById($log_id);

            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/navigation.php';
            require APP . 'view/backuplog/logdetails.php';
            require APP . 'view/_templates/footer.php';
        }

        public function createBackup(){
            require APP . 'model/MatrixModel.php';
            $MatrixModel = new MatrixModel();
            $threshold = $this->model->GetCurrent();
            $this->model->InsertBackupLog($threshold['threshold']);
            $this->model->BackupMatrix($MatrixModel->GetMatrix(), $MatrixModel->GetHeader());
            header('location: ' . URL . 'BackupLogController/index');
        }

    }