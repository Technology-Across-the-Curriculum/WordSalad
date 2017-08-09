<?php

    /**
     * Created by PhpStorm.
     * User: Nathan
     * Date: 9/29/2015
     * Time: 5:04 PM
     */
    class ServiceController extends Controller {
        private $database = null;
        private $MatrixModel = null;
        private $BackupModel = null;



        public function locate() {
            echo "You care calling the WordSalad Service";
        }

        public function ip(){
            echo '<h4>Server Address</h4>';
            echo $_SERVER['SERVER_ADDR'];
            echo '<h4>User Ip Address</h4>';
            echo $_SERVER['REMOTE_ADDR'];
            echo '<h4>Host Name</h4>';
            echo gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $this->model->StoreUserIpAddress(gethostbyaddr($_SERVER['REMOTE_ADDR']),$_SERVER['SERVER_ADDR'],$_SERVER['REMOTE_ADDR']);
        }

        public function devDetection($nodeId){
            require APP . 'model/ServiceModel.php';
            $server = 'dev';


            $this->model = new ServiceModel($server);

            echo $this->model->WordSaladDetection($nodeId,$server);
        }

        public function prodDetection($nodeId){
            require APP . 'model/ServiceModel.php';
            $server = 'prod';
            $this->model = new ServiceModel($server);
            echo $this->model->WordSaladDetection($nodeId,$server);
        }

        public function stageDetection($nodeId){
            require APP . 'model/ServiceModel.php';
            $server = 'stage';
            $this->model = new ServiceModel($server);
            echo $this->model->WordSaladDetection($nodeId,$server);
        }

        public function textDetection($text){

            /*$node = json_decode($_POST);
            echo $node;*/
            require APP . 'model/ServiceModel.php';
            $server = 'dev';
            $this->model = new ServiceModel($server);
            echo $this->model->TextOnlyDetection(json_decode($text));
        }
    }

    //{"node_id":"200","is_wordsalad":1,"percent":2,"score":0.073410901053123}