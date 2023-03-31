<?php

if (!is_dir('logs')) {
    // Create the logs directory
    exec('mkdir logs');

    // Set permissions for the logs directory
    exec('chmod 755 logs');
}

if (!file_exists('logs/error.log')) {
    // Create the error.log file
    exec('touch logs/error.log');

    // Set permissions for the error.log file
    exec('chmod 644 logs/error.log');
}
