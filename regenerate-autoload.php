<?php
/**
 * Regenerate Composer Autoloader
 * Upload this file to your production server and run it via browser or CLI
 */

// Set the base path
$basePath = __DIR__;

echo "Starting autoloader regeneration...\n\n";

// Include composer autoload
require_once $basePath . '/vendor/autoload.php';

// Get the composer ClassLoader instance
$loader = require $basePath . '/vendor/autoload.php';

// Force regeneration by requiring composer's ClassLoader
if (file_exists($basePath . '/vendor/composer/ClassLoader.php')) {
    require_once $basePath . '/vendor/composer/ClassLoader.php';
    
    // Create new instance
    $classLoader = new \Composer\Autoload\ClassLoader();
    
    // Load autoload_psr4.php
    $psr4File = $basePath . '/vendor/composer/autoload_psr4.php';
    if (file_exists($psr4File)) {
        $map = require $psr4File;
        foreach ($map as $namespace => $path) {
            $classLoader->setPsr4($namespace, $path);
        }
        echo "✓ PSR-4 autoload configured\n";
    }
    
    // Load autoload_classmap.php
    $classmapFile = $basePath . '/vendor/composer/autoload_classmap.php';
    if (file_exists($classmapFile)) {
        $classMap = require $classmapFile;
        $classLoader->addClassMap($classMap);
        echo "✓ Class map loaded\n";
    }
    
    // Load autoload_files.php
    $filesFile = $basePath . '/vendor/composer/autoload_files.php';
    if (file_exists($filesFile)) {
        $files = require $filesFile;
        echo "✓ Files autoload configured\n";
    }
    
    $classLoader->register(true);
    
    echo "\n✓ Autoloader regenerated successfully!\n\n";
} else {
    echo "✗ Error: vendor/composer/ClassLoader.php not found\n";
}

// Verify the ConnexApiService can be found
echo "Verifying App\\Services\\ConnexApiService...\n";
$servicePath = $basePath . '/app/Services/ConnexApiService.php';
if (file_exists($servicePath)) {
    echo "✓ ConnexApiService.php exists at: $servicePath\n";
    
    // Try to include it
    try {
        require_once $servicePath;
        if (class_exists('App\\Services\\ConnexApiService')) {
            echo "✓ ConnexApiService class loaded successfully!\n";
        } else {
            echo "✗ ConnexApiService class could not be loaded\n";
        }
    } catch (Exception $e) {
        echo "✗ Error loading ConnexApiService: " . $e->getMessage() . "\n";
    }
} else {
    echo "✗ ConnexApiService.php NOT FOUND at: $servicePath\n";
    echo "   Please check if the file exists on the server!\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "Done! You can now delete this file.\n";
echo str_repeat("=", 50) . "\n";

