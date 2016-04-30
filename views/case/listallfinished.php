<?php

/**
 * Zobrazi vsetky ukoncene case - y v zavislosti na pouzivatelovi a jeho firmach
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */

//var_dump($data->cases);?>

<table>
    <thead>
        <tr>
            <th>Case</th>
            <th>Started</th>
            <th>Finished</th>
            <th>Started by</th>
            <th>Firm</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data->cases as $case){
            echo '<tr>'
                . '<td>' . $case->name . '</td>'
                . '<td>' . $case->timestamp_start . '</td>'
                . '<td>' . $case->timestamp_stop . '</td>'
                . '<td>' . $case->first_name . ' ' .  $case->last_name .  '</td>'
                . '<td>' . $case->firm_name . '</td>'
                . '</tr>';
        }
        ?>
    </tbody>
</table>