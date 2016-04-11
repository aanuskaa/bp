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
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
class ApiController extends AbstractController{
    
    /**
     * 
     */
    public function petrinetPost(){
        $teststr = '{"places":[{"id_in_xml":0,"name":"m1","initial_marking":1},{"id_in_xml":1,"name":"m2","initial_marking":0},{"id_in_xml":2,"name":"m3","initial_marking":0},{"id_in_xml":3,"name":"m4","initial_marking":0},{"id_in_xml":4,"name":"m5","initial_marking":0},{"id_in_xml":5,"name":"m6","initial_marking":0},{"id_in_xml":6,"name":"m7","initial_marking":0},{"id_in_xml":7,"name":"m8","initial_marking":0}],"transitions":[{"id_in_xml":8,"name":"p1"},{"id_in_xml":9,"name":"p2"},{"id_in_xml":10,"name":"p3"},{"id_in_xml":11,"name":"p4"},{"id_in_xml":12,"name":"p5"},{"id_in_xml":13,"name":"p6"},{"id_in_xml":14,"name":"p7"}],"arcs":[{"id_in_xml":15,"type":"regular","sourceId":0,"destinationId":8,"weight":1},{"id_in_xml":16,"type":"regular","sourceId":8,"destinationId":1,"weight":1},{"id_in_xml":17,"type":"regular","sourceId":1,"destinationId":9,"weight":1},{"id_in_xml":18,"type":"regular","sourceId":9,"destinationId":2,"weight":2},{"id_in_xml":19,"type":"regular","sourceId":2,"destinationId":10,"weight":1},{"id_in_xml":20,"type":"regular","sourceId":2,"destinationId":11,"weight":1},{"id_in_xml":21,"type":"regular","sourceId":10,"destinationId":3,"weight":1},{"id_in_xml":22,"type":"regular","sourceId":11,"destinationId":4,"weight":1},{"id_in_xml":23,"type":"regular","sourceId":3,"destinationId":12,"weight":1},{"id_in_xml":24,"type":"regular","sourceId":4,"destinationId":13,"weight":1},{"id_in_xml":25,"type":"regular","sourceId":13,"destinationId":6,"weight":1},{"id_in_xml":26,"type":"regular","sourceId":12,"destinationId":5,"weight":1},{"id_in_xml":27,"type":"regular","sourceId":5,"destinationId":14,"weight":1},{"id_in_xml":28,"type":"regular","sourceId":6,"destinationId":14,"weight":1},{"id_in_xml":29,"type":"regular","sourceId":14,"destinationId":7,"weight":1}],"xml_name":"sietka.xml"}';
        //$teststr = '{"places":[{"id_in_xml":0,"name":"","initial_marking":1},{"id_in_xml":2,"name":"","initial_marking":0},{"id_in_xml":5,"name":"","initial_marking":0},{"id_in_xml":12,"name":"","initial_marking":0},{"id_in_xml":17,"name":"","initial_marking":0}],"transitions":[{"id_in_xml":1,"name":"prechod1"},{"id_in_xml":8,"name":"prechod2"},{"id_in_xml":9,"name":"prechod3"},{"id_in_xml":15,"name":"prechod4"}],"arcs":[{"id_in_xml":3,"type":"regular","sourceId":0,"destinationId":1,"weight":1},{"id_in_xml":4,"type":"regular","sourceId":1,"destinationId":2,"weight":1},{"id_in_xml":7,"type":"regular","sourceId":1,"destinationId":5,"weight":1},{"id_in_xml":10,"type":"regular","sourceId":2,"destinationId":8,"weight":1},{"id_in_xml":11,"type":"regular","sourceId":5,"destinationId":9,"weight":1},{"id_in_xml":13,"type":"regular","sourceId":8,"destinationId":12,"weight":1},{"id_in_xml":14,"type":"regular","sourceId":9,"destinationId":12,"weight":1},{"id_in_xml":16,"type":"regular","sourceId":12,"destinationId":15,"weight":2},{"id_in_xml":18,"type":"regular","sourceId":15,"destinationId":17,"weight":1}],"xml_name":"sietnaukazku.xml"}';
        //$teststr = '{"places":[{"id_in_xml":0,"name":"","initial_marking":0},{"id_in_xml":2,"name":"","initial_marking":0},{"id_in_xml":5,"name":"","initial_marking":0},{"id_in_xml":12,"name":"","initial_marking":2},{"id_in_xml":17,"name":"","initial_marking":0}],"transitions":[{"id_in_xml":1,"name":"prechod1"},{"id_in_xml":8,"name":"prechod2"},{"id_in_xml":9,"name":"prechod3"},{"id_in_xml":15,"name":"prechod4"}],"arcs":[{"id_in_xml":3,"type":"regular","sourceId":0,"destinationId":1,"weight":1},{"id_in_xml":4,"type":"regular","sourceId":1,"destinationId":2,"weight":1},{"id_in_xml":7,"type":"regular","sourceId":1,"destinationId":5,"weight":1},{"id_in_xml":10,"type":"regular","sourceId":2,"destinationId":8,"weight":1},{"id_in_xml":11,"type":"regular","sourceId":5,"destinationId":9,"weight":1},{"id_in_xml":13,"type":"regular","sourceId":8,"destinationId":12,"weight":1},{"id_in_xml":14,"type":"regular","sourceId":9,"destinationId":12,"weight":1},{"id_in_xml":16,"type":"regular","sourceId":12,"destinationId":15,"weight":2},{"id_in_xml":18,"type":"regular","sourceId":15,"destinationId":17,"weight":1}],"xml_name":"sietnaukazku.xml"}';
        //$teststr = '{"places":[{"id_in_xml":0,"name":"","initial_marking":1},{"id_in_xml":2,"name":"","initial_marking":0},{"id_in_xml":5,"name":"","initial_marking":0},{"id_in_xml":12,"name":"","initial_marking":0},{"id_in_xml":17,"name":"","initial_marking":0}],"transitions":[{"id_in_xml":1,"name":"prechod1"},{"id_in_xml":8,"name":"prechod2"},{"id_in_xml":9,"name":"prechod3"},{"id_in_xml":15,"name":"prechod3"}],"arcs":[{"id_in_xml":3,"type":"regular","sourceId":0,"destinationId":1,"weight":1},{"id_in_xml":4,"type":"regular","sourceId":1,"destinationId":2,"weight":1},{"id_in_xml":7,"type":"regular","sourceId":1,"destinationId":5,"weight":1},{"id_in_xml":10,"type":"regular","sourceId":2,"destinationId":8,"weight":1},{"id_in_xml":11,"type":"regular","sourceId":5,"destinationId":9,"weight":1},{"id_in_xml":13,"type":"regular","sourceId":8,"destinationId":12,"weight":1},{"id_in_xml":14,"type":"regular","sourceId":9,"destinationId":12,"weight":1},{"id_in_xml":16,"type":"regular","sourceId":12,"destinationId":15,"weight":1},{"id_in_xml":18,"type":"regular","sourceId":15,"destinationId":17,"weight":1}],"xml_name":"newNet.xml"}';
        //$teststr = '{"places":[{"id_in_xml":0,"name":"miesto1","initial_marking":2},{"id_in_xml":1,"name":"miesto2","initial_marking":0},{"id_in_xml":2,"name":"miesto3","initial_marking":0},{"id_in_xml":3,"name":"miesto4","initial_marking":1},{"id_in_xml":4,"name":"miesto5","initial_marking":1},{"id_in_xml":5,"name":"miesto6","initial_marking":0},{"id_in_xml":6,"name":"miesto8","initial_marking":0},{"id_in_xml":7,"name":"miesto7","initial_marking":1},{"id_in_xml":8,"name":"miesto10","initial_marking":0},{"id_in_xml":34,"name":"miesto11","initial_marking":0},{"id_in_xml":35,"name":"miesto9","initial_marking":0}],"transitions":[{"id_in_xml":9,"name":"prechod1"},{"id_in_xml":10,"name":"prechod2"},{"id_in_xml":11,"name":"prechod3"},{"id_in_xml":12,"name":"prechod4"},{"id_in_xml":13,"name":"prechod5"},{"id_in_xml":14,"name":"prechod7"},{"id_in_xml":31,"name":"prechod6"},{"id_in_xml":32,"name":"prechod8"}],"arcs":[{"id_in_xml":15,"type":"regular","sourceId":0,"destinationId":9,"weight":2},{"id_in_xml":16,"type":"regular","sourceId":9,"destinationId":1,"weight":1},{"id_in_xml":17,"type":"regular","sourceId":9,"destinationId":2,"weight":1},{"id_in_xml":18,"type":"regular","sourceId":2,"destinationId":11,"weight":1},{"id_in_xml":19,"type":"regular","sourceId":1,"destinationId":10,"weight":1},{"id_in_xml":20,"type":"regular","sourceId":10,"destinationId":3,"weight":1},{"id_in_xml":21,"type":"regular","sourceId":11,"destinationId":4,"weight":1},{"id_in_xml":22,"type":"regular","sourceId":4,"destinationId":12,"weight":1},{"id_in_xml":23,"type":"regular","sourceId":3,"destinationId":12,"weight":1},{"id_in_xml":24,"type":"regular","sourceId":12,"destinationId":5,"weight":1},{"id_in_xml":25,"type":"regular","sourceId":5,"destinationId":13,"weight":1},{"id_in_xml":26,"type":"regular","sourceId":13,"destinationId":7,"weight":2},{"id_in_xml":27,"type":"regular","sourceId":13,"destinationId":6,"weight":1},{"id_in_xml":28,"type":"regular","sourceId":6,"destinationId":14,"weight":1},{"id_in_xml":29,"type":"regular","sourceId":7,"destinationId":14,"weight":1},{"id_in_xml":30,"type":"regular","sourceId":14,"destinationId":8,"weight":1},{"id_in_xml":37,"type":"regular","sourceId":7,"destinationId":31,"weight":1},{"id_in_xml":38,"type":"regular","sourceId":31,"destinationId":35,"weight":1},{"id_in_xml":39,"type":"regular","sourceId":35,"destinationId":32,"weight":1},{"id_in_xml":40,"type":"regular","sourceId":8,"destinationId":32,"weight":1},{"id_in_xml":41,"type":"regular","sourceId":32,"destinationId":34,"weight":1}],"xml_name":"newNet.xml"}';
        //$teststr = '{"places":[{"id_in_xml":0,"name":"miesto1","initial_marking":2},{"id_in_xml":1,"name":"miesto2","initial_marking":0},{"id_in_xml":2,"name":"miesto3","initial_marking":0},{"id_in_xml":3,"name":"miesto4","initial_marking":0},{"id_in_xml":4,"name":"miesto5","initial_marking":0},{"id_in_xml":5,"name":"miesto6","initial_marking":0},{"id_in_xml":6,"name":"miesto8","initial_marking":0},{"id_in_xml":7,"name":"miesto7","initial_marking":0},{"id_in_xml":8,"name":"miesto10","initial_marking":0},{"id_in_xml":34,"name":"miesto11","initial_marking":0},{"id_in_xml":35,"name":"miesto9","initial_marking":0}],"transitions":[{"id_in_xml":9,"name":"prechod1"},{"id_in_xml":10,"name":"prechod2"},{"id_in_xml":11,"name":"prechod3"},{"id_in_xml":12,"name":"prechod4"},{"id_in_xml":13,"name":"prechod5"},{"id_in_xml":14,"name":"prechod7"},{"id_in_xml":31,"name":"prechod6"},{"id_in_xml":32,"name":"prechod8"}],"arcs":[{"id_in_xml":15,"type":"regular","sourceId":0,"destinationId":9,"weight":2},{"id_in_xml":16,"type":"regular","sourceId":9,"destinationId":1,"weight":1},{"id_in_xml":17,"type":"regular","sourceId":9,"destinationId":2,"weight":1},{"id_in_xml":18,"type":"regular","sourceId":2,"destinationId":11,"weight":1},{"id_in_xml":19,"type":"regular","sourceId":1,"destinationId":10,"weight":1},{"id_in_xml":20,"type":"regular","sourceId":10,"destinationId":3,"weight":1},{"id_in_xml":21,"type":"regular","sourceId":11,"destinationId":4,"weight":1},{"id_in_xml":22,"type":"regular","sourceId":4,"destinationId":12,"weight":1},{"id_in_xml":23,"type":"regular","sourceId":3,"destinationId":12,"weight":1},{"id_in_xml":24,"type":"regular","sourceId":12,"destinationId":5,"weight":1},{"id_in_xml":25,"type":"regular","sourceId":5,"destinationId":13,"weight":1},{"id_in_xml":26,"type":"regular","sourceId":13,"destinationId":7,"weight":2},{"id_in_xml":27,"type":"regular","sourceId":13,"destinationId":6,"weight":1},{"id_in_xml":28,"type":"regular","sourceId":6,"destinationId":14,"weight":1},{"id_in_xml":29,"type":"regular","sourceId":7,"destinationId":14,"weight":1},{"id_in_xml":30,"type":"regular","sourceId":14,"destinationId":8,"weight":1},{"id_in_xml":37,"type":"regular","sourceId":7,"destinationId":31,"weight":1},{"id_in_xml":38,"type":"regular","sourceId":31,"destinationId":35,"weight":1},{"id_in_xml":39,"type":"regular","sourceId":35,"destinationId":32,"weight":1},{"id_in_xml":40,"type":"regular","sourceId":8,"destinationId":32,"weight":1},{"id_in_xml":41,"type":"regular","sourceId":32,"destinationId":34,"weight":1}],"xml_name":"newNet.xml"}';
        //$teststr = '{"places":[{"id_in_xml":0,"name":"p1","initial_marking":5},{"id_in_xml":4,"name":"p2","initial_marking":0}],"transitions":[{"id_in_xml":1,"name":"t1"},{"id_in_xml":2,"name":"t2"},{"id_in_xml":3,"name":"t3"}],"arcs":[{"id_in_xml":5,"type":"regular","sourceId":0,"destinationId":1,"weight":1},{"id_in_xml":6,"type":"reset","sourceId":0,"destinationId":2,"weight":1},{"id_in_xml":7,"type":"inhibitor","sourceId":0,"destinationId":3,"weight":11},{"id_in_xml":8,"type":"regular","sourceId":1,"destinationId":4,"weight":2},{"id_in_xml":9,"type":"regular","sourceId":2,"destinationId":4,"weight":2},{"id_in_xml":10,"type":"regular","sourceId":3,"destinationId":4,"weight":2}],"xml_name":"newNet.xml"}';
        //json_decode($_POST['pn_json']);
        $json_pn = json_decode($teststr);
        
        $petri_net = new Petri_NetModel();
        $petri_net->xml_file = $json_pn->xml_name;
        $petri_net->name = str_replace('.xml', '', $json_pn->xml_name);
        $petri_net->created_by = Flow::app()->auth->getUserId();
        $petri_net->created_date =  date("Y-m-d H:i:s");
        var_dump($petri_net->save(TRUE));
        
        $place_model = new PlaceModel();
        foreach ($json_pn->places as $k => $place){
            $p = clone $place_model;
            $p->setAttributes($place);
            $p->id_pn = $petri_net->id;
            $p->save(TRUE);
            $json_pn->places[$k]->id = $p->id;
        }
        
        $transition_model = new TransitionModel();
        foreach ($json_pn->transitions as $k => $transition){
            $t = clone $transition_model;
            $t->setAttributes($transition);
            $t->id_pn = $petri_net->id;
            $t->save(TRUE);
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
                        $r->getValidationErrors();
                    }
                    else{
                        $r = clone $tp;
                        $r->setAttributes($arc);
                        $r->from = $transitions[$arc['sourceId']];
                        $r->to = $places[$arc['destinationId']];
                        var_dump($r->save(TRUE));
                        $r->getValidationErrors();
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
                    $r->getValidationErrors();
                    break;
                default:
                    break;
            }
        }
    }
}
