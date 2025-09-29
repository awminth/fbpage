<?php
    require 'vendor/autoload.php';
    extract($_POST);

    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $code2 ='';
    foreach(str_split($codeno) as $key => $c){
        $code2 .=$c;
        if(count(str_split($codeno)) != $key)
        $code2 .=' ';

    }


    for($i=1; $i<=$cnt; $i++){

?>
        <div id="field" class="float-left m-2">
            <large><b><?php echo $itemname ?></b></large><br>
            <img src="data:image/png;base64,<?php echo base64_encode($generator->getBarcode($codeno, $type)) ?>">
            <div id="code"><?php echo $code2 ?></div>
            <large>price : <b><?php echo $price ?></b></large>
        </div>

<?php
    }

?>