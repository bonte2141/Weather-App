<?php
include 'db.php';

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_id = $_POST['request_id'];

    try {
        $stmt = $conn->prepare("SELECT * FROM requests WHERE request_id = :request_id");
        $stmt->bindParam(':request_id', $request_id);
        $stmt->execute();
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        $data = json_decode($request['data'], true);

        if ($request['action_type'] === 'add') {
            if ($request['page_name'] === 'hazardtracking') {
                $stmt = $conn->prepare("INSERT INTO hazard_tracking (hazard_name, status, location, hazard_icon) VALUES (:hazard_name, :status, :location, :hazard_icon)");
                $stmt->bindParam(':hazard_name', $data['hazard_name']);
                $stmt->bindParam(':status', $data['status']);
                $stmt->bindParam(':location', $data['location']);
                $stmt->bindParam(':hazard_icon', $data['hazard_icon']);
            } else {
                $stmt = $conn->prepare("INSERT INTO real_time_weather (country, city, temp, `condition`, flag_image) VALUES (:country, :city, :temp, :condition, :flag_image)");
                $stmt->bindParam(':country', $data['country']);
                $stmt->bindParam(':city', $data['city']);
                $stmt->bindParam(':temp', $data['temp']);
                $stmt->bindParam(':condition', $data['condition']);
                $stmt->bindParam(':flag_image', $data['flag_image']);
            }
            $stmt->execute();
        } elseif ($request['action_type'] === 'edit') {
            if ($request['page_name'] === 'hazardtracking') {
                $stmt = $conn->prepare("UPDATE hazard_tracking SET hazard_name = :hazard_name, status = :status, location = :location, hazard_icon = :hazard_icon WHERE hazard_id = :hazard_id");
                $stmt->bindParam(':hazard_name', $data['hazard_name']);
                $stmt->bindParam(':status', $data['status']);
                $stmt->bindParam(':location', $data['location']);
                $stmt->bindParam(':hazard_icon', $data['hazard_icon']);
                $stmt->bindParam(':hazard_id', $data['hazard_id']);
            } else {
                $stmt = $conn->prepare("UPDATE real_time_weather SET country = :country, city = :city, temp = :temp, `condition` = :condition, flag_image = :flag_image WHERE entry_id = :entry_id");
                $stmt->bindParam(':country', $data['country']);
                $stmt->bindParam(':city', $data['city']);
                $stmt->bindParam(':temp', $data['temp']);
                $stmt->bindParam(':condition', $data['condition']);
                $stmt->bindParam(':flag_image', $data['flag_image']);
                $stmt->bindParam(':entry_id', $data['entry_id']);
            }
            $stmt->execute();
        } elseif ($request['action_type'] === 'delete') {
            if ($request['page_name'] === 'hazardtracking') {
                $stmt = $conn->prepare("DELETE FROM hazard_tracking WHERE hazard_id = :hazard_id");
                $stmt->bindParam(':hazard_id', $data['hazard_id']);
            } else {
                $stmt = $conn->prepare("DELETE FROM real_time_weather WHERE entry_id = :entry_id");
                $stmt->bindParam(':entry_id', $data['entry_id']);
            }
            $stmt->execute();
        }

        $stmt = $conn->prepare("UPDATE requests SET status = 'approved' WHERE request_id = :request_id");
        $stmt->bindParam(':request_id', $request_id);
        $stmt->execute();

        $response['message'] = "Request approved successfully";
    } catch (PDOException $e) {
        $response['error'] = $e->getMessage();
    }
}

header('Content-Type: application/json');
echo json_encode($response);

$conn = null;
?>
