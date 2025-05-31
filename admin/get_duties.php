<?php
include '../db_connect.php';

if (isset($_GET['date'])) {
    $date = $_GET['date'];
    $sql = "SELECT po.name AS officer_name, dt.name AS duty_name
            FROM assigned_duties ad
            JOIN police_officers po ON ad.officer_id = po.id
            JOIN duty_types dt ON ad.duty_id = dt.id
            WHERE ad.duty_date = '$date'";

    $result = $conn->query($sql);

    $duties = [];
    while ($row = $result->fetch_assoc()) {
        $duties[] = $row;
    }

    echo json_encode($duties);
}
?>
