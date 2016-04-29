<?php

namespace app\controllers;

use app\models\Arc_InhibitorModel;
use app\models\Arc_PTModel;
use app\models\Arc_ResetModel;
use app\models\Arc_TPModel;
use app\models\Petri_NetModel;
use app\models\PlaceModel;
use app\models\TransitionModel;
use flow\AbstractController;
use flow\Flow;

/**
 * Controller sluzi na komunikaciu s pn editorom
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.market/
 * @version    1.0.0
 */
class ApiController extends AbstractController{
    
    /**
     * @inheritdoc 
     */
    protected function accessRules(){
        return [
            'logged_in' => ['petrinetPost', 'saveArcs'],
        ];
    }
    
    /**
     * Ulozenie PN do db pre potreby wf enginu
     */
    public function petrinetPost(){
        $teststr = '{"places":[{"id_in_xml":0,"name":"","initial_marking":2},{"id_in_xml":2,"name":"","initial_marking":0}],"transitions":[{"id_in_xml":1,"name":"inhibitor"},{"id_in_xml":3,"name":"reset"},{"id_in_xml":5,"name":"klasika"}],"arcs":[{"id_in_xml":4,"type":"inhibitor","sourceId":0,"destinationId":1,"weight":1},{"id_in_xml":6,"type":"regular","sourceId":0,"destinationId":5,"weight":1},{"id_in_xml":7,"type":"regular","sourceId":5,"destinationId":2,"weight":1},{"id_in_xml":8,"type":"regular","sourceId":1,"destinationId":2,"weight":1},{"id_in_xml":9,"type":"reset","sourceId":2,"destinationId":3,"weight":1}],"xml_name":"testik.xml"}';
        $json_pn = json_decode($teststr);
        
        $petri_net = new Petri_NetModel();
        $petri_net->xml_file = $json_pn->xml_name;
        $petri_net->name = str_replace('.xml', '', $json_pn->xml_name);
        $petri_net->created_by = Flow::app()->auth->getUserId();
        $petri_net->created_date =  date("Y-m-d H:i:s");
        $petri_net->xml_file =  $xml;
        $petri_net->svg_file =  $svg;
        var_dump($petri_net->save(TRUE));
        
        $place_model = new PlaceModel();
        foreach ($json_pn->places as $k => $place){
            $p = clone $place_model;
            $p->setAttributes($place);
            $p->id_pn = $petri_net->id;
            var_dump($p->save(TRUE));
            $json_pn->places[$k]->id = $p->id;
        }
        
        $transition_model = new TransitionModel();
        foreach ($json_pn->transitions as $k => $transition){
            $t = clone $transition_model;
            $t->setAttributes($transition);
            $t->id_pn = $petri_net->id;
            var_dump($t->save(TRUE));
            $json_pn->transitions[$k]->id = $t->id;
        }
        
        $this->saveArcs(json_encode($json_pn));
        
        //$this->render('');
    }
    
    /**
     * Vyhodnotí typ hrany a na základe toho to uloží do prislúchajúcej tabuľky
     * @param type $json_str
     */
    private function saveArcs($json_str){
        $json = json_decode($json_str, TRUE);
        $pt = new Arc_PTModel();
        $tp = new Arc_TPModel();
        $inhibitor = new Arc_InhibitorModel();
        $reset = new Arc_ResetModel();
        $places = array_column($json['places'], 'id','id_in_xml');
        $transitions = array_column($json['transitions'], 'id', 'id_in_xml');

        foreach ($json['arcs'] as $arc){
            $type = $arc['type'];
            switch($type){
                case 'regular':
                    if(isset($places[$arc['sourceId']])){
                        $r = clone $pt;
                        $r->setAttributes($arc);
                        $r->from = $places[$arc['sourceId']];
                        $r->to = $transitions[$arc['destinationId']];
                        var_dump($r->save(TRUE));
                        var_dump($r->getValidationErrors());
                    }
                    else{
                        $r = clone $tp;
                        $r->setAttributes($arc);
                        $r->from = $transitions[$arc['sourceId']];
                        $r->to = $places[$arc['destinationId']];
                        var_dump($r->save(TRUE));
                        var_dump($r->getValidationErrors());
                    }
                    break;
                case 'reset':
                    $r = clone $reset;
                    $r->setAttributes($arc);
                    $r->from = $places[$arc['sourceId']];
                    $r->to = $transitions[$arc['destinationId']];
                    var_dump($r->save(TRUE));
                    $r->getValidationErrors();
                    break;
                case 'inhibitor':
                    $i = clone $inhibitor;
                    $i->setAttributes($arc);
                    $i->from = $places[$arc['sourceId']];
                    $i->to = $transitions[$arc['destinationId']];
                    var_dump($i->save(TRUE));
                    $i->getValidationErrors();
                    break;
                default:
                    break;
            }
        }
    }
}
