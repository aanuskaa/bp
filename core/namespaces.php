<?php

/**
 * Associative array of automatically added framework namespaces
 * 
 * Array format
 * [
 *      namespace1 => base_dir1,
 *      namespace2 => [base_dir2, base_dir3],
 *      ...
 * ]
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */

$namespaces = [
    'flow' => APP_PATH . 'core/',
    'app\controllers' => APP_PATH . 'controllers/',
    'app\models' => APP_PATH . 'models/',
];
