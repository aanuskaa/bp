<?php

namespace app\controllers;

use app\models\Case_MarkingModel;
use app\models\CaseModel;
use app\models\Petri_NetModel;
use app\models\PlaceModel;
use flow\AbstractController;
use flow\Flow;
use PDO;


/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
class CaseController extends AbstractController{
    
    /**
     * @inheritdoc 
     */
    protected function accessRules(){
        return [
            'logged_in' => ['create', 'viewActive', 'viewFinished'],
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
        
        if((Petri_NetModel::model()->findOne('id = ' . $case->id_pn)) == NULL){
            echo 'error';
            return;
        }
        
        $case->timestamp_start = date("Y-m-d H:i:s");
        $case->started_by = Flow::app()->auth->getUserId();
        var_dump($case->save(TRUE));
        var_dump($case->getValidationErrors());

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
        header('Location:' . ENTRY_SCRIPT_URL . 'petrinet/filter', TRUE, 301);
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
                            USERS_X_FIRM.user_id = 1)
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
                            USERS_X_FIRM.user_id = 1)
                        AND timestamp_stop IS NULL;';

        $result = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
        
        $this->render('visualizeActive', ['cases' => $result]);
        
    }
}
