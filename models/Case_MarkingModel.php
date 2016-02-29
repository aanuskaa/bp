<?php

namespace app\models;

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
class Case_MarkingModel extends \flow\AbstractModel{
    
    private $testMarking;

    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            'required' => 'id_case, id_place, marking',
            'unique'   => 'id',
        ];
    }

    public function table() {
        return 'case_marking';
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    

}
