<?php

    /**
     * Created by PhpStorm.
     * User: Nathan
     * Date: 9/21/2015
     * Time: 9:49 AM
     */
    class WordSaladModel {

        private $Good = NULL;
        private $BigDirectory = NULL;
        private $matrix = NULL;
        private $threshold = NULL;
        private $precentage = NULL;
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
            $this->Good = APP . 'libs' . DIRECTORY_SEPARATOR . 'OldGibberish' . DIRECTORY_SEPARATOR . 'txt' . DIRECTORY_SEPARATOR . 'good.txt';
            $this->BigDirectory = APP . 'libs' . DIRECTORY_SEPARATOR . 'WordSalad' . DIRECTORY_SEPARATOR . 'Books' . DIRECTORY_SEPARATOR;
        }

        private static $_accepted_characters = 'abcdefghijklmnopqrstuvwxyz ';

        private static $_punctation = array(
          '.',
          '<',
          ',',
          '>',
          '/',
          '?',
          ';',
          ':',
          '"',
          '[',
          '{',
          ']',
          '}',
          '\\',
          '|',
          '=',
          '+',
          '-',
          '_',
          ')',
          '(',
          '*',
          '&',
          '^',
          '%',
          '$',
          '#',
          '@',
          '!',
          '`',
          '~'
        );

        protected static function _normalise($line) {
//          Return only the subset of chars from accepted_chars.
//          This helps keep the  model relatively small by ignoring punctuation,
//          infrequenty symbols, etc.
            return preg_replace('/[^a-z\ ]/', '', strtolower($line));
        }

        public static function _averageTransitionProbability($line, $log_prob_matrix, $train) {
//          Return the average transition prob from line through log_prob_mat.
            $log_prob = 1.0;
            $transition_ct = 0;
            $pos = array_flip(str_split(self::$_accepted_characters));
            $filtered_line = str_split(self::_normalise($line));
            $a = FALSE;
            foreach ($filtered_line as $b) {
                if ($a !== FALSE) {
                    if ($train == TRUE) {
                        $log_prob += $log_prob_matrix[$pos[$a]][$pos[$b]];
                    }
                    else {
                        $log_prob += $log_prob_matrix[$pos[$a]][$pos[$b]]->score;
                    }
                    $transition_ct += 1;
                }
                $a = $b;
            }

            # The exponentiation translates from log probs to probs.
            return exp($log_prob / max($transition_ct, 1));
        }

        public function SetMatrix($matrix) {
            $this->matrix = $matrix;
        }

        public function SetThreshold($threshold) {
            $this->threshold = $threshold;
        }

        public function SetPercentage($percent) {
            $this->precentage = $percent;
        }

        // Matrix Function
        public function TrainMatrix() {
            if (is_file($this->Good) === FALSE) {
                return FALSE;
            }

            $k = strlen(self::$_accepted_characters);
            $pos = array_flip(str_split(self::$_accepted_characters));

//          Assume we have seen 10 of each character pair.  This acts as a kind of
//          prior or smoothing factor.  This way, if we see a character transition
//          live that we've never observed in the past, we won't assume the entire
//          string has 0 probability.
            $log_prob_matrix = array();
            $range = range(0, count($pos) - 1);
            foreach ($range as $index1) {
                $array = array();
                foreach ($range as $index2) {
                    $array[$index2] = 10;
                }
                $log_prob_matrix[$index1] = $array;
            }
//          Gets book from Books directory and removes '..' and '.' from the directory scan. By Nathan Healea
            $books = array_diff(scandir($this->BigDirectory), array('..', '.'));

//          Count transitions from all the books in book, taken
//          from http://norvig.com/spell-correct.html
//          Added look for each book in Books directory. By Nahtan Healea
            foreach ($books as $book => $row) {
                $lines = file($this->BigDirectory . DIRECTORY_SEPARATOR . $row);
                foreach ($lines as $line) {
//              Return all n grams from l after normalizing
                    $filtered_line = str_split(self::_normalise($line));
                    $a = FALSE;
                    foreach ($filtered_line as $b) {
                        if ($a !== FALSE) {
                            $log_prob_matrix[$pos[$a]][$pos[$b]] += 1;
                        }
                        $a = $b;
                    }
                }
//              Moved Unset here to clear variables after each use.
                unset($lines, $filtered_line);
            }


//          Normalize the counts so that they become log probabilities.
//          We use log probabilities rather than straight probabilities to avoid
//          numeric underflow issues with long texts.
//          This contains a justification:
//          http://squarecog.wordpress.com/2009/01/10/dealing-with-underflow-in-joint-probability-calculations/
            foreach ($log_prob_matrix as $i => $row) {
                $s = (float) array_sum($row);
                foreach ($row as $k => $j) {
                    $log_prob_matrix[$i][$k] = log($j / $s);
                }
            }

//          Find the probability of generating a few arbitrarily choosen good and
//          bad phrases.
            $good_lines = file($this->Good);
            $good_probs = array();
            foreach ($good_lines as $line) {
                array_push($good_probs, self::_averageTransitionProbability($line, $log_prob_matrix, TRUE));
            }
//          Removed the bad lines probability. I see no need for this because it just lower the threshold.
//          and it just a score like the good_lines are
            /*$bad_lines = file($this->Bad);
            $bad_probs = array();
            foreach ($bad_lines as $line) {
                array_push($bad_probs, self::_averageTransitionProbability($line, $log_prob_matrix));
            }*/
//          Assert that we actually are capable of detecting the junk.
            /*$min_good_probs = min($good_probs);
            $max_bad_probs = max($bad_probs);

            if ($min_good_probs <= $max_bad_probs) {
                return false;
            }*/

//          And pick a threshold halfway between the worst good and best bad inputs.
            /*$threshold = ($min_good_probs + $max_bad_probs) / 2;*/
            $threshold = array_sum($good_probs) / count($good_probs);

            return array(
              'Matrix'     => $log_prob_matrix,
              'Threshold'  => $threshold,
              'Percentage' => $this->GibberishWordPercent
            );


        }

        // Scoring Function
        public function GibberishScore($text, $raw = FALSE) {
            $value = self::_averageTransitionProbability($text, $this->matrix, FALSE);
            if ($raw === TRUE) {
                return $value;
            }
            if ($value <= $this->threshold) {
                return TRUE;
            }

            return FALSE;
        }

        // Testing function to detect gibberish
        public function GetWordProbability($text){
            $journal = str_replace(self::$_punctation, '', $text);

            //limit the array to size 600 unique words to help performance.
            $journal = explode(' ', $journal, 600);

            //make the frequency table
            $freq_table = array_count_values($journal);

            // Builds probability table
            $prob_table = array();
            foreach($freq_table as $word => $row)
            {
                foreach($freq_table as $wordTwo => $rowTwo){

                }
            }
            $a = FALSE;
            foreach ($journal as $b) {
                if ($a !== FALSE) {

                }
                $a = $b;
            }
        }

        public function GetWordCount($text) {
            $journal = str_replace(self::$_punctation, '', $text);

            //limit the array to size 600 unique words to help performance.
            $journal = explode(' ', $journal, 600);

            //make the frequency table
            $freq_table = array_count_values($journal);
            ksort($freq_table);

            //initialize variables
            $total_words = 0;
            $total_english_words = 0;
            $unique_words = count($freq_table);
            $unique_english_words = 0;
            $avg_word_len = 0;
            $test = 0;

            //loop through every word
            $pspell_link = pspell_new("en_US");
            foreach ($freq_table as $word => $frequency) {
                $test += 1;
                $avg_word_len = $avg_word_len + strlen($word) * $frequency;
                $total_words = $total_words + $frequency;
                if (pspell_check($pspell_link, $word)) {
                    $unique_english_words += 1;
                    $total_english_words = $total_english_words + $frequency;
                }
            }

            $avg_word_len = round($avg_word_len / $total_words, 1);
            return array(
              'total_words'          => $total_words,
              'total_english_words'  => $total_english_words,
              'unique_words'         => $unique_words,
              'unique_english_words' => $unique_english_words,
              'avg_word_len'         => $avg_word_len,
            );
        }

        public function DetermineGibberish($postscore, $postpercentage) {
            $resultPercent = 1;
            $resultScore = 1;
            $result = 1;

            /*if ($this->precentage > $postpercent){
                $resultPercent = FALSE;
            }

            if($this->threshold > $postscore)
            {
                $resultScore = FALSE;
            }

            if($resultPercent == false){
                $result = false;
            }
            if ($resultScore == false){
                $result = false;
            }
            if(($resultPercent == FALSE) && ($resultScore == FALSE))
            {
                $result = false;
            }*/

            // return true for it is Gibberish
            if($postscore < $this->threshold){
                return 1;
            }
            // return true for "it is Gibberish"
            else if($postpercentage < $this->precentage){
                return 1;
            }

            return 0;
        }

        //TODO delete
        public function GetControlData() {
            $sql = "SELECT * FROM ws_matrix_control_data";
            $query = $this->db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        }

        public function BuildControlPost() {
            // local variables
            $sql = "INSERT INTO ws_matrix_control_data (body_text) VALUE (:bodyText)";
            $query = $this->db->prepare($sql);
            $index = 0;
            $post = '';

            // Get Book to become Control Post
            $lines = file($this->BigDirectory . DIRECTORY_SEPARATOR . 'TheStoryOfHungary.txt');

            // Remove lines that only returns
            $lines = array_diff($lines, array("\r\n"));

            foreach ($lines as $line) {
                if ($index < 10) {
                    $post .= $line;
                    $index++;
                }
                else {
                    // SQL post to database
                    $paramaters = array(':bodyText' => $post);
                    $query->execute($paramaters);
                    $post = '';
                    $index = 0;
                }
            }


        }

        public function UpdateControlPost() {
            $sql = "SELECT * FROM ws_matrix_control_data";
            $query = $this->db->prepare($sql);
            $query->execute();
            $controlData = $query->fetchAll();

            $sql = "UPDATE ws_matrix_control_data SET body_text = :new_text WHERE id = :id";
            $query = $this->db->prepare($sql);

            foreach ($controlData as $post) {
                $string = str_replace('/[^0-9]/ ', ' ', $post->body_text);
                $string = str_replace(' ', '-', $string);
                $string = preg_replace('/[^A-Za-z\-]/', '', $string);
                $string = preg_replace('/-+/', '-', $string);
                $newPost = str_replace('-', ' ', $string);
                $paramaters = array(
                  ':new_text' => $newPost,
                  'id'        => $post->id
                );
                $query->execute($paramaters);
            }
        }
    }