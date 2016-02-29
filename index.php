<?php
/**
 * Application entry script
 *
 * @package    app
 * @author     Anuska
 * @link       http://workflow.com/
 * @version    1.0.0
 */

error_reporting( E_ALL );       //typ error 
ini_set('display_errors', 1);   //zobrazenie errorov

define('APP_PATH', __DIR__ . '/');

use flow\Flow;

require_once 'core/Flow.php';
require_once 'config.php';

Flow::app()->start($config);

