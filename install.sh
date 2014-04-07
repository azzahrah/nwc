#!/bin/bash

if [[ `uname -a` =~ "precise1-Ubuntu" ]]; then
    echo "OS confirmed: Ubuntu 12.04"
else
    echo "ERROR: NWC must be installed on Ubuntu 12.04!" 
    exit
fi

echo "Requesting root access, you may have to type your password..."
sudo chmod +x php
sudo ./php install.php
