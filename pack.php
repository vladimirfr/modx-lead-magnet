<?php
$pharFile = 'pipedrive-woodpecker.phar';

if (file_exists($pharFile)) {
    unlink($pharFile);
}

$phar = new Phar($pharFile);
// start buffering. Mandatory to modify stub to add shebang
$phar->startBuffering();

// Create the default stub from main.php entrypoint
$defaultStub = $phar->createDefaultStub('autoload.php');

// Add the rest of the apps files
$phar->buildFromDirectory(__DIR__ . '/vendor');

// Customize the stub to add the shebang
$stub = "#!/usr/bin/env php \n" . $defaultStub;

// Add the stub
$phar->setStub($stub);

$phar->stopBuffering();

// plus - compressing it into gzip
$phar->compressFiles(Phar::GZ);
