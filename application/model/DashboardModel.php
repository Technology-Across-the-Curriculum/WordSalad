<?php

    /**
     * Created by PhpStorm.
     * User: Nathan
     * Date: 9/18/2015
     * Time: 11:05 AM
     */
    class DashboardModel {
        private $db = NULL;

        function __construct() {
            try {
                $options = array(
                  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                  PDO::ATTR_ERRMODE            => PDO::ERRMODE_WARNING
                );
                $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                exit('Database connection could not be established.');
            }
        }

        public function GetLogCount() {
            $sql = "SELECT count(id) as Log_Count FROM ws_matrix_backup_log";
            $query = $this->db->prepare($sql);
            $query->execute();
            $count = $query->fetch(PDO::FETCH_ASSOC);
            return $count['Log_Count'];
        }

        public function GetMatrix() {
            $sql = "SELECT row_id, col_id, score FROM ws_matrix WHERE row_id = :row_id ORDER BY col_id";
            $matrix = array();
            $query = $this->db->prepare($sql);
            for ($i = 0; $i < 27; $i++) {
                $parameter = array(
                  ':row_id' => $i + 1
                );
                $query->execute($parameter);
                array_push($matrix, $query->fetchAll());
            }
            return $matrix;

        }

        public function GetThreshold() {
            $sql = "SELECT threshold FROM ws_matrix_current";
            $query = $this->db->prepare($sql);
            $query->execute();
            $threshold = $query->fetch(PDO::FETCH_ASSOC);
            if ($threshold['threshold'] == FALSE) {
                $threshold = 0;
            }
            return $threshold['threshold'];
        }

        private function GetTest() {
            $sql = "SELECT * FROM ws_matrix_test";
            $query = $this->db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function UpdateTest() {
            $sql = "SELECT * FROM ws_matrix_test";
            $query = $this->db->prepare($sql);
            $query->execute();
            $allTest = $query->fetchAll();

            foreach ($allTest as $test) {
                $sql = "SELECT count(id) as total_post FROM ws_matrix_score WHERE test_id = :test_id";
                $query = $this->db->prepare($sql);
                $parameter = array(':test_id' => $test->id);
                $query->execute($parameter);
                $totalTest = $query->fetch(PDO::FETCH_ASSOC);

                $sql = "SELECT count(is_gibberish) as total_gibberish FROM ws_matrix_score WHERE is_gibberish = TRUE AND test_id = :test_id";
                $query = $this->db->prepare($sql);
                $parameter = array(':test_id' => $test->id);
                $query->execute($parameter);
                $totalGibberish = $query->fetch(PDO::FETCH_ASSOC);

                $sql = "SELECT count(is_gibberish) as total_non_gibberish FROM ws_matrix_score WHERE is_gibberish = FALSE AND test_id = :test_id";
                $query = $this->db->prepare($sql);
                $parameter = array(':test_id' => $test->id);
                $query->execute($parameter);
                $totalNonGibberish = $query->fetch(PDO::FETCH_ASSOC);

                $sql = "UPDATE ws_matrix_test SET total_post = :totalpost, total_gibberish = :totalgibberish, total_non_gibberish = :totalnongibberish WHERE id = :test_id";
                $query = $this->db->prepare($sql);
                $parameter = array(
                  ':test_id'           => $test->id,
                  ':totalpost'         => $totalTest['total_post'],
                  ':totalgibberish'    => $totalGibberish['total_gibberish'],
                  ':totalnongibberish' => $totalNonGibberish['total_non_gibberish']
                );
                $query->execute($parameter);
            }

        }

        public function GetTestData() {
            $sql = "SELECT id as test_id,test_time, total_post,  total_non_gibberish,total_gibberish FROM ws_matrix_test ORDER BY id";
            $query = $this->db->prepare($sql);
            $query->execute();
            $allTest = $query->fetchALL(PDO::FETCH_ASSOC);

            $newData = array();
            foreach( $allTest as $test)
            {
                $test['test_id'] = 'Test ' . $test['test_id'];
                array_push($newData, $test);
            }
            $json = json_encode($newData);
            return $json;
        }

        public function GetAverages() {

            $allTest = self::GetTest();

            $sql = "SELECT
                       test_id,
                       AVG (gibberish_score) AS Average_Gibberish_Score,
                       AVG(total_word) as Average_Total_Words,
                       AVG(total_english_word) as Average_English_Words,
                       AVG(unique_word) as Average_Unique_Words,
                       AVG(unique_english_word) as Average_English_Words,
                       AVG(average_word_length) as Average_Average_Word_Length
                    FROM ws_matrix_score
                    WHERE test_id = :test_id;
                    ";
            $query = $this->db->prepare($sql);
            $allAverages = array();
            foreach ($allTest as $test) {
                if (isset($test['is_gibberish'])) {

                    $parameters = array(':test_id' => $test['id']);
                    $query->execute($parameters);
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    $result[0]['test_id'] = 'Test ' . $result[0]['test_id'];
                    array_push($allAverages, $result[0]);
                }


            }
            $json = json_encode($allAverages);
            return $json;
        }
    }