<?php
$xml = $data->xml;
?>

<div class="svgDiv">
    <svg id="netDrawArea" >
    
    </svg>
</div>
<script>var xml = '<?php echo $xml; ?>';
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="<?php echo ROOT_URL?>editor/tomas/scriptpreanickudusicku.js"></script>
<script> 
    importNet($.parseXML(xml)); 
    setFireModeTransitionColors();
    var svg = createSVGSource();
    console.log(xml);
</script>
