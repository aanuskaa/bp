<?php
  // tam kde je HTML_PATH u mna znamena napr. http://localhost/workflowmarket_integrated/martin
  // to si zmente podla seba, aby vam to vedelo zobrat moj priecinok
?>
<div class="drop-buttons">
  <div class="process button">
    <div class="inner"> <i class="icon icon-down124"></i>
      <span>Procesy</span>
    </div>
    <div class="extra-padding"></div>
    <div class="drop-card">
      <div class="content">
        <div class="top-line">
          <div class="line"></div>
        </div>
        <div class="menu">
          <div class="left-column column">
            <div class="header">Podľa filtrov</div>
            <div class="links">
              <div class="link"> <i class="icon icon-right164"></i>
                <a href="<?php echo HTML_PATH . "index.php/process/show"; ?>" class="text">Všetky procesy</a> <!-- zmente si cesty podla vlastneho -->
              </div>
              <div class="link">
                <i class="icon icon-right164"></i>
                <a href="<?php echo HTML_PATH . "index.php/process/show?&sort=1&page=1"; ?>" class="text">Najlepšie procesy</a> <!-- zmente si cesty podla vlastneho -->
              </div>
              <div class="link">
                <i class="icon icon-right164"></i>
                <a href="<?php echo HTML_PATH . "index.php/process/show?&sort=2&page=1"; ?>" class="text">Najnovšie procesy</a> <!-- zmente si cesty podla vlastneho -->
              </div>
            </div>
          </div>
          <div class="right-column column">
            <div class="header">Podľa abecedy</div>
            <div class="alphabet">
              
              <?php foreach (range('A', 'Z') as $char) { ?>
                <div class="char"><a href="<?php echo HTML_PATH . "index.php/process/show/$char"; ?>"> <?php echo $char; ?> </a></div> <!-- zmente si cesty podla vlastneho -->
              <?php } ?>
     
            </div>
            <!--<div class="header">Vyhľadávanie</div>
            <div class="search">
              <input class="search-input" placeholder="Vyhľadaj kľúčové slovo" type="text">
              <button class="search-button">OK</button>
            </div>-->
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="firm button">
    <div class="inner">
      <i class="icon icon-down124"></i>
      <span>Firmy</span>
    </div>
    <div class="drop-card">
      <div class="content">
        <div class="top-line">
          <div class="line"></div>
        </div>
        <div class="menu">
          <div class="left-column column">
            <div class="header">Podľa filtrov</div>
            <div class="links">
              <div class="link">
                <i class="icon icon-right164"></i>
                <a href="<?php echo HTML_PATH . "index.php/firm/all"; ?>" class="text">Všetky firmy</a> <!-- zmente si cesty podla vlastneho -->
              </div>
              <div class="link">
                <i class="icon icon-right164"></i>
                <a href="<?php echo HTML_PATH . "index.php/firm/all?&sort=1&page=1"; ?>" class="text">Firmy s najviac procesmi</a> <!-- zmente si cesty podla vlastneho -->
              </div>
              <div class="link">
                <i class="icon icon-right164"></i>
                <a href="<?php echo HTML_PATH . "index.php/firm/all?&sort=2&page=1"; ?>" class="text">Firmy s najviac užívateľmi</a> <!-- zmente si cesty podla vlastneho -->
              </div>
            </div>
          </div>
          <div class="right-column column">
            <div class="header">Podľa abecedy</div>
            <div class="alphabet">
              
              <?php foreach (range('A', 'Z') as $char) { ?>
                <div class="char"><a href="<?php echo HTML_PATH . "index.php/firm/all/$char"; ?>"> <?php echo $char; ?> </a></div> <!-- zmente si cesty podla vlastneho -->
              <?php } ?>
                
            </div>
            <!--<div class="header">Vyhľadávanie</div>
            <div class="search">
              <input class="search-input" placeholder="Vyhľadaj kľúčové slovo" type="text">
              <button class="search-button">OK</button>
            </div>-->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>