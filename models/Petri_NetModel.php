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
 * @property string xml_file
 */
class Petri_NetModel extends \flow\AbstractModel{

    /**
     * @inheritdoc
     * @return Petri_NetModel
     */
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    
    /**
     * @inheritdoc
     */
    public function table(){
        return 'petri_net';
    }
    
    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            'required' => 'name, xml_file',
            'unique'   => 'id',
        ];
    }
}
