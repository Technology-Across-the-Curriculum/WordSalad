<div class="row">
  <div class="col-lg-12 col-md-12">
      <div class="panel panel-default">
          <div class="panel-heading">
              <div class="row">
                  <div class="col-xs-3">
                      <i class="fa fa-gears fa-5x"></i>
                  </div>
                  <div class="col-xs-9 text-right">
                    <a href="<?php echo URL; ?>WordSaladController/initializeMatrix">
                        <div class="btn btn-warning">Initialize Matrix</div>
                    </a>
                      <a href="<?php echo URL; ?>WordSaladController/trainMatrix">
                          <div class="btn btn-primary">Train Matrix</div>
                      </a>
                      <a href="<?php echo URL; ?>BackupLogController/createBackup">
                          <div class="btn btn-success">Create Backup</div>
                      </a>

                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 display-matrix">
      <?php $this->model->PrintMatrix(); ?>
  </div>
</div>
