
<!DOCTYPE html>
<html>
<head>  
        <link rel="stylesheet" href="../global/vendor/normalize.css">
        <link rel="stylesheet" href="../global/styles/font-icon.css">
        <link rel="stylesheet" href="../global/styles/global.css">
        <link rel="stylesheet" href="../global/styles/modules.css">
        <link rel="stylesheet" type="text/css" href="sheet.css">
       
        
	<script src="jquery-1.11.3.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script src="script.js"></script>
        
	<title>Petri net modeler</title>
</head>
<body>
    <script>
    
    </script>
    <?php
        include ('../global/modules/header.php');
        
    ?>
	<div class="container-fluid">
            <!--<div class="menu">
                
            </div>-->
		<div class="controlAreaUpped">
                    <ul>
                        <li>
                            <input type="button" id="clearButton" value="clear" onClick="clearNetArea()"/>
                            <label for="clearButton" ><i class="icon icon-cross"></i></label>
                            <div class="tooltip">Clear net area</div>
                        </li>
                        
                        <li>
                            <input type="button" id="saveButton" value="save" onClick="exportAsXML()"/>
                            <label for="saveButton" ><i class="icon icon-xml" ></i></label>
                            <div class="tooltip">Save as XML</div>
                        </li>
                        <li>
                            <input type="button" id="svgSaveButton" value="saveSVG" onClick="saveAsSVG()"/>
                            <label for="svgSaveButton" ><i class="icon icon-svg"></i></label>
                            <div class="tooltip">Save as SVG</div>
                        </li>
                        <li>
                            <input type="button" id="databaseSaveButton" value="saveDatabase" onClick="postDataToDB()"/>
                            <label for="databaseSaveButton" ><i class="icon icon-upload-database"></i></label>
                            <div class="tooltip">Save net into database</div>
                        </li>
                        <li>
                            <input type="button" id="databaseLoadButton" value="loadDatabase" onClick="getDataFromDB()"/>
                            <label for="databaseLoadButton" ><i class="icon icon-download-database"></i></label>
                            <div class="tooltip">Load net from database</div>
                        </li>
                        <li>
                            <input type="button" id="info" value="info" onClick="showInfo()"/>
                            <label for="info" ><i class="icon icon-info"></i></label>
                            <div class="tooltip">Info</div>
                        </li>
                          <li>
                            <input type="button" id="descriptionButton" title="describe net" value="descrube net" onClick="describeNet()"/>
                            <label for="descriptionButton" ><i class="icon icon-edit"></i></label>
                            <div class="tooltip">Describe net</div>
                        </li> 
                        <li>
                            <input type="file" id="inportButton" onchange="openFile(event)"/>
                            <label for="inportButton" ><i class="icon icon-upload"></i></label>
                            <div class="tooltip">Load XML from file</div>
                        </li>
                     
                     
                    </ul>
                    </div>
                    <div class="controlAreaLower">
                    <ul>
                        <li>
                            
                            <input type="radio"
                                        name="netControls"
                                        id="placeRadio"
                                        value="place"
                                        tabindex="1"/>
                            <label for="placeRadio" ><i class="icon icon-place"></i></label>
                            <div class="tooltip">Place</div>
                        </li>
                        <li>
                            <input type="radio"
                                        name="netControls"
                                        id="transitionRadio"
                                        value="transition"
                                        tabindex="2"/>
                            <label for="transitionRadio" ><i class="icon icon-transition"></i></label>
                            <div class="tooltip">Transition</div>
                        </li>
                        <li>
                            <input type="radio"
                                        name="netControls"
                                        id="moveRadio"
                                        value="move"
                                        tabindex="3"/>
                            <label for="moveRadio" ><i class="icon icon-move"></i></label>
                            <div class="tooltip">Move</div>
                        </li>
                        <li>
                            <input type="radio"
                                        name="netControls"
                                        id="removeRadio"
                                        value="remove"
                                        tabindex="4"/>
                            <label for="removeRadio" ><i class="icon icon-trash"></i></label>
                            <div class="tooltip">Remove</div>
                        </li>
                        <li>
                            <input type="radio"
                                        name="netControls"
                                        id="fireRadio"
                                        value="fire"
                                        tabindex="5"/>
                            <label for="fireRadio" ><i class="icon icon-fire"></i></label>
                            <div class="tooltip">Fire</div>
                        </li>
                        <li>
                            <input type="radio"
                                        name="netControls"
                                        id="addTakeTokenRadio"
                                        value="addTake"
                                        tabindex="6"/>
                            <label for="labelRadio" ><i class="icon icon-add_removeToken"></i></label>
                            <div class="tooltip">Add/Remove token</div>
                        </li>
                        <li>
                            <input type="radio"
                                        name="netControls"
                                        id="arcRadio"
                                        value="arc"
                                        tabindex="7"/>
                            <label for="arcRadio" ><i class="icon icon-regular_ARC_arrow"></i></label>
                            <div class="tooltip">Regular Arc</div>
                        </li>
                        <li>
                            <input type="radio"
                                        name="netControls"
                                        id="resetArcRadio"
                                        value="resetArc"
                                        tabindex="8"/>
                            <label for="resetArcRadio" ><i class="icon icon-reset_ARC_arrow"></i></label>
                            <div class="tooltip">Reset Arc</div>
                        </li>
                        <li>
                            <input type="radio"
                                        name="netControls"
                                        id="inhibitorArcRadio"
                                        value="inhibitorArc"
                                        tabindex="9"/>
                            <label for="inhibitorArcRadio" ><i class="icon icon-inhibitor_ARC_arrow"></i></label>
                            <div class="tooltip">Inhibitor Arc</div>
                        </li>
                        <li>
                            <input type="radio"
                                        name="netControls"
                                        id="arcWeightRadio"
                                        value="arcWeight"
                                        tabindex="10"/>
                            <label for="arcWeightRadio" ><i class="icon icon-arc_weight"></i></label>
                            <div class="tooltip">Arc Weight</div>
                        </li>
                        <li>
                            <input type="radio"
                                        name="netControls"
                                        id="markRadio"
                                        value="mark"
                                        tabindex="11"/>
                            <label for="markRadio" ><i class="icon icon-marking"></i></label>
                            <div class="tooltip">Marking</div>
                        </li>
                        <li>
                            <input type="radio"
                                        name="netControls"
                                        id="labelRadio"
                                        value="label"
                                        tabindex="12"/>
                            <label for="labelRadio" ><i class="icon icon-label"></i></label>
                            <div class="tooltip">Label</div>
                        </li>
                        
                </ul>
                
		</div>
                <div class="svgDiv">
                    <svg id="netDrawArea" >
                    
                    </svg>
                </div>
                
            <div class="spodok">
               
            </div>
	</div>
    <div class="popupInfo">
                    <div class="head">
                        <div class="infoHead">Basic usage information</div>
                        <div class="close"><i class="icon-deleteAll"></i></div>
                    </div>
                    <div class="body">
                        <p><i class="icon icon-place"></i><span>Activates place drawing mode</span></p>
                        <p><i class="icon icon-transition"></i><span>Activates transition drawing mode</span></p>
                        <p><i class="icon icon-move"></i><span>Activates drag mode. 
                            To exit drag mode, click again anywhere. You can make a new break point by
                            clicking on arc or just move already created one.</span></p>
                        <p><i class="icon icon-trash"></i><span>Clicking on a place, a transition or an arc will delete it.
                            If cursor is close to a break point of an arc, point will be deleted.</span></p>
                        <p><i class="icon icon-fire"></i><span>Activation of fire mode will allow you to simulate the process by
                            clicking on a transition. Green transition is active, red is not.</span></p>
                        <p><i class="icon icon-add_removeToken"></i><span>Left mouse click on a place will add and
                            right click will remove one token.</span></p>
                        <p><i class="icon icon-regular_ARC_arrow"></i><span>Clicking on place or transition will activate arc drawing
                            mode. In arc drawing mode, clicking on another type as the source type of arc will connect
                            them.</span></p>
                        <p><i class="icon icon-reset_ARC_arrow"></i><span>Reset arc drawing mode will be activated only
                            when you click on a place.</span></p>
                        <p><i class="icon icon-inhibitor_ARC_arrow"></i><span>Inhibitor arc drawing mode will be activated only
                            when you click on a place.</span></p>
                        <p><i class="icon icon-arc_weight"></i><span>Clicking on an arc will bring up a window with input field which
                            represents positive integer value of the arc weight</span></p>
                        <p><i class="icon icon-marking"></i><span>Clicking on a place will bring up a window with input field which
                            represents number of tokens in the current place</span></p>
                        <p><i class="icon icon-label"></i><span>Clicking on a place or a transtion will bring up a window with input field which
                            represents label of place or transition</span></p>
                        <p><i class="icon icon-cross upper"></i><span>Brings up a window asking for confirmation of
                                deleting all components on the drawing area.</span></p>
                        <p><i class="icon icon-xml upper"></i><span>Saves XML file of Petri net to the local file system</span></p>
                        <p><i class="icon icon-svg upper"></i><span>Saves SVG file of Petri net to the local file system</span></p>
                        <p><i class="icon icon-upload-database upper"></i><span>Uploads Petri net to the database</span></p>
                        <p><i class="icon icon-download-database upper"></i><span>Loads Petri net from the database</span></p>
                        <p><i class="icon icon-upload upper"></i><span>Loads xml of Petri net from the local file system</span></p>
                    </div>
                    <div class="infoF">
                            <!--<div class="confirm">OK</div> -->
                        </div>
    </div>
</body>
</html>
