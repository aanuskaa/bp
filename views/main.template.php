<?php

use flow\Flow;

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */

?><!DOCTYPE html><html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <link rel="stylesheet" href="<?php echo ROOT_URL . 'style/main.min.css'?>"/>
        <link rel="stylesheet" href="<?php echo ROOT_URL . 'style/normalize.min.css'?>"/>
        <link rel="stylesheet" href="<?php echo ROOT_URL . 'style/custom.css'?>"/>
        <script type="text/javascript" src="<?php echo ROOT_URL . 'js/jquery-2.2.3.min.js'?>"></script>
    </head>
    <body>
        <?php
        include_once __DIR__ .'/../global/modules/header.php';
        ?>
        <div class="container mt">
            <div class="row">
                <div class="col-lg-2">
                    <nav>
                        <ul class="menu-h">
                            <li><a href="<?php echo ENTRY_SCRIPT_URL . 'petrinet/filter'?>">Create Case</a></li>
                            <li><a href="<?php echo ENTRY_SCRIPT_URL . 'task/listAvailable'?>">Take Tasks</a></li>
                            <li><a href="<?php echo ENTRY_SCRIPT_URL . 'task/listAll'?>">My Tasks</a></li>
                            <li><a href="<?php echo ENTRY_SCRIPT_URL . 'task/finished'?>">Tasks history</a></li>
                            <li><a href="<?php echo ENTRY_SCRIPT_URL . 'case/viewFinished'?>">Cases history</a></li>
                            <li><a href="<?php echo ENTRY_SCRIPT_URL . 'case/viewActive'?>">Current Cases</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-10">
                    <div class="content">
                        <?php echo Flow::app()->alertmanager->displayAlerts(); ?>
                        <?php echo $content; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt">
            <?php
            include_once __DIR__ .'/../global/modules/footer.php';
            ?>
        </div>
    </body>
</html>