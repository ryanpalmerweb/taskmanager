<?php
// edit.php
include 'classes/Database.php';
include 'classes/Task.php';
include 'classes/StatusHandler.php';

$database = new Database();
$taskObj = new Task($database);
$statusHandler = new StatusHandler();

$id = $_GET['id'] ?? null;

if ($id === null) {
    $statusHandler->setStatus('error', 'No task ID provided.');
    header("Location: index.php");
    exit;
}

// Fetch the task by ID
$task = $taskObj->getTaskById($id);

if ($task === false) {
    $statusHandler->setStatus('error', 'Task not found.');
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
</head>
<body>
<h1>Edit Task</h1>

<!-- Display current task info -->
<p>Current task id: <?php echo htmlspecialchars($task['id']); ?></p>
<p>Current task message: <?php echo htmlspecialchars($task['description']); ?></p>

<!-- Form for editing the task -->
<form action="actions/edit_task.php" method="POST"> <!-- Updated action target -->
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($task['id']); ?>">
    <label for="task">New Task Description:</label>
    <input type="text" id="task" name="task" value="<?php echo htmlspecialchars($task['description']); ?>" required>
    <button type="submit">Save Task</button>
</form>

</body>
</html>
