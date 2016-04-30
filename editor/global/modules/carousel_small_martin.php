<?php
  // tam kde je HTML_PATH u mna znamena napr. http://localhost/workflowmarket_integrated/martin
  // to si zmente podla seba, aby vam to vedelo zobrat moj priecinok, tj pracuje s Http linkami.

  // SITE_PATH znamena napr. cestu E:/xampp/htdocs/workflowmarket_integrated/martin tj. celu cestu z disku, servera, ... tj. pracuje s diskovymi linkami
?>

<div id="carousel">
  <div id="carousel-background">
    <img src="<?php echo HTML_PATH . "template/images/carousel/$subCarousel"; ?>" id="carousel-image"> <!-- zmente si cesty podla vlastneho -->
  </div>

  <div class="carousel-wrapper">
    <div class="container">

      <div class="row  carousel-content">
        <div class="col-lg-12 ">

          <?php include(SITE_PATH . "global/modules/carousel_main_menu_martin.php"); ?> <!-- zmente si cesty podla vlastneho -->

          <h1><?php echo $title; ?></h1>
        </div>
      </div>
      
    </div>
  </div>

</div>