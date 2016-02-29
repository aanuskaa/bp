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
 * @property int initial_marking
 * @property int id_pn
 * @property int id_in_xml
 */
class PlaceModel  extends \flow\AbstractModel{
    
    
    /**
     * @inheritdoc
     */
    public function table(){
        return 'place';
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
    
        
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
