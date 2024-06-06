<?php
// Include your database connection file if it's separate
include_once 'db.php';

function addLead($data) {
    try {
        // Get the database connection
        $pdo = getDbConnection();

        // Prepare  SQL statement
        $stmt = $pdo->prepare('INSERT INTO leads (first_name, last_name, email, phone_number, ip, country, url, note, sub_1) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');

        // Execute the statement with the data
        $stmt->execute([$data['first_name'], $data['last_name'], $data['email'], $data['phone_number'],$data['ip'], $data['country'], $data['url'], $data['note'], $data['sub_1']]);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
    
}

function addLeads($valuesString, $parameters){
    try{
        // Get the database connection
        $pdo = getDbConnection();

        // Prepare SQL statement
        $sql = "INSERT INTO leads (first_name, last_name, email, phone_number, ip, country, url) VALUES $valuesString";
        $stmt = $pdo->prepare($sql);

        // Execute the statement
        $stmt->execute($parameters);
    }catch(PDOException $e){
        throw $e;
    }

}

function getLeadById($id) {
    // Get the database connection
    try{
        $pdo = getDbConnection();

        // Prepare  SQL statement
        $stmt = $pdo->prepare("SELECT * FROM leads WHERE id = ?");
    
        // Execute the statement with the id
        $stmt->execute([$id]);
    
        // Fetch the data
        $data = $stmt->fetch();
        
        return $data ?: new stdClass();
    }catch(PDOException $e){
        echo 'Error: ' . $e->getMessage();
    }
}

function markLeadAsCalled($id) {
    try{
        // Get the database connection
        $pdo = getDbConnection();

        // Prepare SQL statement
        $stmt = $pdo->prepare("UPDATE leads SET called = 1 WHERE id = ?");

        // Execute the statement with the id
        $stmt->execute([$id]);
    }catch(PDOException $e){
        echo 'Error: ' . $e->getMessage();
    }
}

function getCalledLeads($date = null, $country = null) {
    try{
         // Get the database connection
        $pdo = getDbConnection();

        // Prepare SQL statement
        $sql = "SELECT * FROM leads WHERE called = 1";
        $params = [];

        if ($date) {
            $sql .= " AND DATE(created_at) = ?";
            $params[] = $date;
        }

        if ($country) {
            $sql .= " AND country = ?";
            $params[] = $country;
        }

        $stmt = $pdo->prepare($sql);

        // Execute the statement with the parameters
        $stmt->execute($params);

        // Fetch all the data
        $data = $stmt->fetchAll();

        return $data ?: new stdClass();
    }catch(PDOException $e){
        echo 'Error: ' . $e->getMessage();
    }
}
?>