<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top navbar-fixed-top"
         role="navigation"
         style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.html">WordSalad Admin v2.0</a>
        </div>
        <!-- /.navbar-header -->


        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <li>
                        <a href="<?php echo URL; ?>DashboardController/index"><i
                              class="fa fa-dashboard fa-fw"></i>Dashboard</a>
                    </li>
                    <!--<li>
                        <a href="<?php /*echo URL; */?>DashboardController/debug"><i
                              class="fa fa-bug fa-fw"></i>Debug Output</a>
                    </li>-->
                    <li>
                        <a href="<?php echo URL; ?>MatrixController/index"><i class="fa fa-table fa-fw"></i>Matrix</a>
                    </li>
                    <!--<li>
                        <a href="<?php /*echo URL; */?>BackupLogController/index">
                            <i class="fa fa-edit fa-fw"></i>Logs</a>
                    </li>-->
                    <!--<li>
                        <a href="<?php /*echo URL; */?>TestController/index">
                            <i class="fa fa-edit fa-fw"></i>Test WordSalad</a>
                    </li>-->
                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><?php if (isset($this->location)) {
                        echo $this->location;
                    } ?></h1>
                <div><?php if (isset($this->message)) {
                        echo $this->message;
                    } ?></div>
            </div>
            <!-- /.col-lg-12 -->
        </div>