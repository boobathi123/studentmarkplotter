<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_marks";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get values from the form
$name = $_POST['name'];
$dept = $_POST['dept'];
$subject1 = $_POST['subject1'];
$subject2 = $_POST['subject2'];
$subject3 = $_POST['subject3'];
$subject4 = $_POST['subject4'];
$total = $_POST['total'];

// Insert data into the database
$sql = "INSERT INTO marks (name, dept, subject1, subject2, subject3, subject4, total) VALUES ('$name', '$dept', '$subject1', '$subject2', '$subject3', '$subject4', '$total')";

if ($conn->query($sql) === TRUE) {
    echo "Record added successfully";

    // Fetch data for plotting
    $plottingData = fetchDataForPlotting(); // Replace with your own function to fetch data

    // Convert PHP array to JavaScript array
    echo "<script> var plotData = " . json_encode($plottingData) . ";</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

function fetchDataForPlotting() {
    // Replace this function with your own logic to fetch data from the database
    // For example, you might fetch the last inserted record for demonstration purposes
    global $conn;
    $result = $conn->query("SELECT * FROM marks ORDER BY id DESC LIMIT 1");
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return array($row['subject1'], $row['subject2'], $row['subject3'], $row['subject4']);
    } else {
        return array();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Visualization</title>
    <script src="https://d3js.org/d3.v5.min.js"></script>
</head>
<body>

    <div id="plot-container"></div>

    <script>
        // Check if the plotData variable is defined
        if (typeof plotData !== 'undefined' && plotData.length > 0) {
            // Set up the SVG container
            const svg = d3.select("#plot-container")
                .append("svg")
                .attr("width", 400)
                .attr("height", 200);

            // Create bars based on the data
            svg.selectAll("rect")
                .data(plotData)
                .enter()
                .append("rect")
                .attr("x", (d, i) => i * 80)
                .attr("y", d => 200 - d)
                .attr("width", 70)
                .attr("height", d => d)
                .attr("fill", "blue");
        }
    </script>

</body>
</html>
