<?php
// Connect to database
$servername = "localhost:3306";
$username = "root";
$password = "admin";
$dbname = "attendance_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$studentName = $_POST['studentName'];
$studentID = $_POST['studentID'];
$imageData = isset($_POST['imageData']) ? $_POST['imageData'] : ''; // Check if imageData is set

// Insert student data into database
$sql = "INSERT INTO students (name, student_id_number) VALUES ('$studentName', '$studentID')";
if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    // Save image to server
    if (!empty($imageData)) {
        $img = str_replace('data:image/jpeg;base64,', '', $imageData);
        $img = str_replace(' ', '+', $img);
        $fileData = base64_decode($img);
        $fileName = 'student_' . $last_id . '.jpg';
        $directory = 'student_images/';

        // Create directory if it doesn't exist
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true); // Creates the directory recursively
        }

        $filePath = $directory . $fileName;
        if (file_put_contents($filePath, $fileData)) {
            echo "Attendance recorded successfully!";
        } else {
            echo "Failed to save image.";
        }
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
