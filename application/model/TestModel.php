<?php

/**
 * Created by PhpStorm.
 * User: Nathan
 * Date: 9/21/2015
 * Time: 1:24 PM
 */
class TestModel
{

    private $db = NULL;
    private $Matrix = NULL;
    private $Current = NULL;
    private $Test = NULL;
    private $WordSaladModel = NULL;


    function __construct()
    {
        try {
            $options = array(
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
            );
            $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
        require APP . 'model/WordSaladModel.php';
        $this->WordSaladModel = new WordSaladModel();

        self::SetCurrent();
        self::SetMatrix();
    }

    private function SetCurrent()
    {
        $sql = "SELECT * FROM ws_matrix_current";
        $query = $this->db->prepare($sql);
        $query->execute();
        $this->Current = $query->fetch(PDO::FETCH_ASSOC);
    }

    private function SetMatrix()
    {
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
        $this->Matrix = $matrix;
    }

    private function SetTest()
    {
        $sql = "SELECT * FROM ws_matrix_test ORDER BY test_time DESC LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute();
        $this->Test = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function GetGibberishData()
    {
        $sql = "SELECT * FROM ws_matrix_gibberish_data";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    public function GetControlData()
    {
        $sql = "SELECT * FROM ws_matrix_control_data";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    public function GetFallData()
    {
        $sql = "SELECT * FROM ws_fall_term";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function GetAllTest()
    {
        $sql = "SELECT * FROM ws_matrix_test";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    public function GetCurrent()
    {
        return $this->Current;
    }

    public function GetTestScore($test_id)
    {
        $sql = "SELECT * FROM ws_matrix_score WHERE test_id = :test_id";
        $query = $this->db->prepare($sql);
        $parameters = array(':test_id' => $test_id);
        $query->execute($parameters);
        return $query->fetchAll();

    }

    public function GetTestInfo($test_id)
    {
        $sql = "SELECT * FROM ws_matrix_test WHERE id = :test_id";
        $query = $this->db->prepare($sql);
        $parameters = array(':test_id' => $test_id);
        $query->execute($parameters);
        return $query->fetch(PDO::FETCH_ASSOC);

    }

    public function GetTestDetails($test_id)
    {
        $sql = "SELECT count(id) as total_post FROM ws_matrix_score WHERE test_id = :test_id";
        $query = $this->db->prepare($sql);
        $parameter = array(':test_id' => $test_id);
        $query->execute($parameter);
        $totalTest = $query->fetch(PDO::FETCH_ASSOC);

        $sql = "SELECT count(is_gibberish) as total_gibberish FROM ws_matrix_score WHERE is_gibberish = TRUE AND test_id = :test_id";
        $query = $this->db->prepare($sql);
        $parameter = array(':test_id' => $test_id);
        $query->execute($parameter);
        $totalGibberish = $query->fetch(PDO::FETCH_ASSOC);

        $sql = "SELECT count(is_gibberish) as total_non_gibberish FROM ws_matrix_score WHERE is_gibberish = FALSE AND test_id = :test_id";
        $query = $this->db->prepare($sql);
        $parameter = array(':test_id' => $test_id);
        $query->execute($parameter);
        $totalNonGibberish = $query->fetch(PDO::FETCH_ASSOC);
        return array(
            'TotalTest' => $totalTest['total_post'],
            'TotalGibberish' => $totalGibberish['total_gibberish'],
            'TotalNonGibberish' => $totalNonGibberish['total_non_gibberish']
        );

    }

    public function UpdateTestDetails($test_id, $total_post, $total_gibberish, $total_non_gibberish){
        $sql = "UPDATE ws_matrix_text SET total_post = :tp, total_gibberish = :tg, total_non_gibberish = :tng WHERE id = :id";
        $query = $this->db->prepare($sql);
        $parameter = array(
            ':id' => $test_id,
            ':tp' => $total_post,
            ':tg' => $total_gibberish,
            ':tng' => $total_non_gibberish);
        $query->execute($parameter);

    }
    public function DeleteTest($test_id)
    {
        $sql = "DELETE FROM ws_matrix_test WHERE id = :id";
        $query = $this->db->prepare($sql);
        $parameter = array(
            ':id' => $test_id
        );
        $query->execute($parameter);

    }

    public function NewTest($testType)
    {
        $isControl = FALSE;
        $isGibberish = FALSE;
        $isLive = FALSE;


        $sql = "INSERT INTO ws_matrix_test (threshold,percentage,is_control, is_gibberish,is_live) VALUE (:threshold, :percent, :control, :gibberish,:live)";
        $query = $this->db->prepare($sql);

        // Sets test type based on passed parameter
        if ($testType == "Gibberish") {
            $isGibberish = TRUE;
        } elseif ($testType == "Control") {
            $isControl = TRUE;
        } elseif ($testType == "Live") {
            $isLive = TRUE;
        }

        $parameters = array(
            ':threshold' => $this->Current['threshold'],
            ':percent' => $this->Current['percentage'],
            ':control' => $isControl,
            ':gibberish' => $isGibberish,
            ':live' => $isLive
        );
        $query->execute($parameters);
        self::SetTest();

    }

    public function RecordScore($score, $wordCount, $gibberishId, $controlId, $liveId, $isGibberish, $wordPercent)
    {
        $sql = "INSERT INTO ws_matrix_score
                  (test_id,gibberish_id, control_id, is_gibberish, gibberish_score, total_word, total_english_word, unique_word, unique_english_word, average_word_length,word_percentage, live_id)                    VALUE
                  (:test_id,:gibberish_id, :control_id, :is_gibberish, :gibberish_score, :total_word, :total_english_word, :unique_word, :unique_english_word, :average_word_length, :word_percent, :live_id)";
        $query = $this->db->prepare($sql);
        $parameters = array(
            ':test_id' => $this->Test['id'],
            ':gibberish_id' => $gibberishId,
            ':control_id' => $controlId,
            ':is_gibberish' => $isGibberish,
            ':gibberish_score' => $score,
            ':total_word' => $wordCount['total_words'],
            ':total_english_word' => $wordCount['total_english_words'],
            ':unique_word' => $wordCount['unique_words'],
            ':unique_english_word' => $wordCount['unique_english_words'],
            ':average_word_length' => $wordCount['avg_word_len'],
            ':word_percent' => $wordPercent,
            ':live_id' =>$liveId

        );
        $query->execute($parameters);
    }

    public function testGibberish()
    {
        self::NewTest("Gibberish");
        $gibberish = self::GetGibberishData();
        foreach ($gibberish as $text) {

            $this->WordSaladModel->SetMatrix($this->Matrix);
            $this->WordSaladModel->SetThreshold($this->Current['threshold']);
            $this->WordSaladModel->SetPercentage($this->Current['percentage']);
            // Get Score
            $score = $this->WordSaladModel->GibberishScore($text->body_text, TRUE);
            $wordCount = $this->WordSaladModel->GetWordCount($text->body_text);

            // Calculates Unique world percentage.
            $percent = (
                ($wordCount['unique_words'] / $wordCount['total_words'])
                +
                ($wordCount['unique_english_words'] / $wordCount['total_english_words'])
            );

            // For testing on local Machine/*
            /* $percent = 0;
             $wordCount = array(
               'total_words' => 1,
               'total_english_words' => 1,
               'unique_words' => 0,
               'unique_english_words' => 0,
               'avg_word_len' => 0,
             );*/

            // Determine Gibberish
            $isGibberish = $this->WordSaladModel->DetermineGibberish($score, $percent);

            // Record Score
            self::RecordScore($score, $wordCount, $text->id, NULL,NULL, $isGibberish, $percent, NULL);

        }
        $testDetails = self::GetTestDetails($this->Test['id']);
        self::UpdateTestDetails($this->Test['id'], $testDetails['TotalTest'], $testDetails['TotalGibberish'], $testDetails['TotalNonGibberish']);
    }

    public function testControl()
    {
        self::NewTest("Control");
        $gibberish = self::GetControlData();
        foreach ($gibberish as $text) {

            $this->WordSaladModel->SetMatrix($this->Matrix);
            $this->WordSaladModel->SetThreshold($this->Current['threshold']);
            $this->WordSaladModel->SetPercentage($this->Current['percentage']);
            // Get Score
            $score = $this->WordSaladModel->GibberishScore($text->body_text, TRUE);
            $wordCount = $this->WordSaladModel->GetWordCount($text->body_text);

            // Calculates Unique world percentage.
            $percent = (($wordCount['unique_words'] / $wordCount['total_words'])
                + ($wordCount['unique_english_words'] / $wordCount['total_english_words']));

            // Determine Gibberish
            $isGibberish = $this->WordSaladModel->DetermineGibberish($score, $wordCount);

            // Record Score
            self::RecordScore($score, $wordCount, null, $text->id,NULL, $isGibberish, $percent);
        }
        $testDetails = self::GetTestDetails($this->Test['id']);
        self::UpdateTestDetails($this->Test['id'], $testDetails['TotalTest'], $testDetails['TotalGibberish'], $testDetails['TotalNonGibberish']);
    }

    public function testFall()
    {
        set_time_limit(600);
        self::NewTest("Live");

        // Get Data

        $gibberish = self::GetFallData();
        // Sets Matrix, Threshold, and Percentage for testing against.
        $this->WordSaladModel->SetMatrix($this->Matrix);
        $this->WordSaladModel->SetThreshold($this->Current['threshold']);
        $this->WordSaladModel->SetPercentage($this->Current['percentage']);

        foreach ($gibberish as $text) {


            // Get Score
            $score = $this->WordSaladModel->GibberishScore($text->body_text, TRUE);
            $wordCount = $this->WordSaladModel->GetWordCount($text->body_text);

            // Calculates Unique world percentage.
            if (!($wordCount['total_words'] == 0 || $wordCount['total_english_words'] == 0)) {
                $percent = (($wordCount['unique_words'] / $wordCount['total_words'])
                    + ($wordCount['unique_english_words'] / $wordCount['total_english_words']));
            } else {
                $percent = 0;
            }

            // Determine Gibberish
            $isGibberish = $this->WordSaladModel->DetermineGibberish($score, $wordCount);

            // Record Score
            self::RecordScore($score, $wordCount, null, null, $text->nid, $isGibberish, $percent);
        }
        $testDetails = self::GetTestDetails($this->Test['id']);
        self::UpdateTestDetails($this->Test['id'], $testDetails['TotalTest'], $testDetails['TotalGibberish'], $testDetails['TotalNonGibberish']);
    }

}