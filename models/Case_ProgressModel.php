<?php

namespace app\models;

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.market/
 * @version    1.0.0
 */
class Case_ProgressModel extends \flow\AbstractModel{

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            'required' => 'id_case, id_transition, timestamp_start, started_by',
            'unique'   => 'id',
        ];        
    }

    public function table() {
        return 'case_progress';
    }
    
    /**
     * @inheritdoc
     * @return Case_ProgressModel
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
