<?php

namespace app\models;

use flow\AbstractModel;
use flow\Flow;
use PDO;


/**
 * Model prechodu
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.market/
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
        $places = ArcModel::model()->findAll('arcs.`to` = ' . $transition_id . ' AND case_marking.id_case = ' . $case_id, 
                'case_marking.`id`, case_marking.`id_place`, case_marking.`marking`', 
                'LEFT JOIN case_marking ON arcs.`from` = case_marking.id_place');
        $place = new Case_MarkingModel();
        
        $placesArr = [];
        foreach ($places as $p){
            $placesArr[$p->id_place] = clone($place);
            $placesArr[$p->id_place]->id = $p->id;
            $placesArr[$p->id_place]->marking = $p->marking;
        }
        
        foreach ($arcs as $arc){
            switch($arc->type){
                case 'PT':
                    if(!isset($placesArr[$arc->from])){
                        //var_dump($places);
                    }
                    if( ($placesArr[$arc->from]->marking - $arc->weight) < 0 ){
                        return false;
                    }
                    break;
                case 'reset':
                    break;
                case 'inhibitor':
                    if( ($placesArr[$arc->from]->marking >= $arc->weight)){
                        return false;
                    }
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
        $reset = [];
        $cnt = 0;
        $arcs = ArcModel::model()->findAll('arcs.`to` = ' . (int)$transition_id, 'arcs.`id_arc`, arcs.`from`, arcs.`weight`, arcs.`type`');
        foreach ($arcs as $arc){
            switch($arc->type){
                case 'PT':
                    $pl = Case_MarkingModel::model()->findOne('id_case =' . (int)$case_id . ' AND id_place =' . $arc->from);
                    var_dump((int)$transition_id);
                    var_dump((int)$case_id);
                    var_dump($arc->from);
                    var_dump($pl);
                    $pl->marking -= $arc->weight;
                    $pl->save(TRUE);
                    break;
                case 'inhibitor':
                case 'reset':
                    $pl = Case_MarkingModel::model()->findOne('id_case =' . $case_id . ' AND id_place =' . $arc->from);
                    $reset[$cnt]->id = $arc->id_arc;
                    $reset[$cnt]->tokens = $pl->marking;
                    $pl->marking = 0;
                    $pl->save(TRUE);
                    $cnt++;
                    break;
            }
        }
        return $reset;
    }
    
    /**
     * Pri cancel vrati tokeny do predchadzajuceho miesta
     * @param type $case_id
     * @param type $transition_id
     */
    public function returnTokens($case_id, $transition_id, $case_progressid){
        $arcs = ArcModel::model()->findAll('arcs.`to` = ' . (int)$transition_id, 'arcs.`id_arc`, arcs.`from`, arcs.`weight`, arcs.`type`');
        foreach ($arcs as $arc){
            switch($arc->type){
                case 'PT':
                    $pl = Case_MarkingModel::model()->findOne('id_case =' . (int)$case_id . ' AND id_place =' . $arc->from);
                    $pl->marking += $arc->weight;
                    $pl->save(TRUE);
                    break;
                case 'inhibitor':
                case 'reset':
                    $query = 'SELECT * FROM `reset_arc_cancel` WHERE arc_id=' . $arc->id_arc .  ' AND case_progress_id = ' . $case_progressid;
                    $result = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
                    $pl = Case_MarkingModel::model()->findOne('id_case =' . $case_id . ' AND id_place =' . $arc->from);
                    $pl->marking = $pl->marking + $result[0]->consumed_tokens;
                    $pl->save(TRUE);
                    $query = 'DELETE FROM `workflow`.`reset_arc_cancel` WHERE arc_id=' . $arc->id_arc .  ' AND case_progress_id = ' . $case_progressid;
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
    
    

