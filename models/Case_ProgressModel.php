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
    

}
