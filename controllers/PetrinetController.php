<?php

namespace app\controllers;

use app\models\PetrinetModel;
use flow\Flow;

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
class PetrinetController extends \flow\AbstractController{
    
    public function create(){
        $this->render('petrinet');
    }
    
    public function listAll(){
        $nets = \app\models\Petri_NetModel::model()->findAll();
        $this->render('listall', ['nets' => $nets]);
    }
    
}
