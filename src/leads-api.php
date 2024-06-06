<?php
include_once 'leads-modal.php';

// Check if the method parameter is present in the query string
if (isset($_GET['method']) && !empty($_GET['method'])) {
    $method = $_GET['method'];

    switch (strtolower($method)) {
        case 'getleadbyid':
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $lead = getLeadById($_GET['id']);
                echo json_encode($lead);
            } else {
                echo json_encode(['error' => 'Missing id parameter']);
            }
            break;

        case 'markleadascalled':
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                markLeadAsCalled($_GET['id']);
                echo json_encode(['success' => 'Lead marked as called']);
            } else {
                echo json_encode(['error' => 'Missing id parameter']);
            }
            break;

        case 'getcalledleads':
            $date = null;
            if (isset($_GET['date']) && !empty($_GET['date'])) {
                $d = DateTime::createFromFormat('Y-m-d', $_GET['date']);
                if ($d && $d->format('Y-m-d') == $_GET['date']) {
                    $date = $_GET['date'];
                } else {
                    echo json_encode(['error' => 'Invalid date format. Please use YYYY-mm-dd.']);
                    exit;
                }
            }
            $country = isset($_GET['country']) && !empty($_GET['country']) ? strtolower($_GET['country']) : null;
            $leads = getCalledLeads($date, $country);
            echo json_encode($leads);
            break;

        default:
            echo json_encode(['error' => 'Invalid method']);
            break;
    }
} else {
    echo json_encode(['error' => 'Missing method parameter']);
}
?>