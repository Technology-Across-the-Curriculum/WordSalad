<?php

    /**
     * Created by PhpStorm.
     * User: Nathan
     * Date: 9/21/2015
     * Time: 1:20 PM
     */
    class TestController extends Controller{

        function __construct(){
            require APP . 'model/ResourceModel.php';
            $this->css = new CssModel();
            $this->js = new JsModel();

            require APP . 'model/TestModel.php';
            $this->model = new TestModel();

            $this->location = "Testing";
        }

        public function index(){
            $resultTest = $this->model->GetAllTest();
            $resultCurrent = $this->model->GetCurrent();
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/navigation.php';
            require APP . 'view/test/index.php';
            require APP . 'view/_templates/footer.php';

        }

        public function gibberishIndex(){
            $resultTest = $this->model->GetAllTest();
            $resultCurrent = $this->model->GetCurrent();
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/navigation.php';
            require APP . 'view/test/gibberishIndex.php';
            require APP . 'view/_templates/footer.php';

        }

        public function gibberishTest(){
            $this->model->testGibberish();
            header('location: ' . URL . 'TestController/index');
        }

        public function gibberishDetails($test_id){
            $resultTest = $this->model->GetTestInfo($test_id);
            $resultScores = $this->model->GetTestScore($test_id);
            $resultText = $this->model->GetGibberishData();
            $resultDetails = $this->model->GetTestDetails($test_id);
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/navigation.php';
            require APP . 'view/test/testdetails.php';
            require APP . 'view/_templates/footer.php';
        }

        public function controlTest(){
            $this->model->testControl();
            header('location: ' . URL . 'TestController/index');
        }

        public function controlDetails($test_id){
            $resultTest = $this->model->GetTestInfo($test_id);
            $resultScores = $this->model->GetTestScore($test_id);
            $resultText = $this->model->GetControlData();
            $resultDetails = $this->model->GetTestDetails($test_id);
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/navigation.php';
            require APP . 'view/test/testdetails.php';
            require APP . 'view/_templates/footer.php';
        }

        public function falltermTest(){
            $this->model->testFall();
            header('location: '. URL . 'TestController/index');
        }
        public function fallDetails($test_id){
            $resultTest = $this->model->GetTestInfo($test_id);
            $resultScores = $this->model->GetTestScore($test_id);
            $resultText = $this->model->GetFallData();
            $resultDetails = $this->model->GetTestDetails($test_id);
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/navigation.php';
            require APP . 'view/test/testdetails.php';
            require APP . 'view/_templates/footer.php';
        }


        public function deleteTest($test_id)
        {
            $this->model->DeleteTest($test_id);
            header('location: ' . URL . 'TestController/index');
        }
    }