<?php
// Function to load environment variables from a file
function loadEnvironmentVariables($filePath) {
    // Check if the file exists
    if (!is_file($filePath)) {
        // If the file doesn't exist, throw an exception
        throw new InvalidArgumentException("{$filePath} is not a valid file.");
    }

    // Parse the file and get the environment variables
    $envVariables = parse_ini_file($filePath);

    // Loop through each environment variable
    foreach ($envVariables as $key => $value) {
        // Set the environment variable
        putenv("{$key}={$value}");
    }
}

// Call the function to load environment variables from the .env file in the config directory
loadEnvironmentVariables(__DIR__ . DIRECTORY_SEPARATOR .'config' . DIRECTORY_SEPARATOR . '.env');