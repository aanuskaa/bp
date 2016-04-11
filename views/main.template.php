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
        <script type="text/javascript" src="<?php echo ROOT_URL . 'js/jquery-2.2.1.min.js'?>"></script>
    </head>
    <body>
        <div id="main-container">
            <div class="header">
                <div class="container">
                    <span class="icon">
                        <img src ="<?php echo ROOT_URL . 'style/logo.png'?>"/>
                    </span>
                    <h1><span>Work</span><span id="orange">flow</span></h1>
                </div>
            </div>
            <nav class="container">
                <a href="<?php echo ENTRY_SCRIPT_URL . 'petrinet/filter'?>">Vytvor case</a>
                <a href="<?php echo ENTRY_SCRIPT_URL . 'task/listAvailable'?>">Vsetky tasky na zobratie</a>
                <a href="<?php echo ENTRY_SCRIPT_URL . 'task/listAll'?>">Uzivatelove tasky</a>
            </nav>
            <div class="content container">
                <?php echo $content; ?>
            </div>
        </div>
    </body>
</html>