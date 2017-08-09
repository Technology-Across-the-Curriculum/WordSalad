<!-- DataTables JavaScript -->
<script
  src="<?php echo URL; ?>libs/datatables/media/js/jquery.dataTables.min.js"></script>
<script
  src="<?php echo URL; ?>libs/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('#datatable-test-details').DataTable({
            responsive: true

        });
    });
</script>




<div class="col-lg-12">
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-2">
                        <i class="fa fa-bar-chart fa-5x"></i>
                    </div>
                    <div class="col-xs-10 text-right">
                        <div
                          class="large"><?php echo $resultDetails['TotalTest']; ?></div>
                        <div>Total Post!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-times fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div
                          class="large"><?php echo $resultDetails['TotalGibberish']; ?></div>
                        <div>Total Fail Post!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-check fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div
                          class="large"><?php echo $resultDetails['TotalNonGibberish'];?></div>
                        <div>Total Passed Post!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-6 col-md-6">
    <div class="panel panel-yellow">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-2">
                    <i class="fa fa-5x"><strong>#</strong></i>
                </div>
                <div class="col-xs-10 text-right">
                    <div
                      class="large"><?php echo number_format($resultTest['threshold'], 6, '.', ''); ?></div>
                    <div>Current Threshold!</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-3">
                    <i class="fa fa-5x"><strong>%</strong></i>
                </div>
                <div class="col-xs-9 text-right">
                    <div
                      class="large"><?php echo $resultTest['percentage']; ?></div>
                    <div>Current Word Percentage!</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <table class="table" id="datatable-test-details">
        <thead>
            <tr>
                <th>Id</th>
                <th>Is Gibberish</th>
                <th>Score</th>
                <th>Word Percentage</th>
                <th>Unique Words</th>
                <th>Total Words</th>
                <th>Unique English Words</th>
                <th>Total English Words</th>
                <th>Text</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0;
            foreach ($resultScores as $score) { ?>
                <tr>
                    <td><?php if (isset($score->id)) {
                            echo htmlspecialchars($score->id, ENT_QUOTES, 'UTF-8');
                        } ?></td>

                    <td><?php
                            if(isset($score->is_gibberish)) {
                                if ($score->is_gibberish == FALSE) {
                                    echo '<div class="alert alert-success"><p>' . $score->is_gibberish . 'Post Passed</p></div>';
                                }
                                elseif ($score->is_gibberish == TRUE) {
                                    echo '<div class="alert alert-warning"><p>' . $score->is_gibberish . 'Post Failed</p></div>';
                                }


                        } ?></td>
                    <td><?php if (isset($score->gibberish_score)) {
                            if ($score->gibberish_score < $resultTest['threshold']) {
                                echo '<div class="alert alert-warning"><p>' . htmlspecialchars($score->gibberish_score, ENT_QUOTES, 'UTF-8') . '</p></div>';
                            }
                            elseif ($resultTest['threshold'] <= $score->gibberish_score)  {
                                echo '<div class="alert alert-success"><p>' . htmlspecialchars($score->gibberish_score, ENT_QUOTES, 'UTF-8') . '</p></div>';
                            }
                            else{
                                echo '<div class="alert alert-error"><p> Error! </p></div>';
                            }

                        } ?></td>
                    <td><?php if (isset($score->word_percentage)) {
                            if ($score->word_percentage < $resultTest['percentage']) {
                                echo '<div class="alert alert-warning"><p>' . htmlspecialchars($score->word_percentage, ENT_QUOTES, 'UTF-8') . '</p></div>';
                            }
                            elseif ($resultTest['percentage'] <= $score->word_percentage) {
                                echo '<div class="alert alert-success"><p>' . htmlspecialchars($score->word_percentage, ENT_QUOTES, 'UTF-8') . '</p></div>';
                            }
                            else{
                                echo '<div class="alert alert-error"><p> Error! </p></div>';
                            }
                        } ?></td>
                    <td><?php if (isset($score->unique_word)) {
                            echo htmlspecialchars($score->unique_word, ENT_QUOTES, 'UTF-8');
                        } ?></td>
                    <td><?php if (isset($score->total_word)) {
                            echo htmlspecialchars($score->total_word, ENT_QUOTES, 'UTF-8');
                        } ?></td>
                    <td><?php if (isset($score->unique_english_word)) {
                            echo htmlspecialchars($score->unique_english_word, ENT_QUOTES, 'UTF-8');
                        } ?></td>
                    <td><?php if (isset($score->total_english_word)) {
                            echo htmlspecialchars($score->total_english_word, ENT_QUOTES, 'UTF-8');
                        } ?></td>
                    <td>
                        <div class="">
                            <?php
                                if ($resultTest['is_gibberish']) {
                                    if (isset($resultText[$score->gibberish_id]->id)) {
                                        echo $resultText[$score->gibberish_id - 1]->body_text;
                                    }
                                }
                                elseif ($resultTest['is_control']) {
                                    if (isset($resultText[$score->control_id]->id)) {
                                        echo $resultText[$score->control_id - 1]->body_text;
                                    }
                                }
                            else if ($resultTest['is_live']){
                                if(isset($resultText[$count]->nid)){
                                    echo $resultText[$count]->body_text;
                                    $count++;
                                }
                            }
                            ?>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

