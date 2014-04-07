<?php

$install_dir = "/var/nwc";

echo "
 ________   ___       __   ________     
|\   ___  \|\  \     |\  \|\   ____\    
\ \  \\ \  \ \  \    \ \  \ \  \___|    
 \ \  \\ \  \ \  \  __\ \  \ \  \       
  \ \  \\ \  \ \  \|\__\_\  \ \  \____  
   \ \__\\ \__\ \____________\ \_______\
    \|__| \|__|\|____________|\|_______|

Welcome to NWC Installation
";

### Copy NWC
if (getcwd() != $install_dir) {
    echo "Copying NWC to {$install_dir} \n";
    if (is_dir($install_dir)) {
        exec ("rm -rf {$install_dir}");
    } 
    
    mkdir($install_dir);    
    exec ("cp -R * {$install_dir}");
    chdir($install_dir);
} else {
    echo "Installing from {$install_dir} \n";
}

### Composer update
$handle = popen("cd {$install_dir}/base && /var/nwc/php /var/nwc/composer.phar update 2>&1", 'r');
while (!feof($handle))
{
    $read = fread($handle, 2096);
    echo $read;
}
pclose($handle);

### Run NWC
chdir($install_dir);
exec ("cp nwc.conf /etc/init/");
echo "Starting NWC Service\n";
exec ("service nwc restart");

echo "\n\nNWC Successfully Installed!\n\n";

### Get IP
ob_start();
include("ip.php");
$ip = ob_get_clean();
echo $ip;