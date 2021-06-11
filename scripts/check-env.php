<?php
$app_dir_path = realpath(dirname(__FILE__) . '/../../');
$env_file_path = dirname(__FILE__) . '/../../.env';

if (!file_exists($env_file_path)) {
    exit_with_error($app_dir_path . '/.env does not exists.');
    exit(1);
}
echo 'checking for ' . realpath($env_file_path) . PHP_EOL;

$env_map = array();
$env_file_contents = file_get_contents($env_file_path);
foreach (explode("\n", $env_file_contents) as $line) {
    $env_line = trim($line);
    if (!$env_line) { continue; }

    list($key, $value) = explode('=', $env_line);
    if (strpos($key, '#') === 0) { continue; }
    $env_map[$key] = $value;
}

$env_required = array(
    'APP_ENV',
    'APP_DOMAIN',
    'DB_DATABASE',
    'DB_USERNAME',
    'DB_PASSWORD',
);

foreach ($env_required as $env_key) {
    if (!array_key_exists($env_key, $env_map)) {
        exit_with_error($env_key . ' undefined.');
    }
}

if ($env_map['DB_USERNAME'] == 'root') {
    exit_with_error('DB_USERNAME must not be \'root\'.');
}

echo 'ok.' . PHP_EOL;

function exit_with_error($message) {
    echo 'Error: ' . $message . PHP_EOL;
    exit(1);
}