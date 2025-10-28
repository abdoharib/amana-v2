<?php

$target = '/home/cloudte2/Amana/storage/app/public';
$link = '/home/cloudte2/Amana/public/storage';

if (!file_exists($link)) {
    if (symlink($target, $link)) {
        echo "Symlink created successfully.";
    } else {
        echo "Failed to create symlink.";
    }
} else {
    echo "Symlink already exists.";
}
