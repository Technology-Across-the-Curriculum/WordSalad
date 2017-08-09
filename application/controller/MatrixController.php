<?php

    /**
     * Created by PhpStorm.
     * User: Nathan
     * Date: 9/18/2015
     * Time: 12:00 PM
     */
    class MatrixController extends Controller {
        function __construct() {
            require APP . 'model/ResourceModel.php';
            $this->css = new CssModel();
            $this->js = new JsModel();

            require APP . 'model/MatrixModel.php';
            $this->model = new MatrixModel();
            $this->location = 'Matrix';
        }

        public function index() {
            $resultStatus = $this->model->GetStatus();
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/navigation.php';
            require APP . 'view/matrix/index.php';
            require APP . 'view/_templates/footer.php';
        }

    }