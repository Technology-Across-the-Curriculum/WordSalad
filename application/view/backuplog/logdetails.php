<div class="col-lg-12">
    <table class="table table-bordered">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Time Changed</th>
                    <th>Threshold</th>
                    <th class="text-center">OPTIONS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php if (isset($log['id'])) {
                            echo htmlspecialchars($log['id'], ENT_QUOTES, 'UTF-8');
                        } ?></td>
                    <td><?php if (isset($log['backup-time'])) {
                            echo htmlspecialchars($log['backup-time'], ENT_QUOTES, 'UTF-8');
                        } ?></td>
                    <td><?php if (isset($log['threshold'])) {
                            echo htmlspecialchars($log['threshold'], ENT_QUOTES, 'UTF-8');
                        } ?></td>
                    <td class="text-center">
                        <a
                          href="<?php echo URL . 'WordSaladController/rollbackLog/' . htmlspecialchars($log['id'], ENT_QUOTES, 'UTF-8'); ?>"
                          class="btn btn-warning">
                            Roll Back
                        </a>

                        <a
                          href="<?php echo URL . 'WordSaladController/deleteLog/' . htmlspecialchars($log['id'], ENT_QUOTES, 'UTF-8'); ?>"
                          class="btn btn-danger">
                            Delete
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
</div>

<div class="col-lg-12 display-matrix">
    <?php if (isset($matrix)) {
        $MatrixModel->PrintGivenMatrix($matrix);
    } ?>
</div>
