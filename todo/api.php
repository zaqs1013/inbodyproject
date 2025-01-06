<?php
include 'db.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'fetch':
        fetchEvents($conn);
        break;
    case 'add':
        addEvent($conn);
        break;
    case 'update':
        updateEvent($conn);
        break;
    case 'delete':
        deleteEvent($conn);
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}

// Fetch events
function fetchEvents($conn) {
    $stmt = $conn->prepare("SELECT * FROM events");
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($events);
}

// Add event
function addEvent($conn) {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $conn->prepare("INSERT INTO events (title, time_from, time_to, day, month, year) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['title'],
        $data['time_from'],
        $data['time_to'],
        $data['day'],
        $data['month'],
        $data['year']
    ]);
    echo json_encode(['status' => 'success', 'message' => 'Event added']);
}

// Update event
function updateEvent($conn) {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $conn->prepare("UPDATE events SET title = ?, time_from = ?, time_to = ? WHERE id = ?");
    $stmt->execute([
        $data['title'],
        $data['time_from'],
        $data['time_to'],
        $data['id']
    ]);
    echo json_encode(['status' => 'success', 'message' => 'Event updated']);
}

// Delete event
function deleteEvent($conn) {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$data['id']]);
    echo json_encode(['status' => 'success', 'message' => 'Event deleted']);
}
?>
