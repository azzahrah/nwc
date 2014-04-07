<?php

$install_dir = "/var/nwc";


if (getcwd() != $install_dir) {
    echo "Installing NWC to {$install_dir}";
    
    if (is_dir($install_dir)) {
        $process = "ps aux | grep /var/nwc | awk 'FNR == 1 {print $2}'";
        $process = exec($process);
        exec ("kill {$process}");
        exec ("rm -rf {$install_dir}");
    } 
    
    mkdir($install_dir);    
    exec ("cp -R * {$install_dir}");
    chdir($install_dir);
} else {
    echo "Installing from {$install_dir}";
}

