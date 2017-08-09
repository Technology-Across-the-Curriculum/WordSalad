<div class="col-lg-4 col-md-6">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-2">
                    <i class="fa fa-5x"><strong>#</strong></i>
                </div>
                <div class="col-xs-10 text-right">
                    <div class="huge"><?php echo number_format($resultCurrent['threshold'],5,'.',''); ?></div>
                    <div>Current Threshold!</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-4 col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-3">
                    <i class="fa fa-5x"><strong>%</strong></i>
                </div>
                <div class="col-xs-9 text-right">
                    <div class="huge"><?php echo $resultCurrent['percentage']; ?></div>
                    <div>Current Word Percentage!</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-4 col-md-6">
    <div class="panel panel-red">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-2">
                    <i class="fa fa-archive fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">
                    <div class="huge">
                        <a href="<?php echo URL; ?>TestController/gibberishTest" class="btn btn-default" >Gibberish Test</a>
                        <a href="<?php echo URL; ?>TestController/controlTest" class="btn btn-default" >Control Test</a>
                        <a href="<?php echo URL; ?>TestController/falltermTest" class="btn btn-default" >Fall Term Test</a>
                    </div>
                    <div>Test Options!</div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col-lg-12">
    <table class="table" id="dataTable-test">
        <thead>
            <tr>
                <th>Id</th>
                <th>Test Type</th>
                <th>Test Time</th>
                <th>Tested Threshold</th>
                <th>Tested Percentage</th>
                <th>Total Post Tested</th>
                <th class="text-center">OPTIONS</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultTest as $test) { ?>
                <tr>
                    <td><?php if (isset($test->id)) {
                            echo htmlspecialchars($test->id, ENT_QUOTES, 'UTF-8');
                        } ?></td>
                    <td><?php
                            if ($test->is_control) {
                            echo 'Control';
                        } elseif($test->is_gibberish){
                                echo 'Gibberish';
                            }
                        elseif($test->is_live){
                            echo 'Live';
                        }
                        ?>
                    </td>
                    <td><?php if (isset($test->test_time)) {
                            echo htmlspecialchars($test->test_time, ENT_QUOTES, 'UTF-8');
                        } ?></td>
                    <td><?php if (isset($test->threshold)) {
                            echo htmlspecialchars($test->threshold, ENT_QUOTES, 'UTF-8');
                        } ?></td>
                    <td><?php if (isset($test->percentage)) {
                            echo htmlspecialchars($test->percentage, ENT_QUOTES, 'UTF-8');
                        } ?></td>
                    <td><?php if (isset($test->total_post)) {
                            echo htmlspecialchars($test->total_post, ENT_QUOTES, 'UTF-8');
                        } ?></td>
                    <td class="text-center">
                        <?php
                        if ($test->is_control) {?>

                            <a
                              href="<?php echo URL . 'TestController/controlDetails/' . htmlspecialchars($test->id, ENT_QUOTES, 'UTF-8'); ?>"
                              class="btn btn-default">
                                Details
                            </a>
                            <a
                              href="<?php echo URL . 'TestController/deleteTest/' . htmlspecialchars($test->id, ENT_QUOTES, 'UTF-8'); ?>"
                              class="btn btn-danger">
                                Delete
                            </a>
                       <?php } elseif($test->is_gibberish){ ?>
                        <a
                          href="<?php echo URL . 'TestController/gibberishDetails/' . htmlspecialchars($test->id, ENT_QUOTES, 'UTF-8'); ?>"
                          class="btn btn-default">
                            Details
                        </a>
                        <a
                          href="<?php echo URL . 'TestController/deleteTest/' . htmlspecialchars($test->id, ENT_QUOTES, 'UTF-8'); ?>"
                          class="btn btn-danger">
                            Delete
                        </a>
                        <?php } elseif($test->is_live){ ?>
                            <a
                                href="<?php echo URL . 'TestController/fallDetails/' . htmlspecialchars($test->id, ENT_QUOTES, 'UTF-8'); ?>"
                                class="btn btn-default">
                                Details
                            </a>
                            <a
                                href="<?php echo URL . 'TestController/deleteTest/' . htmlspecialchars($test->id, ENT_QUOTES, 'UTF-8'); ?>"
                                class="btn btn-danger">
                                Delete
                            </a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- DataTables JavaScript -->
<script
  src="<?php echo URL; ?>libs/datatables/media/js/jquery.dataTables.min.js"></script>
<script
  src="<?php echo URL; ?>libs/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('#dataTable-test').DataTable({
            responsive: true,
            "order": [[ 0, "desc" ]]

        });
    });
</script>