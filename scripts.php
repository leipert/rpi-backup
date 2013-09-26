<?php

function execInBackground($cmd)
{
    exec($cmd . ' > /dev/null &');
}

function getNiceOutput()
{
    return preg_replace('/[\t\v\f ]+/', ' ', nl2br(trim(file_get_contents('output.log'))));
}

function br2nl($string)
{
    return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
}


touch('output.log');
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
            exec('git pull > /var/www/output.log 2>&1', $arr, $status);
            $output = getNiceOutput();
            if ($status == 0) {
                $status = 'success';
            } else {
                $status = 'danger';
            }
            if (strpos($output, 'Already up-to-date') !== false) {
                $status = 'info';
            }
            $return = array('return' => $output, 'status' => $status);
            break;
        case 'removeHDD':
            touch('.nomount');
            exec('sudo /etc/init.d/crashplan stop > /var/www/output.log 2>&1', $arr, $status);
            sleep(5);
            exec('/usr/local/crashplan/bin/CrashPlanEngine status > /var/www/output.log 2>&1');
            if (strpos(getNiceOutput(), '/CrashPlan Engine is stopped/') !== false) {
                exec('sudo swapoff -a > /var/www/output.log 2>&1', $arr, $status);
                if ($status == 0) {
                    exec('sudo umount /media/backup > /var/www/output.log 2>&1', $arr, $status);
                    if ($status == 0) {
                        $return = array('return' => 'HDD unmounted', 'status' => 'success');
                    } else {
                        $return = array('return' => 'HDD could not be unmounted.<br>' . getNiceOutput(), 'status' => 'danger');
                    }
                } else {
                    $return = array('return' => 'Swap could not be turned off.<br>' . getNiceOutput(), 'status' => 'danger');
                }
            } else {
                $return = array('return' => 'Crashplan still running', 'status' => 'danger');
            }
            unlink('.nomount');
            break;
        case 'status':
            $return = array('return' => '', 'crashplan' => false, 'hdd' => false, 'debug' => '');
            exec('/usr/local/crashplan/bin/CrashPlanEngine status > /var/www/output.log 2>&1');
            if (preg_match('/CrashPlan Engine \(pid \d+\) is running/', getNiceOutput())) {
                $return['crashplan'] = true;
            }
            exec('[ ! -e /media/backup/.backup ]; echo "HDD_there_$?" >> /var/www/output.log 2>&1');
            if (strpos(getNiceOutput(), 'HDD_there_1') !== false) {
                $return['hdd'] = true;
            }
            exec('cat /proc/swaps | grep /dev >> /var/www/output.log 2>&1');
            exec('/etc/init.d/ramlog status >> /var/www/output.log 2>&1');
            $return['debug'] = br2nl(getNiceOutput());
            break;
    }
}

echo json_encode($return);