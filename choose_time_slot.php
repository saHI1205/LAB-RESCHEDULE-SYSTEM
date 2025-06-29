<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

include('db_connection.php');

// Fetch available time slots
$sql = "SELECT * FROM time_slots WHERE is_booked = 0";  // Fetch only available slots
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $slot_id = $_POST['slot_id'];
    
    // Mark the time slot as booked
    $sql = "UPDATE time_slots SET is_booked = 1 WHERE slot_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $slot_id);
    $stmt->execute();

    echo "Time slot successfully booked!";
}

?>

<h1>Choose Your Rescheduled Time Slot</h1>

<form method="POST">
    <select name="slot_id" required>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <option value="<?php echo $row['slot_id']; ?>"><?php echo $row['available_date'] . ' ' . $row['available_time']; ?></option>
        <?php } ?>
    </select>
    <button type="submit">Book Slot</button>
</form>
