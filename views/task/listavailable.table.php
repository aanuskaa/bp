<table class="mt">
    <thead>
        <tr>
            <th>Reference</th>
            <th>Firm</th>
            <th>Case</th>
            <th>Task</th>
            <th>Role</th>
            <th>Take task</th>
        </tr>
    </thead>
    <tbody id="tasks">
        <?php 
        $all_firms = '';
        foreach ($data->firm_cases as $id_firm => $firm){
            foreach ($firm['cases'] as $id_case => $case){
                foreach ($case['tasks'] as $id_task => $task){
                    if($task['reference'] != NULL){
                        $all_firms .= '<tr><td id="reference_check"><i class="icon icon-warning"></i></td>';
                    }
                    else{
                        $all_firms .= '<tr><td>&nbsp;</td>';
                    }
                    $all_firms .= '<td>' . $firm['name'] . '</td><td>' . $case['name'] . '</td><td>' . $task['name'] . '</td>';
                    if(isset($task['role_name'])){
                        $all_firms .= '<td>' . $task['role_name'] . '</td>';
                    }
                    else{
                        $all_firms .=  '<td>No role assigned</td>';
                    }
                    $all_firms .=  '<td>'
                    . '<form action="' . ENTRY_SCRIPT_URL. 'task/take" method="POST">'
                    . '<button type="submit" class="button-blue button-blue-full">Take this task</button>'
                    . '<input type="hidden" name="task" value="'  .$id_case . ',' . $id_task . '"/>'
                    . '</form>'
                    . '</td></tr>';
                }
            }
        }
        echo $all_firms;
        ?>
    </tbody>
</table>