<?php
$cron_dir = '/etc/cron.d/';
$logrotate_conf_dir = '/etc/logrotate.d/';
$lare_log_dir = '/var/log/lare/';

$script_get_env = dirname(__FILE__) . '/get-env.php';
$container_db = `php ${script_get_env} CONTAINER_DB db`;
$db_name = `php ${script_get_env} DB_DATABASE`;
$db_user = `php ${script_get_env} DB_USERNAME`;
$db_pass = `php ${script_get_env} DB_PASSWORD`;
$date = date('Ymd');

install_logrotate_schedule();
install_cron_schedule(array(
    '# backup database',
    "0 3 * * * root docker container exec ${container_db} mysqldump --no-tablespaces -u ${db_user} -p${db_pass} ${db_name} > ${lare_log_dir}${db_name}-`php -r 'print date(\"Ymd\");'`.sql",
));

function install_cron_schedule(array $cron_lines)
{
    global $cron_dir;
    print 'installing cron schedule.' . PHP_EOL;
    $cron_path = "$cron_dir/lare";
    file_put_contents($cron_path, implode(PHP_EOL, $cron_lines) . PHP_EOL);
}

function install_logrotate_schedule()
{
    global $lare_log_dir, $logrotate_conf_dir;
    print 'installing logrotate schedule.' . PHP_EOL;
    if (!is_dir($logrotate_conf_dir)) {
        lare_fail('logrotate config directory does not exist.');
    }

    $logrotate_conf = <<<EOC
${lare_log_dir}*.sql {
  daily
  missingok
  rotate 14
  compress
  delaycompress
  notifempty
  create 640 root root
}
EOC;

    $result = file_put_contents($logrotate_conf_dir . '/flavius', $logrotate_conf);
    if (!$result) {
        lare_fail('failed to put logrotate config file.');
    }

    if (!is_dir($lare_log_dir)) {
        $return_var = 1;
        lare_system("mkdir ${lare_log_dir}", $return_var);
        if ($return_var) {
            lare_fail('failed to create logging directory.');
        }
    }
    lare_system("chmod 0755 ${lare_log_dir}", $return_var);
    if ($return_var) {
        lare_fail('failed to configurate permission logging directory.');
    }

    return 0;
}


function lare_system($cmd, &$ret)
{
    print $cmd . PHP_EOL;
    return system($cmd, $ret);
}

function lare_fail($message)
{
    print $message . PHP_EOL;
    exit(1);
}
