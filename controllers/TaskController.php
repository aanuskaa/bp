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
 * Controller pre pracu s ulohami
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.market/
 * @version    1.0.0
 */
class TaskController extends AbstractController{
    
    /**
     * @inheritdoc 
     */
    protected function accessRules(){
        return [
            'logged_in' => ['take', 'listAll', 'listAvailable', 'finish', 'cancel', 'finished'],
        ];
    }
    
    /**
     * Vylistuje vsetky tasky, ktore si moze pouzivatel vziat
     */
    protected function listAvailable(){
        $firm_query = 'SELECT 
                            USERS_X_FIRM.firm_id, FIRM.firm_name
                        FROM
                            USERS_X_FIRM
                                LEFT JOIN
                            FIRM ON USERS_X_FIRM.firm_id = FIRM.firm_id
                        WHERE
                            user_id = ' . Flow::app()->auth->getUserId() . ';';
        $firms =  Flow::app()->pdo->query($firm_query)->fetchAll(PDO::FETCH_OBJ);
        $query = 'SELECT DISTINCT
                    USERS_X_FIRM.firm_id,
                    FIRM.firm_name,
                    `case`.timestamp_stop,
                    `case`.id AS case_id,
                    `case`.`name` AS case_name,
                    `case`.id_pn,
                    `transition`.id AS transition_id,
                    `transition`.`name` AS transition_name,
                    TRANSITIONS_X_ROLE.id_role,
                    ROLES.role_name,
                    `REFERENCES`.referenced_transition_id,
                    `REFERENCES`.value

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
                        `case`.timestamp_stop IS NULL
                    AND (
                                EXISTS( 
                                        SELECT 
                                                *
                                        FROM
                                                USERS_X_ROLE
                                        WHERE
                                                user_id = ' . Flow::app()->auth->getUserId() . '
                                                        AND USERS_X_FIRM.firm_id = USERS_X_ROLE.firm_id
                                                        AND TRANSITIONS_X_ROLE.id_role = USERS_X_ROLE.role_id
                                        )        
                        OR referenced_transition_id IS NOT NULL        
                        OR id_role IS NULL
                        );' ;
        $result = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
        $data = $this->sanitizeTasksData($result);
        
        foreach ($data as $firm_id=>$firm){
            foreach($firm['cases'] as $case_id=>$case){
                foreach ($case['tasks'] as $task_id=>$task){
                    if(!TransitionModel::model()->isEnabled($case_id, $task_id)){
                        unset($data[$firm_id]['cases'][$case_id]['tasks'][$task_id]);
                    }
                    elseif($task['reference'] != NULL || $task['referencevalue']){
                        $ref = $this->resolveReferences($firm_id, $case_id, $task_id, $task['reference'], $task['referencevalue'], $task['id_role'], $case['id_pn']);
                        switch ($ref){
                            case [FALSE, FALSE]:
                                unset($data[$firm_id]['cases'][$case_id]['tasks'][$task_id]);
                                break;
                            case [TRUE, FALSE]:
                                $data[$firm_id]['cases'][$case_id]['tasks'][$task_id]['reference'] = NULL;
                                break;
                            case [TRUE, TRUE]:
                                $data[$firm_id]['cases'][$case_id]['tasks'][$task_id]['reference'] = 1;
                                break;
                        }
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
        if(isset($_GET['ajaxify'])){

            $this->renderPartial('listavailable.table', ['firm_cases' => $data, 'firms' => $firms]);
        }
        else{
            $this->render('listavailable', ['firm_cases' => $data, 'firms' => $firms]);
        }
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
            $reset = TransitionModel::model()->fireStart($temp[0], $temp[1]);
            $caseProgress = new Case_ProgressModel;
            $caseProgress->id_case = $temp[0];
            $caseProgress->id_transition = $temp[1];
            $caseProgress->started_by = Flow::app()->auth->getUserId();
            $caseProgress->timestamp_start=  date("Y-m-d H:i:s");
            $caseProgress->save(TRUE);
            if(!empty($reset)){
                foreach ($reset as $r){
                    $query = 'INSERT INTO `reset_arc_cancel` (`case_progress_id`,`consumed_tokens`, `arc_id`) VALUES (' . $caseProgress->id . ', ' . $r->tokens . ', ' . $r->id . ');'; 
                    Flow::app()->pdo->query($query);
                }
            }
            
        }
        else{
            Flow::app()->alertmanager->setAlert('task-error', 'alert-danger', 'Task uz nie je k dispozicii');
        }
        header('Location:' . ENTRY_SCRIPT_URL . 'task/listAvailable', TRUE, 301);
    }
    
    /**
     * Pouzivatel vrati nedokonceny task, "vrati spat tokeny"
     */
    protected function cancel(){
        $temp = explode(',', $_POST['cancel']);
        $caseProgress = Case_ProgressModel::model()->findOne('id = ' .  $temp[0]);
        TransitionModel::model()->returnTokens($temp[1], $temp[2], $caseProgress->id);
        $caseProgress->delete();
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
                $arr[$r->firm_id]['cases'][$r->case_id]['tasks'][$r->transition_id]['referencevalue'] = $r->value;
            }
        }
        return $arr;
    }
    
    /**
     * Funkcia riesi referencie na predosle prechody.
     * Kontroluje, ci predosly prechod bol spusteny
     * Ak bol spusteny, zisti, ci ho spustil dany pouzivatel, ak nebol, zisti, ci ho prihlaseny pouzivatel moze spustit
     * @param type $firm_id
     * @param type $case_id
     * @param type $transition_id
     * @param type $referencedtransition_id
     * @return array [boolean, boolean]  prva hodnota urcuje, ci pouzivatel moze spustit dany prechod
     *                                   druha ci ho spusta na zaklade platnej referencie na neho
     */
    private function resolveReferences($firm_id, $case_id, $transition_id, $referencedtransition_id, $value, $role_id, $pn_id){
        $user_id = Flow::app()->auth->getUserId();
        /*Referencia na prveho pouzivatela*/
        if($value == 1){
            $query = 'SELECT 
                            *
                        FROM
                            TRANSITIONS_X_ROLE
                                LEFT JOIN
                            `transition` ON `transition`.id = TRANSITIONS_X_ROLE.id_prechod
                                LEFT JOIN
                            `case_progress` ON `transition`.id = `case_progress`.id_transition
                        WHERE
                            `case_progress`.id_case = ' . $case_id . '
                                AND `transition`.id_pn = ' . $pn_id . '
                                AND id_role = ' . $role_id . ' 
                        ORDER by timestamp_start;';
            $result = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
            if(empty($result)){
                return[TRUE, FALSE];
            }
            elseif($result[0]->started_by == $user_id){
                return[TRUE, TRUE];
            }
            else{
                return[FALSE,FALSE];
            }
        }
        
        $referencedTask = Case_ProgressModel::model()->findOne('id_case=' . $case_id . ' AND id_transition=' . $referencedtransition_id);
        /*Referencovany prechod nebol spusteny*/
        if(empty($referencedTask)){
            $query = 'SELECT * FROM TRANSITIONS_X_ROLE WHERE id_prechod = ' . $transition_id;
            $role_check = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
                /*Prechod nema naviazanu rolu*/
                if(empty($role_check)){
                    return[TRUE, FALSE];
                }
                /*Prechod ma naviazanu rolu*/
                else{
                    $query = 'SELECT * FROM USERS_X_ROLE WHERE user_id = '
                            . $user_id . ' AND firm_id = ' . $firm_id . ' AND role_id = ' . $role_check[0]->id_role . ';';
                    $role_check = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
                    /*Pouzivatel v danej firme nema danu rolu*/
                    if(empty($role_check)){
                        return [FALSE, FALSE];
                    }
                    /*Pouzivatel v danej firme ma danu rolu*/
                    else{
                        return [TRUE, FALSE];
                    }
                }
        }
        /*Referencovany prechod bol spusteny*/
        else{
            /*Pouzivatel spustil referencovany prechod*/
            if($referencedTask->started_by == $user_id){
                return [TRUE, TRUE];
            }
            /*Pouzivatel nespustil dany prechod*/
            else{
                $query = 'SELECT * FROM USERS_X_FIRM WHERE user_id = ' . $referencedTask->started_by . ' AND firm_id = ' . $firm_id . ';';
                $user_firm_check = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
                /*Pouzivatel, ktory referencovany prechod spustil uz nie je vo firme*/
                if(empty($user_firm_check)){
                    $query = 'SELECT * FROM TRANSITIONS_X_ROLE WHERE id_prechod = ' . $transition_id;
                    $role_check = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
                    /*Prechod nema naviazanu rolu*/
                    if(empty($role_check)){
                        return[TRUE, FALSE];
                    }
                    /*Prechod ma naviazanu rolu*/
                    else{
                        $query = 'SELECT * FROM USERS_X_ROLE WHERE user_id = '
                                . $user_id . ' AND firm_id = ' . $firm_id . ' AND role_id = ' . $role_check[0]->id_role . ';';
                        $role_check = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
                        /*Pouzivatel v danej firme nema danu rolu*/
                        if(empty($role_check)){
                            return [FALSE, FALSE];
                        }
                        /*Pouzivatel v danej firme ma danu rolu*/
                        else{
                            return [TRUE, FALSE];
                        }
                    }
                }
                /*Pouzivatel, ktory referencovany prechod spustil je stale vo firme*/
                else{
                    return [FALSE, FALSE];
                }
            }
        }
    }
    /**
     * Funkcia vyfiltruje z DB tasky pouzivatela, ktore uz dokoncil
     */
    public function finished(){
        $query = 'SELECT 
                    `case_progress`.id_case,
                    `case_progress`.id_transition,
                    transition.`name` AS task_name,
                    `case_progress`.timestamp_start,
                    `case_progress`.timestamp_stop,
                    `case`.`name` AS case_name,
                    `case`.firm,
                    FIRM.`firm_name` AS firm_name
                FROM
                    workflow.case_progress
                        LEFT JOIN
                    `case` ON case_progress.id_case = `case`.id
                        LEFT JOIN
                    `FIRM` ON `case`.firm = FIRM.firm_id
                        LEFT JOIN
                    `transition` ON `case_progress`.id_transition = transition.id
                WHERE
                    case_progress.started_by = ' . Flow::app()->auth->getUserId() . '
                        AND case_progress.timestamp_stop IS NOT NULL;';
        $data= Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
        $this->render('viewFinished', ['tasks' => $data]);
    }
}
