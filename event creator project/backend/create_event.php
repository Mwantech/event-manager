<?php
include 'db.php';

$name = $_POST['name'];
$date = $_POST['date'];
$time = $_POST['time'];
$location = $_POST['location'];
$description = $_POST['description'];
$unique_link = md5(uniqid($name, true));

$query = "INSERT INTO events (event_name, event_date, event_time, location, description, unique_link) VALUES ('$name', '$date', '$time', '$location', '$description', '$unique_link')";
$result = mysqli_query($conn, $query);

if ($result) {
    $event_url = "http://" . $_SERVER['HTTP_HOST'] . "/event creator project/event_details.html?link=" . urlencode($unique_link);
    
    // Display success message with sharing options
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Event Created Successfully</title>
        <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
    </head>
    <body class='bg-gray-100'>
        <div class='max-w-2xl mx-auto p-6 bg-white shadow-lg mt-10 rounded-lg'>
            <h1 class='text-3xl font-bold mb-6 text-gray-800'>Event Created Successfully</h1>
            <p class='mb-4'>Your event has been created. Here's the unique link to share:</p>
            <input type='text' value='$event_url' class='w-full p-3 border border-gray-300 rounded-lg mb-4' readonly>
            <div class='flex space-x-4'>
                <a href='https://www.facebook.com/sharer/sharer.php?u=" . urlencode($event_url) . "' target='_blank' class='bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700'>Share on Facebook</a>
                <a href='https://twitter.com/intent/tweet?url=" . urlencode($event_url) . "' target='_blank' class='bg-blue-400 text-white px-4 py-2 rounded-lg hover:bg-blue-500'>Share on Twitter</a>
                <button onclick='copyToClipboard()' class='bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600'>Copy Link</button>
            </div>
            <a href='../upload_media.html?link=$unique_link' class='block mt-6 text-center bg-green-500 text-white p-3 rounded-lg hover:bg-green-600'>Continue to Upload Media</a>
        </div>
        <script>
        function copyToClipboard() {
            var copyText = document.querySelector('input[type=\"text\"]');
            copyText.select();
            document.execCommand('copy');
            alert('Link copied to clipboard!');
        }
        </script>
    </body>
    </html>";
} else {
    echo "Error creating event: " . mysqli_error($conn);
}
?>