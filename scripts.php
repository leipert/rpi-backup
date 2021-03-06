<?php

function execInBackground($cmd)
{
    exec($cmd . ' > /dev/null &');
}

function getNiceOutput()
{
    return preg_replace('/[\t\v\f ]+/', ' ', nl2br(trim(file_get_contents('log/output.log'))));
}

function br2nl($string)
{
    return preg_replace('/\<br(\s*)?\/?\>\s+/i', "\n", $string);
}

function update(){
    exec('git pull > /var/www/log/output.log 2>&1', $arr, $status);
    $output = getNiceOutput();
    if ($status == 0) {
        $status = 'success';
        file_put_contents('log/update.log',time());
    } else {
        $status = 'danger';
    }
    if (strpos($output, 'Already up-to-date') !== false) {
        $status = 'info';
    }
    return array('return' => $output, 'status' => $status);
}


touch('log/output.log');
touch('log/update.log');
$return = '';
if (isset($_GET['call'])) {
    switch ($_GET['call']) {
        case 'reboot':
            execInBackground('sudo shutdown -r now');
            break;
        case 'shutdown':
            execInBackground('sudo shutdown -h now');
            break;
        case 'pull':
            $return = update();
            break;
        case 'removeHDD':
            touch('log/.nomount');
            exec('sudo /etc/init.d/crashplan stop > /var/www/log/output.log 2>&1', $arr, $status);
            sleep(5);
            exec('/usr/local/crashplan/bin/CrashPlanEngine status > /var/www/log/output.log 2>&1');
            if (strpos(getNiceOutput(), 'CrashPlan Engine is stopped') !== false) {
                exec('sudo swapoff -a > /var/www/log/output.log 2>&1', $arr, $status);
                if ($status == 0) {
                    exec('sudo umount /media/backup > /var/www/log/output.log 2>&1', $arr, $status);
                    if ($status == 0) {
                        $return = array('return' => 'Festplatte erfolgreich entfernt.', 'status' => 'success');
                    } else {
                        $return = array('return' => 'Festplatte konnte nicht entfernt werden. (umount) <br>' . getNiceOutput(), 'status' => 'danger');
                    }
                } else {
                    $return = array('return' => 'Festplatte konnte nicht entfernt werden. (swapoff) <br>' . getNiceOutput(), 'status' => 'danger');
                }
            } else {
                $return = array('return' => 'Festplatte konnte nicht entfernt werden, da Crashplan noch läuft!', 'status' => 'danger');
            }
            unlink('log/.nomount');
            break;
        case 'status':
            if(time()>(intval(file_get_contents('log/update.log'))+24*60*60)){
                update();
            }
            $return = array('return' => '', 'crashplan' => false, 'hdd' => false, 'debug' => '');
            exec('/usr/local/crashplan/bin/CrashPlanEngine status > /var/www/log/output.log 2>&1');
            if (preg_match('/CrashPlan Engine \(pid \d+\) is running/', getNiceOutput())) {
                $return['crashplan'] = true;
            }
            exec('[ ! -e /media/backup/.backup ]; echo "HDD_there_$?" >> /var/www/log/output.log 2>&1');
            if (strpos(getNiceOutput(), 'HDD_there_1') !== false) {
                $return['hdd'] = true;
            }
            exec('echo Mounted swaps: `cat /proc/swaps | grep /dev` >> /var/www/log/output.log 2>&1');
            exec('echo Ramlog Status: `/etc/init.d/ramlog status` >> /var/www/log/output.log 2>&1');
            exec('echo Program Version: `git rev-parse --short HEAD` >> /var/www/log/output.log 2>&1');
            exec('echo CPU Temp: `cat /sys/class/thermal/thermal_zone0/temp` >> /var/www/log/output.log 2>&1');
            $return['debug'] = br2nl(date('r').' <br> '.getNiceOutput());
            break;
    }
}

echo json_encode($return);