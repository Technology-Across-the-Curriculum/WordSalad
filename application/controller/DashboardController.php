<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class DashboardController extends Controller
{

    function __construct(){
        require APP . 'model/ResourceModel.php';
        $this->css = new CssModel();
        $this->js = new JsModel();

        require APP . 'model/DashboardModel.php';
        $this->model = new DashboardModel();
        $this->location = 'Dashboard';
    }

    public function index()
    {
        $resultLog = $this->model->GetLogCount();
        $resultMatrix = $this->model->GetMatrix();
        $resultThreshold = $this->model->GetThreshold();
        
        // load views
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/navigation.php';
        require APP . 'view/dashboard/index.php';
        require APP . 'view/_templates/footer.php';
    }

    public function AjaxTestData(){
        echo  $this->model->GetTestData();

    }
    public function AjaxGetAverages(){
        echo $this->model->GetAverages();
    }
    public function debug(){
        require APP . 'view/dashboard/debug.php';

    }
}
