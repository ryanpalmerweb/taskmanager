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
<p>Current task priority: 
<?php
    switch ($task['priority']) {
    case 1:
        echo "Low";
        break;
    case 2:
        echo "Normal";
        break;
    case 3:
        echo "High";
        break;
    case 4:
        echo "Urgent";
        break;
    case 5:
        echo "Critical";
        break;
    }
?></p>

<!-- Form for editing the task -->
<form action="actions/edit_task.php?<?php echo http_build_query($_GET); ?>" method="POST"> <!-- Updated action target -->
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($task['id']); ?>">
    <label for="task">New Task Description:</label>
    <input type="text" id="task" name="task" value="<?php echo htmlspecialchars($task['description']); ?>" required>
    <br>
    <label for="priority">New Task Priority:</label>
    <select name="priority" id="priority">
        <option value="1" <?php if ($task['priority'] == 1) echo 'selected'; ?>>Low</option>
        <option value="2" <?php if ($task['priority'] == 2) echo 'selected'; ?>>Normal</option>
        <option value="3" <?php if ($task['priority'] == 3) echo 'selected'; ?>>High</option>
        <option value="4" <?php if ($task['priority'] == 4) echo 'selected'; ?>>Urgent</option>
        <option value="5" <?php if ($task['priority'] == 5) echo 'selected'; ?>>Critical</option>
    </select>
    <button type="submit">Save Task</button>
</form>

</body>
</html>
