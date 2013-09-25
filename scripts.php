<?php

function execInBackground($cmd) { 
    if (substr(php_uname(), 0, 7) == 'Windows'){ 
        pclose(popen('start /B '. $cmd, "r"));  
    } 
    else { 
        exec($cmd . ' > /dev/null &');   
    } 
}

touch('output.log');
if(isset($_GET['call'])){
    switch ($_GET['call']) {
        case 'reboot':
            execInBackground('sudo shutdown -r now');
            break;
        case 'shutdown':
            execInBackground('sudo shutdown -h now');
            break;
        case 'pull':
            exec('git pull > output.log');
            break;
    }
}

echo json_encode(array('ret'=>file_get_contents('output.log')));