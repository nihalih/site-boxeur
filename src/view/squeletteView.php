<!DOCTYPE html>
    <html lang='fr'>
    <head>
        <title><?=$this->title?></title>
        <meta charset='UTF-8' />
        <link rel="stylesheet" href="skin/screen.css"/>
    </head>
    <body>
    <?=$this->menu?>
    <?php
        if($this->feedback!=='')
        {
           echo($this->feedback);
        }
    ?>
    <?=$this->content?>
    </body>
    </html>