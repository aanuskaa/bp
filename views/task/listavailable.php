<?php

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
//var_dump($data->cases);



?>
<table>
    <thead>
        <tr>
            <th>ID Case</th>
            <th>Case name</th>
            <th>Task</th>
            <th>Vezmi</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data->cases as $case){
            foreach ($case->tasks as $task){
                echo '<tr><td>' . $case->id . '</td>',
                    '<td>' . $case->name . '</td>',
                    '<td>' . $task->name . '</td>',
                        '<td>',
                        '<form action="' . ENTRY_SCRIPT_URL. 'task/take" method="POST">',
                        '<button type="submit">Vezmi task</button>',
                        '<input type="hidden" name="task" value="' . $case->id . ',' . $task->id . '"/>',
                        '</form>',
                        '</td></tr>';
            }
        }
        ?>
    </tbody>
</table>