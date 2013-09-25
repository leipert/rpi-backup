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
            exec('git pull > /var/www/output.log 2>&1');
            break;
    }
}

echo json_encode(array('return'=>nl2br(preg_replace('/\s+$/','',file_get_contents('output.log')))));