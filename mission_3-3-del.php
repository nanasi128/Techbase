<?php
    $filename = "mission_3-3.txt";
    $fp = fopen($filename, "r");
    $history = array();
    while($cache = fgets($fp)){
        array_push($history, $cache);
    }
    fclose($fp);
    unlink($filename);
    $fp = fopen($filename, "a");
    $count = count($history);
    $post_to_delete = $_POST["number"];
    $is_deleted = 0;
    if(is_numeric($post_to_delete)){
        for($i = 0; $i < $count; $i++){
            $history_split = explode("<>", $history[$i]);
            if($history_split[0] == $post_to_delete){
                $is_deleted = 1;
                continue;
            }
            fwrite($fp, $history[$i]);
        }
    }else echo "指定された番号は無効です。削除に失敗しました"."<br/>";
    if($is_deleted == 0){
        echo "指定された投稿は存在しません。削除に失敗しました"."<br/>";
    }else echo "指定された投稿の削除に成功しました。　-> ".$history[$post_to_delete - 1]."<br/>";
    fclose($fp);
?>
<html>
    <head>
        <meta charset = "utf-8">
        <title> Delete confirm </title>
    </head>
    <body>
        <a href = "https://tb-210037.tech-base.net/Mission/mission3/mission_3-3.php">
            <button type = "button">戻る</button>
        </a>
    </body>
</html>


