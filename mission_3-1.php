<html>
    <head>
        <meta charset = "utf-8">
        <title> Form sample </title>
    </head>
    <body>
        <form action = " " method = "POST">
        コメント：<br/>
        <input type = "text" name = "name" size 15 value "名前" /><br/>
        <input type = "text" name = "comment" size = 30 value = "コメント" /><br/>
        <input type = "submit" value = "送信する"/><br/>
        </form>
    </body>
</html>

<?php
    $filename = "mission_3-1.txt";
    $fp = fopen($filename, "a+");
    $history = array();
    while($cache = fgets($fp)){
        array_push($history, $cache);
    }
    //write $_POST data to mission_3-1.txt
    if(isset($_POST["name"]) && isset($_POST["comment"])){
        $postnumber = count($history) + 1;
        if(!isset($postnumber)){ // if $history is null, $postnumber is undifined so define it as 1
            $postnumber = 1;
        }
    $date = date("Y年m月d日H:i:s");
    fwrite($fp, $postnumber."<>".$_POST["name"]."<>".$_POST["comment"]."<>".$date."\n");
    }
?>

    
    
