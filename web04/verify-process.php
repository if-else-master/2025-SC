<?php
require_once "inc/db.php";
require_once "inc/language.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $gtin_list = explode(separator:"\n",string: trim(string: $_POST["gtin_list"]));
    $results=[];
    $all_valid = true;

    foreach($gtin_list as $gtin){
        $gtin = trim(string: $gtin);

        if(empty($gtin)){
            $results[] = [
                'gtin' => $gtin,
                'valid' => false,
                'message'=> 'Invalid format',
            ];
            $all_valid = false;
            continue;
        }
        if (!preg_match(pattern: '/^\d{13}$/', subject: $gtin)) {
            $results[] = [
                'gtin' => $gtin,
                'valid' => false,
                'message' => __(key:'invalid_format')                
            ];
            $all_valid = false;
            continue;
        }
    }
}
?>
