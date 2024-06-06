<?php
include_once 'leads-modal.php';

// URL of the JSON resource
$url = "https://jsonplaceholder.typicode.com/users";

// Fetch the JSON data
$json = file_get_contents($url);
$users = json_decode($json, true);

$values = [];
$parameters = [];

// Loop through the users
foreach ($users as $user) {
    // Generate random details for missing information
    $fullName = $user['name'] ?? generateRandomName();
    $nameParts = explode(' ', $fullName, 2);
    $firstName = $nameParts[0];
    $lastName = $nameParts[1] ?? '';
    $email = $user['email'];
    $country = generateRandomCountry();
    $ip = '::1';
    $phone = $user['phone'] ?? generateRandomPhone();
    $url = 'https://example.com';

    // Add the placeholders to the values array
    $values[] = "(?, ?, ?, ?, ?, ?, ?)";

    // Add the actual values to the parameters array
    $parameters = array_merge($parameters, [$firstName, $lastName, $email, $phone, $ip, $country, $url]);
}

// Convert the array to a string
$valuesString = implode(', ', $values);

// Insert the leads into the database
try{
    addLeads($valuesString, $parameters);
    echo json_encode(['success' => 'Leads added successfully']);
}catch(PDOException $e){
    echo json_encode(['error' => 'An error occurred while adding leads']);
    die();
}

// Functions to generate random details
function generateRandomName() {
    // Generate a random name
    return 'Name ' . rand(1, 1000);
}

function generateRandomCountry() {
    // List of countries
    $countries = ['usa', 'canada', 'uk', 'australia', 'germany', 'israel', 'france', 'spain', 'italy', 'japan'];

    // Pick a random country from the list
    return $countries[array_rand($countries)];
}


function generateRandomPhone() {
    // Generate a random phone number
    return '555-' . rand(100, 999) . '-' . rand(1000, 9999);
}
?>