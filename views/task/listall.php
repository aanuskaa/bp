<?php

/**
 * Zobrazi vsetky aktualne ulohy pouzivatela
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
            <th>Case</th>
            <th>Task</th>
            <th>Začaté</th>
            <th>Formulár</th>
            <th>Ukončiť</th>
            <th>Opusť task</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($data->tasks as $task){
            echo '<tr><td>' . $task->case_name . '</td>',
                '<td>' . $task->transition_name . '</td>',
                '<td>' . $task->timestamp_start . '</td>',
                '<td><button class="button-grey button-grey-full">Zobraz</button></td>',
                    '<td>',
                    '<form action="' . ENTRY_SCRIPT_URL. 'task/finish" method="POST">',
                    '<button type="submit" class="button-blue button-grey-full">Dokonči</button>',
                    '<input type="hidden" name="task" value="' .  $task->id . ',' . $task->id_case . ',' . $task->id_transition . '"/>',
                    '</form>',
                    '</td>',
                    '<td>',
                    '<form action="' . ENTRY_SCRIPT_URL. 'task/cancel" method="POST">',
                    '<button type="submit" class="button-purple button-grey-full">Cancel</button>',
                    '<input type="hidden" name="cancel" value="' .  $task->id . ',' . $task->id_case . ',' . $task->id_transition . '"/>',
                    '</form>',
                    '</td></tr>';
        }
        ?>
    </tbody>
</table>