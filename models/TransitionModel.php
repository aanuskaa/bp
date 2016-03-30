<?php

namespace app\models;

use flow\AbstractModel;


/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 * 
 * @property int id
 * @property string name
 * @property int id_pn
 * @property int id_in_xml
 */
class TransitionModel extends AbstractModel{
    
    /**
     * @inheritdoc
     */
    public function table(){
        return 'transition';
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            'required' => 'id_pn, id_in_xml',
            'unique'   => 'id',
        ];
    }

    /**
     * Zisti, ci prechod v danom case je v danom case spustitelny
     * @return boolean
     */
    public function isEnabled($case_id,  $transition_id){
        $arcs = ArcModel::model()->findAll('arcs.`to` = ' . $transition_id);
        $places = ArcModel::model()->findAll('arcs.`to` = ' . $transition_id . ' AND case_marking.id_case = ' . $case_id, 'case_marking.`id`, case_marking.`id_place`, case_marking.`marking`', 'LEFT JOIN case_marking ON arcs.`from` = case_marking.id_place');
        //TODO: odkonzultava spravnost SELECTU (vyzera ze funguje)
        $place = new Case_MarkingModel();
        
        $placesArr = [];
        foreach ($places as $p){
            $placesArr[$p->id_place] = clone($place);
            $placesArr[$p->id_place]->id = $p->id;
            $placesArr[$p->id_place]->marking = $p->marking;
        }
        
        //TODO: Dorobit logiku inhibitor hran!
        foreach ($arcs as $arc){
            switch($arc->type){
                case 'PT':
                    if( ($placesArr[$arc->from]->marking - $arc->weight) < 0 ){
                        return false;
                    }
                    break;
                case 'reset':
                    if( ($placesArr[$arc->from]->marking >= $arc->weight)){
                        return false;
                    }
                    break;
                case 'inhibitor':
                    break;
            }
        }
        return true;
    }
    
    /**
     * Najde vsetky spustitelne prechody v danom case
     * @return [] prechodov, ktore su spustitelne
     */
    public function findAllEnabled($case){
        
        $transitions = $this->findAll('id_pn = ' . $case->id_pn, '`id`, `name`');
        $transition = new TransitionModel();
        
        $transitionsArr = [];
        foreach ($transitions as $t){
            $transitionsArr[$t->id] = clone($transition);
            $transitionsArr[$t->id]->name = $t->name;
            $transitionsArr[$t->id]->id = $t->id;
        }
        
        $places = Case_MarkingModel::model()->findAll('id_case =' . $case->id);
        $place = new Case_MarkingModel();
        
        $placesArr = [];
        foreach ($places as $p){
            $placesArr[$p->id_place] = clone($place);
            $placesArr[$p->id_place]->id = $p->id;
            $placesArr[$p->id_place]->marking = $p->marking;
        }
        
        $arcs = ArcModel::model()->findAll('transition.id_pn = ' . $case->id_pn, 'arcs.*', 'LEFT JOIN transition ON arcs.`to` = transition.id');
        
        foreach ($arcs as $arc){
            switch($arc->type){
                case 'PT':
                    if( ($placesArr[$arc->from]->marking - $arc->weight) < 0 ){
                        unset($transitionsArr[$arc->to]);
                    }
                    break;
                case 'inhibitor':
                    if( ($placesArr[$arc->from]->marking >= $arc->weight)){
                        unset($transitionsArr[$arc->to]);
                    }
                    break;
                case 'reset':
                    break;
                default :
                    break;
            }
        }
        return $transitionsArr;
    }
    
    /**
     * Začiatočná fáza spustenia prechodu, skonzumuje všetky potrebné tokeny ale ešte nevyprodukuje
     * @param type $transition_id
     * @param type $case_id
     */
    public function fireStart($case_id, $transition_id){
        $arcs = ArcModel::model()->findAll('arcs.`to` = ' . (int)$transition_id, 'arcs.`from`, arcs.`weight`, arcs.`type`');
        foreach ($arcs as $arc){
            switch($arc->type){
                case 'PT':
                    $pl = Case_MarkingModel::model()->findOne('id_case =' . (int)$case_id . ' AND id_place =' . $arc->from);
                    var_dump((int)$transition_id);
                    var_dump((int)$case_id);
                    var_dump($arc->from);
                    var_dump($pl);
                    $pl->marking -= $arc->weight;
                    var_dump($pl->save(TRUE));
                    break;
                case 'inhibitor':
                case 'reset':
                    $pl = Case_MarkingModel::model()->findOne('id_case =' . $case_id . ' AND id_place =' . $arc->from);
                    $pl->marking = 0;
                    var_dump($pl->save(TRUE));
                    break;
                
            }
        }
    }
    
    public function returnTokens($case_id, $transition_id){
        $arcs = ArcModel::model()->findAll('arcs.`to` = ' . (int)$transition_id, 'arcs.`from`, arcs.`weight`, arcs.`type`');
        foreach ($arcs as $arc){
            switch($arc->type){
                case 'PT':
                    $pl = Case_MarkingModel::model()->findOne('id_case =' . (int)$case_id . ' AND id_place =' . $arc->from);
                    $pl->marking += $arc->weight;
                    $pl->save(TRUE);
                    break;
                case 'inhibitor':
                case 'reset':
                    $pl = Case_MarkingModel::model()->findOne('id_case =' . $case_id . ' AND id_place =' . $arc->from);
                    $pl->marking = 0;
                    var_dump($pl->save(TRUE));
                    break;
                
            }
        }
    }
    
    /**
     * Konečná fáza spustenia prechodu -> vyprodukovanie tokenov
     * @param type $transition_id
     * @param type $case_id
     */
    public function fireStop($transition_id, $case_id){
        $arcs = Arc_TPModel::model()->findAll('`from` = ' . $transition_id, '`to`, `weight`');
        foreach ($arcs as $arc){
            $pl = Case_MarkingModel::model()->findOne('id_case =' . $case_id . ' AND id_place =' . $arc->to);
            $pl->marking += $arc->weight;
            var_dump($pl->save(TRUE));
        }
    }
}
    
    

