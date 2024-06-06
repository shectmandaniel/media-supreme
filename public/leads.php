<?php
require '../src/leads-modal.php';
// Function to validate and sanitize the input data for security purposes
function validateAndSanitize($data) {
    $sanitizedData = [];
    $errors = [];

    // Validate and sanitize first name
    if (empty($data['first_name'])) {
        $errors['first_name'] = 'First name is required.';
    } else {
        $sanitizedData['first_name'] = filter_var($data['first_name'], FILTER_SANITIZE_STRING);
    }

    // Validate and sanitize last name
    if (empty($data['last_name'])) {
        $errors['last_name'] = 'Last name is required.';
    } else {
        $sanitizedData['last_name'] = filter_var($data['last_name'], FILTER_SANITIZE_STRING);
    }

    // Validate and sanitize email
    if (empty($data['email'])) {
        $errors['email'] = 'Email is required.';
    } else {
        $sanitizedData['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($sanitizedData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format";
        }
    }

    // Validate and sanitize phone number
    if (empty($data['phone_number'])) {
        $errors['phone_number'] = 'Phone number is required.';
    } else {
        $sanitizedData['phone_number'] = filter_var($data['phone_number'], FILTER_SANITIZE_STRING);
        if (!preg_match('/^[0-9]{10,15}$/', $sanitizedData['phone_number'])) {
            $errors['phone_number'] = 'Invalid phone number format.';
        }
    }

    if (!empty($data['note'])) {
        $sanitizedData['note'] = filter_var($data['note'], FILTER_SANITIZE_STRING);
    } else {
        $sanitizedData['note'] = null;
    }

    //If there are errors, return the data and errors to not make the API call
    if (!empty($errors)) {
        return [$sanitizedData, $errors];
    }

    // Make an API call to get the user's IP address and country
    // If the API call fails, set the IP address to '::1' and the country to null
    // Otherwise, sanitize and store the country and IP address in the sanitized data array
    $contact = file_get_contents('http://ip-api.com/json/');
    $contact = json_decode($contact, true);


    if ($contact['status'] == 'fail') {
        $sanitizedData['ip'] = '::1';
        $sanitizedData['country'] = null;
    } else {
        $sanitizedData['country'] = !empty($contact['country']) ? strtolower(filter_var($contact['country'], FILTER_SANITIZE_STRING)) : null;

        $sanitizedData['ip'] = filter_var($contact['query'], FILTER_SANITIZE_STRING);
        if (!filter_var($sanitizedData['ip'], FILTER_VALIDATE_IP)) {
            $sanitizedData['ip'] = "::1";
        }
    }
    

    
    //validate and sanitize URL
    $sanitizedData['url'] = filter_var($data['url'], FILTER_SANITIZE_URL);

    // Parse the URL and get the query string
    $queryString = parse_url($sanitizedData['url'], PHP_URL_QUERY);

    // Parse the query string and get the parameters
    parse_str($queryString, $params);

    // Get the sub_1 parameter and sanitize it
    $sanitizedData['sub_1'] = isset($params['sub_1']) ? htmlspecialchars($params['sub_1']) : null;

    return [$sanitizedData, $errors];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try{
        $email = $_POST['email'];
        $pdo = getDbConnection();
        $stmt = $pdo->prepare('SELECT * FROM leads WHERE email = ?');
        $stmt->execute([$email]);

        // If a lead with the given email already exists, return an error message
        // Otherwise, validate and sanitize the POST data
        // If there are any errors, return the errors
        // Otherwise, add the lead and return a success message
        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Sorry, this email is already registered. we will contact you soon.']);
        } else {
            list($sanitizedData, $errors) = validateAndSanitize($_POST);
            if (!empty($errors)) {
                echo json_encode(['status' => 'error', 'errors' => $errors]);
            } else {
                addLead($sanitizedData);
                echo json_encode(['status' => 'success', 'message' => 'Thank you ' . ucfirst($sanitizedData['first_name']) . ' ' . ucfirst($sanitizedData['last_name']) . ', we’ll contact you soon']);
            }
        }
    }catch(PDOException $e){
        //Log the error message for debugging
        error_log($e->getMessage());

        //Present a generic error to the user
        echo json_encode(['status' => 'error', 'message' => 'An error occurred, please try again later.']);
        die();
    }
    
}
?>
