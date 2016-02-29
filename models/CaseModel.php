<?php

namespace app\models;

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
 * @property string timestamp_start
 * @property string timestamp_stop
 * @property int started_by
 */
class CaseModel extends \flow\AbstractModel {
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            'required' => 'name, id_pn, timestamp_start, started_by',
            'unique'   => 'id',
        ];        
    }

    /**
     * @inheritdoc
     */
    public function table() {
        return 'case';
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
}
