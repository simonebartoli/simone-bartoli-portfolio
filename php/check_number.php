<?php
    require $_SERVER['DOCUMENT_ROOT']."/php/init_connection.php";
    $query = "SELECT phoneN FROM banned_number";
    $result = mysqli_query($db, $query);

    while($row = mysqli_fetch_row($result)){
        $result_banned[] = $row;
    }
    $result_banned = array_map('current', $result_banned);

    $query = "SELECT phoneN FROM phone";
    $result = mysqli_query($db, $query);

    while($row = mysqli_fetch_row($result)){
        $result_phone[] = $row;
    }
    $result_phone = array_map('current', $result_phone);


    $to_ban = array_intersect($result_banned, $result_phone);
    print_r($to_ban);
    $query = "UPDATE phone SET banned=true, reason='The Number you verified was not your', verified=false WHERE banned=false AND phoneN IN (".implode(',',$to_ban).")";
    mysqli_query($db, $query);
?>