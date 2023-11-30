<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_marks";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function fetchDataForUser($userId)
{
    global $conn;
    $userId = $conn->real_escape_string($userId);
    $query = "SELECT * FROM marks WHERE name= '$userId'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

header('Content-Type: application/json');

if (isset($_POST['userId'])) {
    $userId = $_POST['userId'];
    $userData = fetchDataForUser($userId);
    echo json_encode($userData);
} else {
    echo json_encode(null);
}

$conn->close();
