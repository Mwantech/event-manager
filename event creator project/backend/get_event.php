<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
include 'db.php';

// Function to safely encode JSON
function safe_json_encode($data) {
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
}

// Set content type to JSON
header('Content-Type: application/json');

// Check if the link parameter is set
if (!isset($_GET['link'])) {
    echo safe_json_encode(['error' => 'No event link provided']);
    exit;
}

// Decode the event link
$event_link = urldecode(mysqli_real_escape_string($conn, $_GET['link']));

// Debugging: echo the event link to ensure it's correct
// echo $event_link; exit;

// Prepare and execute query to fetch the event details
$query = $conn->prepare("SELECT * FROM events WHERE unique_link = ?");
if (!$query) {
    echo safe_json_encode(['error' => 'Database error: ' . $conn->error]);
    exit;
}

$query->bind_param("s", $event_link);
if (!$query->execute()) {
    echo safe_json_encode(['error' => 'Query execution failed: ' . $query->error]);
    exit;
}

$result = $query->get_result();
$event = $result->fetch_assoc();

if ($event) {
    // Prepare and execute query to fetch media files
    $media_query = $conn->prepare("SELECT media_url AS file_name, media_type AS file_type FROM media WHERE event_id = ?");
    if (!$media_query) {
        echo safe_json_encode(['error' => 'Database error: ' . $conn->error]);
        exit;
    }

    $media_query->bind_param("i", $event['id']);
    if (!$media_query->execute()) {
        echo safe_json_encode(['error' => 'Media query execution failed: ' . $media_query->error]);
        exit;
    }

    $media_result = $media_query->get_result();

    // Prepare an array to hold media data
    $media = [];
    while ($row = $media_result->fetch_assoc()) {
        $media[] = $row;
    }

    // Prepare the response
    $response = [
        'name' => $event['name'],
        'date' => $event['date'],
        'time' => $event['time'],
        'location' => $event['location'],
        'description' => $event['description'],
        'media' => $media
    ];

    // Send the response as JSON
    echo safe_json_encode($response);
} else {
    echo safe_json_encode(['error' => 'Event not found']);
}

// Close prepared statements and database connection
if (isset($query)) $query->close();
if (isset($media_query)) $media_query->close();
$conn->close();
?>
