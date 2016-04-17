<?php

/**
 * Zobrazi vsetky ulohy, ktore pouzivatel uz ukoncil
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
?>
<table>
    <thead>
        <tr>
            <th>Úloha</th>
            <th>Začatá</th>
            <th>Ukončená</th>
            <th>Case</th>
            <th>Firma</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data->tasks as $task){
            echo '<tr>'
                    .'<td>' . $task->task_name.'</td>'
                    .'<td>' . $task->timestamp_start.'</td>'
                    .'<td>' . $task->timestamp_stop.'</td>'
                    .'<td>' . $task->case_name.'</td>'
                    .'<td>' . $task->firm_name.'</td>'
                .'</tr>';
        }?>
    </tbody>
</table>