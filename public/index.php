<?php

// Suppress deprecation notices for PHP 8.4 compatibility
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */

$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION,
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;

    exit(1);
}

/*
 *---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 *---------------------------------------------------------------
 */

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 */

// Search for Paths.php in standard locations and custom hosting folders like app_core/
$pathsFile = null;

if (is_file(FCPATH . 'app_core/app/Config/Paths.php')) {
    $pathsFile = FCPATH . 'app_core/app/Config/Paths.php';
} elseif (is_file(FCPATH . 'app_core/Config/Paths.php')) {
    $pathsFile = FCPATH . 'app_core/Config/Paths.php';
} elseif (is_file(FCPATH . 'app/Config/Paths.php')) {
    $pathsFile = FCPATH . 'app/Config/Paths.php';
} elseif (is_file(FCPATH . 'daily-tools/app/Config/Paths.php')) {
    $pathsFile = FCPATH . 'daily-tools/app/Config/Paths.php';
} elseif (@is_file(FCPATH . '../app/Config/Paths.php')) {
    $pathsFile = FCPATH . '../app/Config/Paths.php';
}

if ($pathsFile === null) {
    header('HTTP/1.1 500 Internal Server Error');
    echo "<div style='font-family: sans-serif; padding: 20px; line-height: 1.6;'>";
    echo "<h2 style='color: #c0392b;'>Paths.php Location Diagnostic</h2>";
    echo "<p>Current web root (FCPATH): <code>" . htmlspecialchars(FCPATH) . "</code></p>";

    if (is_dir(FCPATH . 'app_core')) {
        echo "<h3>Contents of <code>app_core/</code>:</h3><ul>";
        $appCoreItems = scandir(FCPATH . 'app_core');
        foreach ($appCoreItems as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $isDir = is_dir(FCPATH . 'app_core/' . $item) ? ' <b>[DIR]</b>' : '';
            echo "<li>" . htmlspecialchars($item) . $isDir . "</li>";
        }
        echo "</ul>";
    }

    echo "</div>";
    exit(1);
}

require $pathsFile;

$paths = new Config\Paths();

// LOAD THE FRAMEWORK BOOTSTRAP FILE
$bootstrap = $paths->systemDirectory . '/Boot.php';
if (! is_file($bootstrap)) {
    header('HTTP/1.1 500 Internal Server Error');
    echo "<div style='font-family: sans-serif; padding: 20px; line-height: 1.6;'>";
    echo "<h2 style='color: #c0392b;'>CodeIgniter System Error: <code>Boot.php</code> not found</h2>";
    echo "<p>Paths file used: <code>" . htmlspecialchars($pathsFile) . "</code></p>";
    echo "<p>Looking for Boot.php at: <code>" . htmlspecialchars($bootstrap) . "</code></p>";
    echo "<p>Please ensure your <code>vendor/</code> folder is uploaded alongside the <code>app/</code> folder.</p>";
    echo "</div>";
    exit(1);
}

require $bootstrap;

exit(CodeIgniter\Boot::bootWeb($paths));
