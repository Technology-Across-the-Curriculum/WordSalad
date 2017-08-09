<div class="col-lg-6 col-md-6">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-2">
                    <i class="fa fa-clock-o fa-5x"></i>
                </div>
                <div class="col-xs-10 text-right">
                    <div><h2><?php echo $resultCurrent['update_last']; ?></h2></div>
                    <div>Last Backup</div>
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
                    <i class="fa fa-gears fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">
                    <div class="huge">
                        <a href="<?php echo URL; ?>BackupLogController/createBackup"><div class="btn btn-primary">Create Backup</div></a>
                    </div>
                    <div>Options</div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col-lg-12">
    <table class="table" id="dataTable-log">
        <thead>
            <tr>
                <th>Id</th>
                <th>Backup Time </th>
                <th>Threshold</th>
                <th>Percentage</th>

                <th class="text-center">OPTIONS</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultLog as $log) { ?>
                <tr>
                    <td><?php if (isset($log->id)) {
                            echo htmlspecialchars($log->id, ENT_QUOTES, 'UTF-8');
                        } ?></td>
                    <td><?php if (isset($log->backup_time)) {
                            echo htmlspecialchars($log->backup_time, ENT_QUOTES, 'UTF-8');
                        } ?></td>
                    <td><?php if (isset($log->threshold)) {
                            echo htmlspecialchars($log->threshold, ENT_QUOTES, 'UTF-8');
                        } ?></td>
                    <td><?php if (isset($log->threshold)) {
                            echo htmlspecialchars($log->percentage, ENT_QUOTES, 'UTF-8');
                        } ?></td>
                    <td class="text-center">
                        <a
                          href="<?php echo URL . 'BackupLogController/logDetails/' . htmlspecialchars($log->id, ENT_QUOTES, 'UTF-8'); ?>"
                          class="btn btn-default">
                            Details
                        </a>
                        <a
                          href="<?php echo URL . 'BackupLogController/rollBackMatrix/' . htmlspecialchars($log->id, ENT_QUOTES, 'UTF-8'); ?>"
                          class="btn btn-warning">
                            Roll Back
                        </a>

                        <a
                          href="<?php echo URL . 'BackupLogController/deleteLog/' . htmlspecialchars($log->id, ENT_QUOTES, 'UTF-8'); ?>"
                          class="btn btn-danger">
                            Delete
                        </a>
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
        $('#dataTable-log').DataTable({
            responsive: true

        });
    });
</script>