<?php

// Define the commands to be executed in sequence
$commands = [
    'php spark migrate:rollback',
    'php spark migrate',
    'php spark db:seed Counters'
];

// Execute each command and display the output
foreach ($commands as $command) {
    echo "Running: $command\n";

    // Execute the command
    $output = null;
    $resultCode = null;
    exec($command, $output, $resultCode);

    // Display the output
    echo implode("\n", $output) . "\n";

    // Check if the command failed
    if ($resultCode !== 0) {
        echo "Error: Command '$command' failed with exit code $resultCode.\n";
        exit($resultCode);
    }

    echo "Command '$command' executed successfully.\n\n";
}

// Inform the user that all commands ran successfully
echo "All commands executed successfully.\n";
