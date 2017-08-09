<?php

/**
 * Created by PhpStorm.
 * User: Nathan
 * Date: 9/30/2015
 * Time: 8:06 AM
 */
class ServiceModel
{

    private $db = NULL;
    private $livedb = NULL;
    private $WordSalad = NULL;
    private $Matrix = NULL;
    private $Current = NULL;

    function __construct($connection)
    {
        try {
            $options = array(
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
            );
            // Create connection to dev server
            if ($connection == 'dev' || $connection = 'prod' || $connection = 'stage') {
                $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $options);
            }

            // Create connection to production server
            if ($connection == 'prod') {
                $this->livedb = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME_LIVE . ';charset=' . DB_CHARSET, DB_USER_LIVE, DB_PASS_LIVE, $options);
            }

            // Create connection to production server
            if ($connection == 'stage') {
                $this->livedb = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME_STAGE . ';charset=' . DB_CHARSET, DB_USER_STAGE, DB_PASS_STAGE, $options);
            }
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
        require APP . 'model/WordSaladModel.php';
        $this->WordSalad = new WordSaladModel();

        require APP . 'model/MatrixModel.php';
        $this->Matrix = new MatrixModel();

        self::SetCurrent();
    }

    /**
     * Set Current to the current matrix threshold and word percentage
     */
    private function SetCurrent()
    {
        $sql = "SELECT * FROM ws_matrix_current";
        $query = $this->db->prepare($sql);
        $query->execute();
        $this->Current = $query->fetch(PDO::FETCH_ASSOC);
    }

    private function GetDevNodeText($nodeId)
    {

        $sql = "SELECT body_value  FROM w365dev_field_data_body WHERE entity_id = :nodeId";
        $query = $this->db->prepare($sql);
        $parameters = array(':nodeId' => $nodeId);
        $query->execute($parameters);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    private function GetProdLiveNodeText($nodeId)
    {
        $sql = "SELECT body_value  FROM w365prod_field_data_body WHERE entity_id = :nodeId";
        $query = $this->livedb->prepare($sql);
        $parameters = array(':nodeId' => $nodeId);
        $query->execute($parameters);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function StoreUserIpAddress($host, $server, $user)
    {
        $sql = "INSERT INTO ws_ip (host_name, server_address, user_address) VALUES (:host, :server, :userip)";
        $query = $this->db->prepare($sql);
        $server = ip2long($server);
        $user = ip2long($user);
        $parameter = array(':host' => $host, ':server' => $server, ':userip' => $user);
        $query->execute($parameter);


    }

    /*public function GibberishDetection($nodeId)
    {

        // Gets the node text
        $nodeText = self::GetNodeText($nodeId);

        // TODO add check to make sure I received data from database.

        // Sets data to determine gibberish
        $this->WordSalad->SetMatrix($this->Matrix->GetMatrix());
        $this->WordSalad->SetThreshold($this->Current['threshold']);
        $this->WordSalad->SetPercentage($this->Current['percentage']);


        // Get Score
        $score = $this->WordSalad->GibberishScore($nodeText['body_value'], TRUE);
        $wordCount = $this->WordSalad->GetWordCount($nodeText['body_value']);

        // Calculates Unique world percentage.
        $percent = (
            ($wordCount['unique_words'] / $wordCount['total_words'])
            +
            ($wordCount['unique_english_words'] / $wordCount['total_english_words'])
        );


        // Determine Gibberish
        $isGibberish = $this->WordSalad->DetermineGibberish($score, $percent);
        $results = array('node_id' => $nodeId,
            'node_body_text' => $nodeText['body_value'],
            'is_gibberish' => $isGibberish,
            'percent' => $percent,
            'score' => $score
        );
        return json_encode($results);

    }*/

    public function WordSaladDetection($nodeId, $server)
    {
        $nodeText = null;
        $results = array();

        // Error handling for null nodeId
        if (empty($nodeId)) {
            $results['error'] = "true";
            $results['message'] = "NodeId was null";
            return json_encode($results);

        }

        if ($server == 'dev') {
            // Gets the node text form dev server
            $nodeText = self::GetDevNodeText($nodeId);
        } else if ($server == 'prod') {
            // Gets the node text form live server
            $nodeText = self::GetProdLiveNodeText($nodeId);
        }


        // Error handling for empty node
        if (($nodeText == false) || empty($nodeText['body_value'])) {
            $results['error'] = "true";
            $results['message'] = "Node text body was not retrieved";
            return json_encode($results);

        }


        // TODO add check to make sure I received data from database.

        // Sets data to determine gibberish
        $this->WordSalad->SetMatrix($this->Matrix->GetMatrix());
        $this->WordSalad->SetThreshold($this->Current['threshold']);
        $this->WordSalad->SetPercentage($this->Current['percentage']);


        // Get Score
        $score = $this->WordSalad->GibberishScore($nodeText['body_value'], TRUE);
        $wordCount = $this->WordSalad->GetWordCount($nodeText['body_value']);

        // Calculates Unique world percentage.
        if (($wordCount['total_words'] == 0) || ($wordCount['total_english_words'] == 0)) {
            $percent = 0;
        } else {
            $percent = (
                ($wordCount['unique_words'] / $wordCount['total_words'])
                +
                ($wordCount['unique_english_words'] / $wordCount['total_english_words'])
            );
        }


        // Determine Gibberish
        $isGibberish = $this->WordSalad->DetermineGibberish($score, $percent);
        $results = array('node_id' => $nodeId,
            /*'node_body_text' => $nodeText['body_value'],*/
            'is_wordsalad' => $isGibberish,
            'percent' => $percent,
            'score' => $score
        );
        return json_encode($results);

    }

    public function TextOnlyDetection($text)
    {

        // TODO add check to make sure I received data from database.

        // Sets data to determine gibberish
        $this->WordSalad->SetMatrix($this->Matrix->GetMatrix());
        $this->WordSalad->SetThreshold($this->Current['threshold']);
        $this->WordSalad->SetPercentage($this->Current['percentage']);

        // Get Score
        $score = $this->WordSalad->GibberishScore($text, TRUE);
        $wordCount = $this->WordSalad->GetWordCount($text);

        // Calculates Unique world percentage.

        if (($wordCount['total_words'] == 0) || ($wordCount['total_english_words'] == 0)) {
            $percent = 0;
        } else {
            $percent = (
                ($wordCount['unique_words'] / $wordCount['total_words'])
                +
                ($wordCount['unique_english_words'] / $wordCount['total_english_words'])
            );
        }


        // Determine Gibberish
        $isGibberish = $this->WordSalad->DetermineGibberish($score, $percent);
        $results = array(
            'is_wordsalad' => $isGibberish,
            'percent' => $percent,
            'score' => $score
        );
        return json_encode($results);

    }
}