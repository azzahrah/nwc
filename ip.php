<?php

$script = <<<EOF
ifconfig |grep -B1 "inet addr" |awk '{ if ( $1 == "inet" ) { print $2 } else if ( $2 == "Link" ) { printf "%s:" ,$1 } }' |awk -F: '{ print $1 ": " $3 }'
EOF;
$ips = [];
exec($script,$ips);
if (count($ips) > 0) {
    echo "\nYou can access NWC using one of these address:\n";
    foreach($ips as $ip) {
        $ip = explode(" ",$ip);
        $ip = $ip[1];
        echo "http://{$ip}:8080\n";
    }
} else {
    $ip = explode(" ",$ip);
    $ip = $ip[1];
    echo "http://{$ip}:8080\n";
    echo "\nYou can access NWC using this address:" . $ip;
}
echo "\n\n";