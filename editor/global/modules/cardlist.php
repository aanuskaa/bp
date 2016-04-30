<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="description" content="Workflow management forms">
    <meta name="author" content="Jan Polacek">

    <link rel="stylesheet" href="../vendor/normalize.css">
    <link rel="stylesheet" href="../styles/global.css">
</head>


<div class="container">
    <div class="row">
        <div class="card-list-process col-lg-12 ">
            <div class="row">
                <div class="header col-lg-12">
                    <i class="icon icon-edit"></i>
                    <span class="text">Najpoužívanejšie procesy</span>
                </div>
            </div>
            <div class="row">
                <div class="cards col-lg-12">
                    <div class="row">
                        <?php
                            include ('card_process.php');
                            include ('card_process.php');
                            include ('card_process.php');
                            include ('card_process.php')
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="more-button" tabindex="30">
                        Zobraziť viac
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card-list-firm col-lg-12 ">
            <div class="row">
                <div class="header col-lg-12">
                    <i class="icon icon-trend"></i>
                    <span class="text">Najlepšie firmy</span>
                </div>
            </div>
            <div class="row">
                <div class="cards col-lg-12">
                    <div class="row">
                        <?php
                        include ('card_firm.php');
                        include ('card_firm.php');
                        include ('card_firm.php');
                        include ('card_firm.php')
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="more-button" tabindex="50">
                        Zobraziť viac
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


