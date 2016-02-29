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
class Arc_InhibitorModel extends \flow\AbstractModel {
    /**
     * @inheritdoc
     */
    public function table(){
        return 'arc_inhibitor';
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
}
