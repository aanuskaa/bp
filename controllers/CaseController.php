<?php

namespace app\controllers;

use app\models\Case_MarkingModel;
use SimpleXMLElement;
use app\models\CaseModel;
use app\models\Petri_NetModel;
use app\models\PlaceModel;
use flow\AbstractController;
use flow\Flow;
use PDO;


/**
 * Controller pre pracu s caseom
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.market/
 * @version    1.0.0
 */
class CaseController extends AbstractController{
    
    /**
     * @inheritdoc 
     */
    protected function accessRules(){
        return [
            'logged_in' => ['create', 'viewActive', 'viewFinished', 'visualizeOne'],
        ];
    }
    
    /**
     * Funkcia na vytvorenie noveho case, ulozenie do DB a zaznacenie pociatocneho znackovania do DB
     */
    protected function create(){
        
        $case = new CaseModel();
        $case->name = $_POST['name'];
        $case->id_pn = $_POST['pn'];
        $case->firm = $_POST['firm'];
        
        var_dump($case);
        
        if(empty($case->id_pn)){
            Flow::app()->alertmanager->setAlert('case-error', 'alert-danger', 'Case sa nevytvoril, vyberte proces!');
            header('Location:' . ENTRY_SCRIPT_URL . 'petrinet/filter', TRUE, 301);
        }
        if(empty($case->firm)){
            Flow::app()->alertmanager->setAlert('case-error', 'alert-danger', 'Case sa nevytvoril, vyberte firmu!');
            header('Location:' . ENTRY_SCRIPT_URL . 'petrinet/filter', TRUE, 301);
        }
        if(empty($case->name)){
            Flow::app()->alertmanager->setAlert('case-error', 'alert-danger', 'Case sa nevytvoril, zadajte názov case-u!');
            header('Location:' . ENTRY_SCRIPT_URL . 'petrinet/filter', TRUE, 301);
        }
        
        if((Petri_NetModel::model()->findOne('id = ' . $case->id_pn)) == NULL){
            Flow::app()->alertmanager->setAlert('case-error', 'alert-danger', 'Case sa nevytvoril, proces v DB neexistuje');
            header('Location:' . ENTRY_SCRIPT_URL . 'petrinet/filter', TRUE, 301);
        }
        
        $case->timestamp_start = date("Y-m-d H:i:s");
        $case->started_by = Flow::app()->auth->getUserId();
        if($case->save(TRUE)){
        /*Zaznaci pociatocne znackovanie pre case*/
            $caseMarking = new Case_MarkingModel();
            $allPlacesMarking = PlaceModel::model()->findAll('id_pn = ' . $case->id_pn, 'id, initial_marking');
            foreach ($allPlacesMarking as $p){
                $cm = clone($caseMarking);
                $cm->id_case = $case->id;
                $cm->id_place = $p->id;
                $cm->marking = $p->initial_marking;
                $cm->save(TRUE);
            }
            header('Location:' . ENTRY_SCRIPT_URL . 'task/listAvailable', TRUE, 301);
        }
        else{
            Flow::app()->alertmanager->setAlert('case-error', 'alert-danger', 'Case sa nevytvoril, niekde nastala chyba');
            header('Location:' . ENTRY_SCRIPT_URL . 'petrinet/filter', TRUE, 301);
        }
        
    }

    /**
     * Najde a vylistuje vsetky case-y, ktore uz boli ukoncene, tzn. su neaktivne
     */
    public function viewFinished(){
        $query = 'SELECT 
                    `case`.id,
                    `case`.name,
                    `case`.id_pn,
                    `case`.timestamp_start,
                    `case`.timestamp_stop,
                    `case`.`started_by`,
                    `case`.firm,
                    FIRM.firm_name,
                    USERS.first_name,
                    USERS.last_name
                FROM
                    `case`
                        LEFT JOIN
                    FIRM ON FIRM.firm_id = `case`.firm
                                LEFT JOIN
                        USERS ON `case`.started_by = USERS.id
                WHERE
                    `case`.firm IN (SELECT 
                            firm_id
                        FROM
                            USERS_X_FIRM
                        WHERE
                            USERS_X_FIRM.user_id = ' . Flow::app()->auth->getUserId() . ')
                        AND timestamp_stop IS NOT NULL;';
        $result = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);

        $this->render('listallfinished', ['cases' => $result]);
    }
    
    /**
     * Funkcia vyfiltruje z DB vsetky case-y firiem, v ktorych je pouzivatel, ktore este nie su ukoncene
     */
    public function viewActive(){
        $query = 'SELECT 
                    `case`.id,
                    `case`.name,
                    `case`.id_pn,
                    `case`.timestamp_start,
                    `case`.`started_by`,
                    `case`.firm,
                    FIRM.firm_name,
                    USERS.first_name,
                    USERS.last_name
                FROM
                    `case`
                        LEFT JOIN
                    FIRM ON FIRM.firm_id = `case`.firm
                                LEFT JOIN
                        USERS ON `case`.started_by = USERS.id
                WHERE
                    `case`.firm IN (SELECT 
                            firm_id
                        FROM
                            USERS_X_FIRM
                        WHERE
                            USERS_X_FIRM.user_id = ' . Flow::app()->auth->getUserId() . ')
                        AND timestamp_stop IS NULL;';

        $result = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
        
        $this->render('visualizeActive', ['cases' => $result]);
    }
    
    /**
     * Funkcia zaznaci do XML aktualne znackovanie case-u, odosle do editoru, ktore odosle sfarebnene svg
     */
    public function visualizeOne(){
        $id_case = $_POST['case_id'];
        $case = CaseModel::model()->findOne('id = ' . $id_case);
        $pn = Petri_NetModel::model()->findOne('id = ' . $case->id_pn);

        $query = 'SELECT 
                    case_marking.id_place, case_marking.marking, place.id_in_xml
                FROM
                    case_marking
                        LEFT JOIN
                    place ON case_marking.id_place = place.id
                WHERE
                    case_marking.id_case = ' . $id_case . ';';
        $case_marking = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
        
        foreach ($case_marking as $m){
            $arr[$m->id_in_xml] = $m->marking;
        }
        
        $xmlStr = $pn->xml_file;
        $xml = new SimpleXMLElement($xmlStr);

        
        foreach ($xml->place as $p){
            $p->tokens = $arr[(Integer) $p->id];
        }
        
        $str = $xml->asXML();
        /*XML odoslat do editora - ziskat zafarbene SVG*/
        $str = str_replace('<?xml version="1.0"?>', '<?xml version="1.0" encoding="UTF-8"?>', $str);
        $str = str_replace(['\n', '\n\r', '\r\n', PHP_EOL], '', $str);
        
                
        $query = 'SELECT 
                    transition.id_in_xml, USERS.first_name, USERS.last_name
                FROM
                        case_progress
                                LEFT JOIN
                        transition ON case_progress.id_transition = transition.id
                                LEFT JOIN USERS ON case_progress.started_by = USERS.id
                WHERE
                    id_case = ' . $id_case . ' AND timestamp_stop IS NULL;';
        $case_progress = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
        
        $this->render('visualizeOne', ['xml' => $str, 'progress' => $case_progress]);
    }
}
