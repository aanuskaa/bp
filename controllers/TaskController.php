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
                        WHERE user_id = ' . Flow::app()->auth->getUserId() . '
                                        AND USERS_X_FIRM.firm_id = USERS_X_ROLE.firm_id
                                        AND TRANSITIONS_X_ROLE.id_role = USERS_X_ROLE.role_id)
                        OR referenced_transition_id IS NOT NULL
                    OR id_role IS NULL;';
        $result = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
        $data = $this->sanitizeTasksData($result);
        
        foreach ($data as $firm_id=>$firm){
            foreach($firm['cases'] as $case_id=>$case){
                foreach ($case['tasks'] as $task_id=>$task){
                    if(!TransitionModel::model()->isEnabled($case_id, $task_id)){
                        unset($data[$firm_id]['cases'][$case_id]['tasks'][$task_id]);
                    }
                    elseif($task['reference'] != NULL){
                        $this->resolveReferences($firm_id, $case_id, $task_id, $task['reference']);
                    }
                }
                if(empty($data[$firm_id]['cases'][$case_id]['tasks'])){
                    unset($data[$firm_id]['cases'][$case_id]);
                }
            }
            if(empty($data[$firm_id]['cases'])){
                unset($data[$firm_id]);
            }
        }
        $this->render('listavailable', ['firm_cases' => $data]);
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
            $caseProgress->save(TRUE);
            
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
        header('Location:' . ENTRY_SCRIPT_URL . 'task/listAll', TRUE, 301);
    }
    
    /**
     * Spracuje dokoncenie tasku pouzivatelom
     */
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
    
    /**
     * Funkcia spracuje data z DB do pozadovaneho formatu
     * @param type $data
     * @return type
     */
    private function sanitizeTasksData($data){
        $arr = [];
        foreach ($data as $r){
            if(!isset($arr[$r->firm_id])){
                $arr[$r->firm_id] = [];
                $arr[$r->firm_id]['name'] = $r->firm_name;
                $arr[$r->firm_id]['cases'] = [];
                
            }
            if (!isset($arr[$r->firm_id]['cases'][$r->case_id]) && $r->case_id != NULL) {
                $arr[$r->firm_id]['cases'][$r->case_id] = [];
                $arr[$r->firm_id]['cases'][$r->case_id]['name'] = $r->case_name;
                $arr[$r->firm_id]['cases'][$r->case_id]['id_pn'] = $r->id_pn;
                $arr[$r->firm_id]['cases'][$r->case_id]['tasks'] = [];
            }
            if ($r->transition_id != NULL) {
                $arr[$r->firm_id]['cases'][$r->case_id]['tasks'][$r->transition_id] = [];
                $arr[$r->firm_id]['cases'][$r->case_id]['tasks'][$r->transition_id]['name'] = $r->transition_name ;
                $arr[$r->firm_id]['cases'][$r->case_id]['tasks'][$r->transition_id]['id_role'] = $r->id_role ;
                $arr[$r->firm_id]['cases'][$r->case_id]['tasks'][$r->transition_id]['role_name'] = $r->role_name ;
                $arr[$r->firm_id]['cases'][$r->case_id]['tasks'][$r->transition_id]['reference'] = $r->referenced_transition_id;
            }
        }
        return $arr;
    }
    
    /**
     * Funkcia riesi referencie na predosle prechody.
     * Kontroluje, ci predosly prechod bol spusteny
     * Ak bol spusteny, zisti, ci ho spustil dany pouzivatel, ak nebol, zisti, ci ho prihlaseny pouzivatel moze spustit
     */
    private function resolveReferences($firm_id, $case_id, $transition_id, $referencedtransition_id){
        $referencedTask = Case_ProgressModel::model()->findOne('id_case=' . $case_id .
                ' AND id_transition=' . $referencedtransition_id);
        var_dump(empty($referencedTask));
        if(empty($referencedTask)){
            
        }
        else{
            
        }
        return true;
    }
    
}
