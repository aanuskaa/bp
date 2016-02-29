<?php

namespace app\controllers;

use app\models\CaseModel;
use app\models\TransitionModel;
use app\models\Case_MarkingModel;

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
class TaskController extends \flow\AbstractController{
    
    public function listAvailableTasks(){
        $cases = CaseModel::model()->findAll('timestamp_stop is NULL');
        $tasks = [];
        foreach ($cases as $case) {
            $tasks[$case->id] = TransitionModel::model()->findAllEnabled($case);
        }
        var_dump($tasks);
    }
    
    public function listAllTakenTask(){
        
    }
    
    
}
