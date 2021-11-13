<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/php/init_connection.php'; 
    mysqli_query($db, "DELETE FROM banned_number");

    $array = array_unique(explode("\n", file_get_contents('banned.txt')));
    sort($array);
    $file = fopen("banned.txt", "w");
    for($i=0; $i<count($array); $i++){
        mysqli_query($db, "INSERT INTO banned_number (phoneN) VALUES ($array[$i])");
        if($i==count($array)-1){
            fwrite($file, $array[$i]);
        }else{
            fwrite($file, $array[$i]."\n");
        }
    }
    fclose($file);

?>