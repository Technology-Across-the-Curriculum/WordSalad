<?php

    /**
     * Created by PhpStorm.
     * User: Nathan
     * Date: 9/18/2015
     * Time: 12:19 PM
     */
    class MatrixModel {
        private $db = NULL;
        private $matrix = NULL;
        private $header = NULL;

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
            self::SetMatrix();
            self::SetHeader();
        }

        public function InitializeMatrix() {
            // Initialize Matrix
            $sql = "INSERT INTO ws_matrix(row_id,col_id, score) VALUES (:row_id, :col_id, :score)";
            $query = $this->db->prepare($sql);

            foreach ($this->header as $letter_row) {
                foreach ($this->header as $letter_col) {
                    $parameters = array(
                      ':row_id' => $letter_row->id,
                      ':col_id' => $letter_col->id,
                      ':score'  => 0
                    );
                    $query->execute($parameters);
                }
            }
            return self::SetMatrix();
        }

        private function SetMatrix() {
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
            if (isset($matrix[0][0])) {
                $this->matrix = $matrix;
                return TRUE;
            }

            return FALSE;

        }

        private function SetHeader() {
            $sql = "SELECT * FROM ws_matrix_header";
            $query = $this->db->prepare($sql);
            $query->execute();
            $this->header = $query->fetchAll();
            if (isset($this->header)) {
                return TRUE;
            }
            return FALSE;
        }

        public function GetMatrix() {
            return $this->matrix;
        }

        public function GetHeader() {
            return $this->header;
        }

        public function GetStatus() {
            $status = NULL;
            if (!isset($this->matrix)) {
                $status = "Not Initialize";
            }
            return $status;
        }

        public function UpdateMatrix($matrix) {
            // Setup for update of matrix
            $sql = "UPDATE ws_matrix SET  score = :score WHERE row_id = :row_id AND col_id = :col_id";
            $query = $this->db->prepare($sql);
            $rowCount = 0;
            $colCount = 0;

            // Updates matrix
            foreach ($matrix as $letter => $row) {
                $rowId = $this->header[$rowCount]->id;
                foreach ($row as $value) {
                    $parameters = array(
                      ':row_id' => $rowId,
                      ':col_id' => $this->header[$colCount]->id,
                      ':score'  => $value
                    );

                    $query->execute($parameters);
                    $colCount++;
                }
                $colCount = 0;
                $rowCount++;
            }
            self::SetMatrix();

        }

        public function PrintMatrix() {

            if (isset($this->matrix)) {


                echo '<table class="table table-bordered" >';
                echo '<thead>';
                echo '<tr >';
                echo '<th ></th >';
                foreach ($this->header as $letter => $row) {
                    echo '<th>' . $row->letter . '</th>';
                }
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                $rowcount = 0;
                foreach ($this->matrix as $matrix_row) {
                    echo '<tr>';
                    echo '<td>' . $this->header[$rowcount]->letter . '</td>';
                    foreach ($matrix_row as $score) {
                        echo '<td>' . number_format($score->score, 4, '.', '') . '</td>';
                    }
                    echo '</tr>';
                    $rowcount++;
                }
                echo '</tbody>';
                echo '</table>';
            }
        }

        public function PrintGivenMatrix($matrix) {
            echo '<table class="table table-bordered" >';
            echo '<thead>';
            echo '<tr >';
            echo '<th ></th >';
            foreach ($this->header as $letter => $row) {
                echo '<th>' . $row->letter . '</th>';
            }
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $rowcount = 0;
            foreach ($matrix as $matrix_row) {
                echo '<tr>';
                echo '<td>' . $this->header[$rowcount]->letter . '</td>';
                foreach ($matrix_row as $score) {
                    echo '<td>' . number_format($score->score, 4, '.', '') . '</td>';
                }
                echo '</tr>';
                $rowcount++;
            }
            echo '</tbody>';
            echo '</table>';
        }
    }


