<?php
include 'db.php';

session_start();
$user_id = $_SESSION['ID'] ?? null;

if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'fetch':
        fetchEvents($conn, $user_id);
        break;
    case 'add':
        addEvent($conn, $user_id);
        break;
    case 'update':
        updateEvent($conn, $user_id);
        break;
    case 'delete':
        deleteEvent($conn, $user_id);
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}

// Fetch events for the logged-in user
function fetchEvents($conn, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM events WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($events);
}

// Add event for the logged-in user
function addEvent($conn, $user_id) {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $conn->prepare("INSERT INTO events (title, time_from, time_to, day, month, year, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['title'],
        $data['time_from'],
        $data['time_to'],
        $data['day'],
        $data['month'],
        $data['year'],
        $user_id
    ]);
    echo json_encode(['status' => 'success', 'message' => 'Event added']);
}

// Update event for the logged-in user
function updateEvent($conn, $user_id) {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $conn->prepare("UPDATE events SET title = ?, time_from = ?, time_to = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([
        $data['title'],
        $data['time_from'],
        $data['time_to'],
        $data['id'],
        $user_id
    ]);
    echo json_encode(['status' => 'success', 'message' => 'Event updated']);
}

// Delete event for the logged-in user
function deleteEvent($conn, $user_id) {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ? AND user_id = ?");
    $stmt->execute([$data['id'], $user_id]);
    echo json_encode(['status' => 'success', 'message' => 'Event deleted']);
}
?>
