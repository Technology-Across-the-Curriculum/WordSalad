<?php

/**
 * Created by Nathan Healea.
 * Project: WordSalad
 * File: dashboard.php
 * Date: 2/4/16
 * Time: 2:31 PM
 */
class DashboardController extends Controller
{
    public function index(){

        /* Location information */
        $this->header = 'Dashboard';
        $this->description = 'Welcome to WordSalad service overview.';
        require APP .'view/dashboard/index.php';
    }

}