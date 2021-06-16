<?php
$app_dir_path = realpath(dirname(__FILE__) . '/../../');
$env_file_path = dirname(__FILE__) . '/../../.env';

if ($argc < 2) {
    exit_with_error(
        'env_name required.' . PHP_EOL .
        'Usage: ' . pathinfo(__FILE__)['basename'] . ' env_name [default_value]');
}

if (!file_exists($env_file_path)) {
    exit_with_error($app_dir_path . '/.env does not exists.');
}

$env_map = array();
$env_file_contents = file_get_contents($env_file_path);
foreach (explode("\n", $env_file_contents) as $line) {
    $env_line = trim($line);
    if (!$env_line) { continue; }

    list($key, $value) = explode('=', $env_line);
    if (strpos($key, '#') === 0) { continue; }
    $env_map[$key] = $value;
}

$env_name = $argv[1];
if (array_key_exists($env_name, $env_map)) {
    echo $env_map[$env_name];
} else {
    if ($argc > 2) {
        echo $argv[2];
    } else {
        echo '';
    }
}


function exit_with_error($message) {
    echo 'Error: ' . $message . PHP_EOL;
    exit(1);
}