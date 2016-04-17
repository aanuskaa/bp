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
            <th>Case</th>
            <th>Začaté</th>
            <th>Začal/a</th>
            <th>Firma</th>
            <th>Pozri aktuálny stav</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data->cases as $case){
            echo '<tr>'
                . '<td>' . $case->name . '</td>'
                . '<td>' . $case->timestamp_start . '</td>'
                . '<td>' . $case->first_name . ' ' .  $case->last_name .  '</td>'
                . '<td>' . $case->firm_name . '</td>'
                . '<td><form method="POST" action="' . ENTRY_SCRIPT_URL . 'case/visualize">'
                    . '<input type="hidden" name="case_id" value="' . $case->id . '">'
                    . '<button type="submit" class="button-blue button-blue-full"><i class="icon icon-informations"></i></button>'
                . '</form></td>'
                . '</tr>';
        }
        ?>
    </tbody>
</table>
