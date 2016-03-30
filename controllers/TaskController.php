<?php

namespace app\controllers;

use app\models\Case_ProgressModel;
use app\models\CaseModel;
use app\models\TransitionModel;
use flow\AbstractController;
use flow\Flow;
use PDO;
use const ENTRY_SCRIPT_URL;

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
class TaskController extends AbstractController{
    
    /**
     * @inheritdoc 
     */
    protected function accessRules(){
        return [
            'logged_in' => ['take', 'listAll', 'listAvailable', 'finish', 'cancel'],
        ];
    }
    /**
     * Vylistuje vsetky tasky, ktore si moze pouzivatel vziat
     */
    protected function listAvailable(){
        $query = 'SELECT DISTINCT
                    USERS_X_FIRM.firm_id,
                    FIRM.firm_name,
                    `case`.id AS case_id,
                    `case`.`name` AS case_name,
                    `case`.id_pn,
                    `transition`.id AS transition_id,
                    `transition`.`name` AS transition_name,
                    TRANSITIONS_X_ROLE.id_role,
                    ROLES.role_name,
                    `REFERENCES`.referenced_transition_id
                FROM
                    USERS_X_FIRM
                                LEFT JOIN 
                        FIRM ON FIRM.firm_id = USERS_X_FIRM.firm_id
                        LEFT JOIN
                    `case` ON `case`.firm = USERS_X_FIRM.firm_id
                        LEFT JOIN
                    transition ON `case`.id_pn = transition.id_pn
                        LEFT JOIN
                    TRANSITIONS_X_ROLE ON TRANSITIONS_X_ROLE.id_prechod = transition.id
                        LEFT JOIN
                    ROLES ON ROLES.role_id = TRANSITIONS_X_ROLE.id_role
                        LEFT JOIN
                    `REFERENCES` ON `REFERENCES`.transition_id = transition.id
                WHERE
                    EXISTS( SELECT * FROM
                            USERS_X_ROLE
                        WHERE user_id = 1
                                        AND USERS_X_FIRM.firm_id = USERS_X_ROLE.firm_id
                                        AND TRANSITIONS_X_ROLE.id_role = USERS_X_ROLE.role_id)
                        OR referenced_transition_id IS NOT NULL
                    OR id_role IS NULL;';
        $result = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
//        var_dump($result);
        var_dump($this->sanitizeTasksData($result));
        
//        $cases = CaseModel::model()->findAll('timestamp_stop is NULL');
//        foreach ($cases as $case) {
//            $case->tasks = TransitionModel::model()->findAllEnabled($case);
//        }
//        $this->render('listavailable', ['cases' => $cases]);
    }
    
    /**
     * Vylistuje vsetky pouzivatelove tasky
     */
    protected function listAll(){
        $tasks = Case_ProgressModel::model()->findAll(
            '`case_progress`.started_by = ' . Flow::app()->auth->getUserId() . ' AND `case_progress`.timestamp_stop is NULL',

            '`case_progress`.id, `case_progress`.id_case, `case_progress`.id_transition, `case_progress`.timestamp_start, 
             `case`.`name` as case_name, `transition`.`name` as transition_name', 

            'LEFT JOIN `case` ON `case_progress`.id_case = `case`.id
             LEFT JOIN `transition` on `case_progress`.id_transition = `transition`.id'
        );      
        $this->render('listall', ['tasks' => $tasks]);
    }
    
    /**
     * Pouzivatel si vezme task ak je este k dispozicii,"zozere tokeny" 
     */
    protected function take(){
        $temp = explode(',', $_POST['task']);
        if(TransitionModel::model()->isEnabled($temp[0], $temp[1])){
            TransitionModel::model()->fireStart($temp[0], $temp[1]);
            $caseProgress = new Case_ProgressModel;
            $caseProgress->id_case = $temp[0];
            $caseProgress->id_transition = $temp[1];
            $caseProgress->started_by = Flow::app()->auth->getUserId();
            $caseProgress->timestamp_start=  date("Y-m-d H:i:s");
            var_dump($caseProgress->save(TRUE));
            var_dump($caseProgress->getValidationErrors());
            
        }
        else{
            echo 'Task uz nie je k dispozicii';
        }
        header('Location:' . ENTRY_SCRIPT_URL . 'task/listAvailable', TRUE, 301);
    }
    
    /**
     * Pouzivatel vrati nedokonceny task, "vrati spat tokeny"
     */
    protected function cancel(){
        $temp = explode(',', $_POST['cancel']);
        //var_dump($temp);
        TransitionModel::model()->returnTokens($temp[1], $temp[2]);
        $caseProgress = Case_ProgressModel::model()->findOne('id = ' .  $temp[0]);
        var_dump($caseProgress->delete());
        //var_dump($caseProgress->getValidationErrors());
        header('Location:' . ENTRY_SCRIPT_URL . 'task/listAll', TRUE, 301);
    }
    
    /*Pouzivatel dokonci task*/
    protected function finish() {
        $temp = explode(',', $_POST['task']);
        $caseProgress = Case_ProgressModel::model()->findOne('id=' . $temp[0]);
        $caseProgress->timestamp_stop = date("Y-m-d H:i:s");
        var_dump($caseProgress->save(TRUE));
        var_dump($caseProgress->getValidationErrors());
        TransitionModel::model()->fireStop($temp[2], $temp[1]);
        
        $checkTransitions = TransitionModel::model()->findAllEnabled(CaseModel::model()->findOne('id =' . $temp[1]));
        $checkProgress = Case_ProgressModel::model()->findAll('id_case =' . $temp[1] .' AND timestamp_stop IS NULL');
        
        if($checkTransitions  == NULL && $checkProgress == NULL){
            $case = CaseModel::model()->findOne('id = ' . $temp[1]);
            $case->timestamp_stop = date("Y-m-d H:i:s");
            $case->save();
        }
        header('Location:' . ENTRY_SCRIPT_URL . 'task/listAll', TRUE, 301);
    }
    
    private function sanitizeTasksData($data){
        $arr = [];
        foreach ($data as $r){
            if(!isset($arr[$r->firm_id])){
                $arr[$r->firm_id] = [];
                $arr[$r->firm_id]['name'] = $r->firm_name;
                $arr[$r->firm_id]['cases'] = [];
                
            }
            if (!isset($arr[$r->firm_id]['cases'][$r->case_id])) {
                $arr[$r->firm_id]['cases'][$r->case_id] = [];
                $arr[$r->firm_id]['cases'][$r->case_id]['name'] = [$r->case_name];
                $arr[$r->firm_id]['cases'][$r->case_id]['id_pn'] = [$r->id_pn];
                $arr[$r->firm_id]['cases'][$r->case_id]['tasks'] = [];
            }
            $arr[$r->firm_id]['cases'][$r->case_id]['tasks'][$r->transition_id] = [];
            $arr[$r->firm_id]['cases'][$r->case_id]['tasks'][$r->transition_id]['name'] = $r->transition_name ;
            $arr[$r->firm_id]['cases'][$r->case_id]['tasks'][$r->transition_id]['id_role'] = $r->id_role ;
            $arr[$r->firm_id]['cases'][$r->case_id]['tasks'][$r->transition_id]['role_name'] = $r->role_name ;
            $arr[$r->firm_id]['cases'][$r->case_id]['tasks'][$r->transition_id]['reference'] = $r->referenced_transition_id;
        }
        return $arr;
    }
    
    
}
