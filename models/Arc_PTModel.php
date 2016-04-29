<?php

namespace app\models;

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.market/
 * @version    1.0.0
 * 
 * @property int id
 * @property int from
 * @property int to
 * @property int weight
 * @property int id_in_xml
 */
class Arc_PTModel  extends \flow\AbstractModel{
    /**
     * @inheritdoc
     */
    public function table(){
        return 'arc_PT';
    }
    
    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            'required' => 'from, to, weight',
            'unique'   => 'id',
        ];
    }
}
