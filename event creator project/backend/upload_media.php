<?php
include 'db.php'; // Include database connection

// Function to generate a unique filename
function generateUniqueFilename($original_filename) {
    $extension = pathinfo($original_filename, PATHINFO_EXTENSION);
    return uniqid() . '.' . $extension;
}

// Check if form is submitted and files are uploaded
if (isset($_POST['event_link']) && !empty($_FILES['files']['name'][0])) {
    $event_link = mysqli_real_escape_string($conn, $_POST['event_link']);
    $user_id = intval($_POST['user_id']); // Assuming user_id is passed from the form

    // Query to find the event ID using the unique link
    $query = $conn->prepare("SELECT id FROM events WHERE unique_link = ?");
    $query->bind_param("s", $event_link);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $event_id = $row['id']; // Get the event ID

        $target_dir = "../uploads/"; // Directory to store files
        $allowed_image_types = ['jpg', 'jpeg', 'png'];
        $allowed_video_types = ['mp4', 'avi', 'mov'];

        $upload_success = true;
        $uploaded_files = [];

        // Loop through each uploaded file
        foreach ($_FILES['files']['name'] as $key => $filename) {
            $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            // Determine the file type (image or video)
            if (in_array($file_extension, $allowed_image_types)) {
                $fileType = 'image';
                $dir = 'images/';
            } elseif (in_array($file_extension, $allowed_video_types)) {
                $fileType = 'video';
                $dir = 'videos/';
            } else {
                echo "Error: File type not allowed for " . htmlspecialchars($filename) . "<br>";
                $upload_success = false;
                continue;
            }

            $unique_filename = generateUniqueFilename($filename);
            $file_path = $dir . $unique_filename;
            $full_path = $target_dir . $file_path;

            // Move the uploaded file to the correct directory
            if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $full_path)) {
                $uploaded_files[] = [
                    'path' => $file_path,
                    'type' => $fileType
                ];
            } else {
                echo "Error: Failed to move uploaded file " . htmlspecialchars($filename) . "<br>";
                $upload_success = false;
            }
        }

        // If all files were moved successfully, insert into database
        if ($upload_success && !empty($uploaded_files)) {
            $insert_query = $conn->prepare("INSERT INTO media (event_id, user_id, media_url, media_type) VALUES (?, ?, ?, ?)");
            
            foreach ($uploaded_files as $file) {
                $insert_query->bind_param("iiss", $event_id, $user_id, $file['path'], $file['type']);
                if (!$insert_query->execute()) {
                    echo "Error: Failed to insert file info into database: " . $conn->error . "<br>";
                    $upload_success = false;
                    break;
                }
            }

            if ($upload_success) {
                echo "Success: All files have been uploaded and database updated.<br>";
                // Redirect to event details page with the correct link
                header("Location: ../event_details.html?link=" . urlencode($event_link));
                exit;
            }
        } else {
            echo "Error: No files were successfully uploaded.<br>";
        }
    } else {
        echo "Error: Invalid event link.<br>";
    }
} else {
    echo "Error: No files uploaded or missing event link.<br>";
}
?>
