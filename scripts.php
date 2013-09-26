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
$return='';
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
            $return = array('return'=>nl2br(trim(file_get_contents('output.log'))));
            break;
        case 'status':
            $return = array('return'=>'','crashplan'=>false,'hdd'=>false,'debug'=>'');
            exec('/usr/local/crashplan/bin/CrashPlanEngine status > /var/www/output.log 2>&1');
            if (preg_match('#CrashPlan Engine (pid \d+) is running.#', file_get_contents('output.log'))){
            $return['crashplan'] = true;
            }
            exec('[ ! -e /media/backup/.backup ]; echo "HDD_there_$?"');
            if (strpos('HDD_there_1', file_get_contents('output.log'))!==false){
            $return['hdd'] = true;
            }
            exec('cat /proc/swaps | grep /dev >> /var/www/output.log 2>&1');
            exec('/etc/init.d/ramlog status >> /var/www/output.log 2>&1');
            $return['debug'] = nl2br(trim(file_get_contents('output.log')));
            break;
    }
}

echo json_encode($return);