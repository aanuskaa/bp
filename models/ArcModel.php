<?php

namespace app\models;

use flow\AbstractModel;
use flow\Flow;

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
class ArcModel extends AbstractModel{
    
    public function rules() {
        
    }

    public function table() {
        return '';
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function findAll($condition = '', $columns = '*', $joins = '', $index = '') {
        
        $query = 
                "SELECT {$columns} FROM 
                (
                    SELECT id as id_arc, `from`, `to`, weight, 'PT' as `type` FROM arc_PT
                    UNION ALL SELECT id as id_arc, `from`, `to`, weight, 'inhibitor' as `type` FROM arc_inhibitor
                    UNION ALL SELECT id as id_arc, `from`, `to`, 1 as weight, 'reset' as `type` FROM arc_reset
                ) arcs 
                {$joins}
                WHERE {$condition}";
                
        //var_dump($query);
        return Flow::app()->pdo->query($query)->fetchAll();
    }
}
