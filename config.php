<?php

/**
 * Hlavný konfiguračný súbor
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
define('ROOT_URL', 'http://localhost/workflow/');
define('ENTRY_SCRIPT_URL', 'http://localhost/workflow/index.php/');
$config = [
    'db' => [
        'dns'  => 'mysql:host=localhost;dbname=workflow;charset=utf8',
        'user' => 'root',
        'pass' => '', 
    ],
    
];