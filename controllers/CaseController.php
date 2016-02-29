<?php

namespace app\controllers;

use app\models\Case_MarkingModel;
use app\models\CaseModel;
use app\models\Petri_NetModel;
use app\models\PlaceModel;
use app\models\TransitionModel;
use flow\AbstractController;
use flow\Flow;


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
            'logged_in' => ['create'],
        ];
    }
    
    /**
     * Funkcia na vytvorenie noveho case, ulozenie do DB a zaznacenie pociatocneho znackovania do DB
     * TODO: Dorobit hlasenia neuspesneho ulozenia!
     */
    protected function create(){
        
        $case = new CaseModel();
        $case->name = $_POST['name'];
        $case->id_pn = $_POST['pn'];
        
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
    }
    
    public function test(){
        TransitionModel::model()->fireStop(9,2);
    }
}
