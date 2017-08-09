<?php

    class CssModel {

        public function Bootstrap() {
            // Bootstrap Core CSS
            echo '<link href="' . URL . 'libs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">';
            echo '<link href="' . URL . 'css/boostrap-table.css" rel="stylesheet" type="text/css">';
        }

        public function MetisMenu() {
            // MetisMenu Css
            echo '<link href="' . URL . 'libs/metisMenu/dist/metisMenu.min.css" rel="stylesheet" type="text/css">';
        }

        public function Admin() {
            // MetisMenu Css
            echo '<link href="' . URL . 'css/sb-admin-2.css" rel="stylesheet" type="text/css">';
        }

        public function WordSalad() {
            // Custom Css
            echo '<link href="' . URL . 'css/wordsalad.css" rel="stylesheet" type="text/css">';
        }

        public function Timeline() {
            // Timeline Css
            echo '<link href="' . URL . 'css/timeline.css" rel="stylesheet" type="text/css">';
        }

        public function FontAwesome() {
            // Font Awesome Css
            echo '<link href="' . URL . 'libs/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">';
        }

        public function MorrisChart() {
            // Morris Chat Css
            echo '<link href="' . URL . 'libs/morrisjs/morris.css" rel="stylesheet" type="text/css">';
        }

    }

    class JsModel {

        public
        function Jquery() {
            // Jquery
            echo '<script src="' . URL . 'libs/jquery/dist/jquery.min.js"></script>';
        }

        public
        function Bootstrap() {
            // Bootstrap
            echo '<script src="' . URL . 'libs/bootstrap/dist/js/bootstrap.min.js"></script>';
        }

        public
        function MetisMenu() {
            // Metis Menu
            echo '<script src="' . URL . 'libs/metisMenu/dist/metisMenu.min.js"></script>';
        }

        public
        function MorrisChart() {
            // Morris Charts
            echo '<script src="' . URL . 'libs/raphael/raphael-min.js"></script>';
            echo '<script src="' . URL . 'libs/morrisjs/morris.min.js"></script>';
            /*echo '<script src="' . URL . 'js/morris-data.js"></script>';*/
        }

        public
        function Admin() {
            echo '<script src="' . URL . 'js/sb-admin-2.js"></script>';
        }

        public function Dashboard(){
            echo '<script src="' . URL . 'js/dashboard.js"></script>';
        }

        public function ChartJs(){
            echo '<script src="' . URL . 'libs/chart-js/Chart.js"></script>';
        }

    }
