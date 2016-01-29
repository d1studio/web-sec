<?php
include __DIR__.'/common.php';
include __DIR__.'/medoo.php';
try {
    if(isset($_GET['type']) && in_array($_GET['type'],['1','2'])){
        $type = $_GET['type'];
    }else{
        exit('param error');
    }
    $db = new Medoo(
        [
            'database_type' => 'sqlite',
            'database_file' => 'ip.db'
        ]
    );
    if($type ==1){
        $ip   = get_client_ip();
    }else{
        $ip   = get_client_ip(0,true);
    }
    $date = date('Y-m-d');
    $profile = $db->get("ip_filter", [
        "count"
    ], [
        "AND" => [
            "ip"   => $ip,
            "date" => $date
        ]
    ]);
    $count = intval($profile['count']);
    if($count >10){
        exit('limits error');
    }
    if($_GET['password'] == '928' && $type == "1"){
        echo 'ok';
    }elseif($_GET['password'] == '082' && $type == "2"){
        echo 'ok';
    }else{
        echo 'no';
    }
    if($count == 0){
        $last_user_id = $db->insert("ip_filter", [
            "ip"    => $ip,
            "date"  => date('Y-m-d'),
            "count" => 1
        ]);
    }else{
        $last_user_id = $db->update("ip_filter", [
            "count[+]" => 1,
        ],[
            "AND" => [
                "ip"   => $ip,
                "date" => $date
            ]
        ]);
    }
} catch (Exception $e) {
    echo $e;
}

