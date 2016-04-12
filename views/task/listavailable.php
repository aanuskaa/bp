<?php

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
//var_dump($data->firm_cases);



?>
<table>
    <thead>
        <tr>
            <th>Referencia</th>
            <th>Firma</th>
            <th>Case</th>
            <th>Task</th>
            <th>Rola</th>
            <th>Vezmi</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data->firm_cases as $id_firm => $firm){
            foreach ($firm['cases'] as $id_case => $case){
                foreach ($case['tasks'] as $id_task => $task){
                    if($task['reference'] != NULL){
                        echo '<tr><td id="reference_check"><i class="icon  icon-tick"></i></td>';
                    }
                    else{
                        echo '<tr><td>&nbsp;</td>';
                    }
                    echo '<td>' . $firm['name'] . '</td>',
                        '<td>' . $case['name'] . '</td>',
                        '<td>' . $task['name'] . '</td>';
                    if(isset($task['role_name'])){
                        echo '<td>' . $task['role_name'] . '</td>';
                    }
                    else{
                        echo '<td>Bez naväzujúcej role</td>';
                    }
                    echo '<td>',
                    '<form action="' . ENTRY_SCRIPT_URL. 'task/take" method="POST">',
                    '<button type="submit" class="button-blue button-grey-full">Vezmi task</button>',
                    '<input type="hidden" name="task" value="'  .$id_case . ',' . $id_task . '"/>',
                    '</form>',
                    '</td></tr>';
                }
            }
        }
        ?>
    </tbody>
</table>