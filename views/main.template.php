<?php

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
        <link rel="stylesheet" href="<?php echo ROOT_URL . 'style/main.css'?>"/>
        <link rel="stylesheet" href="<?php echo ROOT_URL . 'style/normalize.css'?>"/>
        <link rel="stylesheet" href="<?php echo ROOT_URL . 'style/custom.css'?>"/>
        <script type="text/javascript" src="<?php echo ROOT_URL . 'js/jquery-2.2.1.min.js'?>"></script>
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
                            <li><a href="<?php echo ENTRY_SCRIPT_URL . 'petrinet/filter'?>">Vytvor case</a></li>
                            <li><a href="<?php echo ENTRY_SCRIPT_URL . 'task/listAvailable'?>">Vezmi úlohu</a></li>
                            <li><a href="<?php echo ENTRY_SCRIPT_URL . 'task/listAll'?>">Moje úlohy</a></li>
                            <li><a href="<?php echo ENTRY_SCRIPT_URL . 'task/finished'?>">História úloh</a></li>
                            <li><a href="<?php echo ENTRY_SCRIPT_URL . 'case/viewFinished'?>">História case-ov</a></li>
                            <li><a href="<?php echo ENTRY_SCRIPT_URL . 'case/viewActive'?>">Aktuálne case-y</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-10">
                    <div class="content">
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