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
 * @property int from
 * @property int to
 * @property int weight
 * @property int id_in_xml
 */
class Arc_TPModel extends \flow\AbstractModel{
    /**
     * @inheritdoc
     */
    public function table(){
        return 'arc_TP';
    }
    
    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            'required' => 'from, to, weight, id_in_xml',
            'unique'   => 'id',
        ];
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
