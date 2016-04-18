<?php

/**
 * Zobrazi svg aktualneho stavu case-u
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */

?>
  
<script>
    function addProgress(idTransition, username){
        var x = $("#" + (idTransition-1)).attr("x");
        var y = $("#" + (idTransition-1)).attr("y");
        var width = $("#" + (idTransition-1)).attr("width") -10;
        var height = $("#" + (idTransition-1)).attr("height") -10;
        var svg = document.getElementsByTagName('svg')[0];
        var link = 'http://www.w3.org/2000/svg';
        
        var text_id = 'transition-name' + (idTransition-1);
        console.log(text_id);
        
        var icon = document.createElementNS(link, 'image');
        var set = document.createElementNS(link, 'set');
        var title = document.createElementNS(link, 'title');
        
        icon.setAttributeNS(link,'height',height+11);
        icon.setAttributeNS(link,'width',width+10);
        icon.setAttributeNS(link,'x',x);
        icon.setAttributeNS(link,'y',y);
        icon.setAttributeNS(link,'visibility', 'visible')+ 5;
        icon.setAttributeNS('http://www.w3.org/1999/xlink','href','<?php echo ROOT_URL?>style/tool.gif');
        icon.setAttributeNS(link,'id','transition-icon' + (idTransition-1));
        
        set.setAttributeNS(link,'attributeName','fill-opacity');
        set.setAttributeNS(link,'to','0.9');
        set.setAttributeNS(link,'begin',(idTransition-1) + ".mouseover");
        set.setAttributeNS(link,'end', (idTransition-1) + ".mouseout");
        
        title.textContent = 'Vykonáva: ' + username;
        
        svg.appendChild(icon);
        
        icon.appendChild(title);
        
        $(svg).html($(svg).html());
    }
    
</script>
    
<?php
echo $data->svg;

foreach ($data->progress as $p){
    $user_name = $p->last_name . ' ' . $p->first_name;
    echo '<script>addProgress(' . $p->id_in_xml . ', "' . $user_name . '");</script>';
}


    
    