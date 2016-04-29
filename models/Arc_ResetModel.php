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
 * @property int id_in_xml
 */
class Arc_ResetModel extends \flow\AbstractModel {
    /**
     * @inheritdoc
     */
    public function table(){
        return 'arc_reset';
    }
    
    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            'required' => 'name',
            'unique'   => 'id',
        ];
    }
}
