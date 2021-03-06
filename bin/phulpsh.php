<?php

$vendor = 'vendor';
$autoload = preg_grep('/^--autoload=/', $argv);
$autoload = $autoload ? array_shift($autoload) : null;

$config = getcwd() . '/composer.json';
$json = null;

if (file_exists($config)) {
    $json = file_get_contents($config);
    $json = json_decode($json, true);
    if (isset($json['config']['vendor-dir'])) {
        $vendor = exec('echo ' . $json['config']['vendor-dir']);
    }
}

if ($json != null) {
    $flag = isset($json['config']['phulp_multi_task']) ? $json['config']['phulp_multi_task'] : false;
    if (! $flag) {
        $argv = array_slice($argv, 0, 2);
    }
}

if (! preg_grep('/^--autoload=/', $argv)) {
    $argv[] = $autoload ?: "--autoload={$vendor}/autoload.php";
}

require "{$vendor}/reisraff/phulp/bin/phulp.php";
