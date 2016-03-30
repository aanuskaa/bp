<?php

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */

//var_dump($data->tasks);
?>
<table>
    <thead>
        <tr>
            <th>Nazov case</th>
            <th>Task</th>
            <th>Zacate</th>
            <th>Ukoncit</th>
            <th>Opust task</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data->tasks as $task){
            echo '<tr><td>' . $task->case_name . '</td>',
                '<td>' . $task->transition_name . '</td>',
                '<td>' . $task->timestamp_start . '</td>',
                    '<td>',
                    '<form action="' . ENTRY_SCRIPT_URL. 'task/finish" method="POST">',
                    '<button type="submit">Dokonci</button>',
                    '<input type="hidden" name="task" value="' .  $task->id . ',' . $task->id_case . ',' . $task->id_transition . '"/>',
                    '</form>',
                    '</td>',
                    '<td>',
                    '<form action="' . ENTRY_SCRIPT_URL. 'task/cancel" method="POST">',
                    '<button type="submit">Cancel</button>',
                    '<input type="hidden" name="cancel" value="' .  $task->id . ',' . $task->id_case . ',' . $task->id_transition . '"/>',
                    '</form>',
                    '</td></tr>';
        }
        ?>
    </tbody>
</table>