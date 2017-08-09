<?php

    /**
     * Created by PhpStorm.
     * User: Nathan
     * Date: 9/18/2015
     * Time: 3:55 PM
     */
    class BackupLogModel {
        private $db = NULL;
        private $current = NULL;

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
            self::SetCurrentLog();
        }

        public function InitializeCurrent() {
            $sql = "INSERT INTO ws_matrix_current(id,threshold,percentage) VALUE (1,0,0)";
            $query = $this->db->prepare($sql);
            $query->execute();
            self::SetCurrentLog();
        }

        private function SetCurrentLog() {
            $sql = "SELECT * FROM ws_matrix_current";
            $query = $this->db->prepare($sql);
            $query->execute();
            $this->current = $query->fetch(PDO::FETCH_ASSOC);
            if (isset($this->current['id'])) {
                return TRUE;
            }
            return FALSE;
        }

        public function GetCurrent() {
            return $this->current;
        }

        public function GetAllLogs() {
            $sql = "SELECT * FROM ws_matrix_backup_log ORDER BY id DESC";
            $query = $this->db->prepare($sql);
            $query->execute();

            return $query->fetchAll();

        }

        public function GetLogById($log_id) {
            $sql = "SELECT * FROM ws_matrix_backup_log WHERE id = :log_id";
            $query = $this->db->prepare($sql);
            $parameter = array(
              'log_id' => $log_id,
            );
            $query->execute($parameter);
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        public function GetLastBackup() {
            $sql = "SELECT * FROM ws_matrix_backup_log ORDER BY backup_time DESC LIMIT 1";
            $query = $this->db->prepare($sql);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        public function GetBackupMatrix($log_id) {
            $sql = "SELECT id FROM ws_matrix_header";
            $query = $this->db->prepare($sql);
            $query->execute();
            $header = $query->fetchAll();

            $sql = "SELECT row_id, col_id, score FROM ws_matrix_backup WHERE row_id = :row_id AND backup_id = :backup_id ORDER BY col_id";
            $matrix = array();
            $query = $this->db->prepare($sql);
            foreach ($header as $letter) {
                $parameter = array(
                  'backup_id' => $log_id,
                  ':row_id'   => $letter->id
                );
                $query->execute($parameter);
                array_push($matrix, $query->fetchAll());
            }
            if (isset($matrix[0])) {
                return $matrix;
            }
            else {
                return NULL;
            }
        }

        public function InsertBackupLog($newThreshold = NULL, $newPercentage = NULL) {
            if (isset($newThreshold)) {
                $threshold = $newThreshold;
            }
            elseif (isset($this->current['id'])) {
                $threshold = $this->current['threshold'];
            }
            else {
                $threshold = 0;
            }

            if (isset($newPercentage)) {
                $percentage = $newPercentage;
            }
            elseif (isset($this->current['id'])) {
                $percentage = $this->current['percentage'];
            }
            else {
                $percentage = 0;
            }

            $sql = "INSERT INTO ws_matrix_backup_log(threshold, percentage) VALUES (:threshold, :percent)";
            $query = $this->db->prepare($sql);
            $parameters = array(
              ':threshold' => $threshold,
              ':percent' =>$percentage
            );
            $query->execute($parameters);
            return TRUE;
        }

        public function UpdateCurrent($newThreshold, $newPercentage) {
            $sql = "UPDATE ws_matrix_current SET threshold = :new_threshold, percentage = :percent WHERE id = 1";
            $query = $this->db->prepare($sql);
            $parameter = array(
              ':new_threshold' => $newThreshold,
              ':percent'       => $newPercentage

            );
            $query->execute($parameter);
        }

        public function BackupMatrix($matrix = NULL, $header = NULL) {
            $lastLog = self::GetLastBackup();
            if (isset($matrix) && isset($header)) {

                // Prepares SQL
                $sql = "INSERT INTO ws_matrix_backup(backup_id,row_id,col_id, score) VALUES (:backup_id, :row_id, :col_id, :score)";
                $query = $this->db->prepare($sql);

                // Prepares Index counts for row and count
                $rowCount = 0;
                $colCount = 0;

                // Creates backup of current matrix
                foreach ($matrix as $letter => $row) {
                    $rowId = $header[$rowCount]->id;
                    foreach ($row as $value) {
                        $parameters = array(
                          ':backup_id' => $lastLog['id'],
                          ':row_id'    => $rowId,
                          ':col_id'    => $header[$colCount]->id,
                          ':score'     => $value->score
                        );
                        $query->execute($parameters);
                        $colCount++;
                    }
                    $colCount = 0;
                    $rowCount++;
                }
                return TRUE;
            }
            return FALSE;
        }

    }