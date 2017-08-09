<?php

    class Model
    {
        private $db = null;

        /**
         * @param object $db A PDO database connection
         */
        function __construct()
        {
            try {
                $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
                $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $options);


            } catch (PDOException $e) {
                exit('Database connection could not be established.');
            }
        }

    }
