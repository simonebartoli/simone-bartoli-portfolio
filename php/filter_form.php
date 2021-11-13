<?php
    function verify($mail){
        // Initialize cURL.
        $ch = curl_init();
        // Set the URL that you want to GET by using the CURLOPT_URL option.
        curl_setopt($ch, CURLOPT_URL, 'http://apilayer.net/api/check?access_key=bfc8f8b4f79f54617eeaac4f501117bc&email='.$mail);
        // Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // Execute the request.
        $data = curl_exec($ch);

        // Close the cURL handle.
        curl_close($ch);

        $data = json_decode($data, true);
        // Print the data out onto the page.
        return $data;
    }

    function nameval($string){
        if (!preg_match("/^[a-zA-Z-' ]*$/", $string)) {
            return false;
        }
        else{
            if(count(explode(" ", $string))>3){return false;}
            else{return true;}
        }
    }

    function VPN($ip){
        // Initialize cURL.
        $API_key = "976401-63i998-215371-pd6m28";

        $ch = curl_init();
        // Set the URL that you want to GET by using the CURLOPT_URL option.
        curl_setopt($ch, CURLOPT_URL, 'https://proxycheck.io/v2/'.$ip."?key=".$API_key.'?vpn=1');
        // Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // Execute the request.
        $data = curl_exec($ch);

        // Close the cURL handle.
        curl_close($ch);

        $data = json_decode($data, true);
        // Print the data out onto the page.
        return $data;

    }

?>