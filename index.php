<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'classes/Database.php';
include 'classes/Task.php';
include 'classes/StatusHandler.php';

$database = new Database();
$taskObj = new Task($database);
$statusHandler = new StatusHandler();


if (!isset($_GET["orderBy"])) {
    $orderBy = 0;
} else {
    $orderBy = $_GET["orderBy"];
}

if (!isset($_GET["page"])) {
    $page = 1;
} else {
    $page = $_GET["page"];
}

if (!isset($_GET["itemsPerPage"])) {
    $itemsPerPage = 10;
} else {
    $itemsPerPage= $_GET["itemsPerPage"];
}

$tasks = $taskObj->getTasks($orderBy, $itemsPerPage, $page);

$status = $statusHandler->getStatus();

function humanReadableTimestamp($timestamp) {
    return date('F j, Y, g:i a', strtotime($timestamp));  // Example: "September 11, 2024, 10:12 am"
}

// can be called whenever order is updated
function renderTaskList($tasks, $taskObj) {
    echo "<ul>";
    foreach ($tasks as $task) {
        echo "<li>";
        switch ($task['priority']) {
        case 1:
            $priority_text = "Low";
            break;
        case 2:
            $priority_text = "Normal";
            break;
        case 3:
            $priority_text = "High";
            break;
        case 4:
            $priority_text = "Urgent";
            break;
        case 5:
            $priority_text = "Critical";
            break;
        }
        echo "<b>[" . $priority_text . "] </b>";
        echo "<strong>" . htmlspecialchars($task['description']) . "</strong> ";
        if (!empty($task['updated_at'])):
            echo "<em>Last updated at: " . humanReadableTimestamp($task['updated_at']) . "</em> ";
        else:
            echo "<em>Created at: " . humanReadableTimestamp($task['created_at']) . "</em> ";
        endif;
        echo "<a href='actions/delete_task.php?id=" . $task["id"]  . "&" . http_build_query($_GET) . "'>Delete</a> ";
        echo "<a href='edit.php?id=" . $task["id"] . "&" . http_build_query($_GET) . "'>Edit</a> ";
        echo "<a href='attachment.php?id=" . $task['id'] . "'>View " . $taskObj->getNumAttachments($task['id']) . " attachments</a>";
        echo "</li>";
    }
    echo "</ul>";
}

$tasks = $taskObj->getTasks($orderBy, $itemsPerPage, $page);

if (isset($_POST["itemsPerPage"])) {
    $itemsPerPage = $_POST["itemsPerPage"];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>To-Do List</h1>

<!-- General Status Messages -->
<?php if ($status): ?>
    <p style="color: <?php echo ($status['type'] == 'success') ? 'green' : 'red'; ?>;">
        <?php echo htmlspecialchars($status['message']); ?>
    </p>
<?php endif; ?>

<!-- priority dropdown - 'Normal' (2) is default -->
<form action="actions/add_task.php?<?php echo http_build_query($_GET); ?>" method="POST">
    <input type="text" name="task" placeholder="New Task" required>
    <select name="priority" id="priority">
        <option value="1" >Low</option>
        <option value="2" selected>Normal</option>
        <option value="3" >High</option>
        <option value="4" >Urgent</option>
        <option value="5" >Critical</option>
    </select>
    <button type="submit">Add Task</button>
</form>
<br>

<!-- order dropdown -->
<form method="GET" action="">
    <input type="hidden" name="page" value=<?php echo $page ?>>
    <select name="orderBy" id="orderBy" onchange="this.form.submit()">
        <option value="0" <?php if ($orderBy == 0) echo 'selected'; ?>>Order tasks by priority</option>
        <option value="1" <?php if ($orderBy == 1) echo 'selected'; ?>>Order tasks by date created</option>
    </select>

    <select name="itemsPerPage" id="itemsPerPage" onchange="this.form.submit()">
        <option value="5" <?php if ($itemsPerPage == 5) echo 'selected'; ?>>5</option>
        <option value="10" <?php if ($itemsPerPage == 10) echo 'selected'; ?>>10</option>
        <option value="15" <?php if ($itemsPerPage == 15) echo 'selected'; ?>>15</option>
        <option value="20" <?php if ($itemsPerPage == 20) echo 'selected'; ?>>20</option>
    </select>
</form>

<?php renderTaskList($tasks, $taskObj) ?>

<!-- navigation -->
<ul class="page-nav">
    <li><a href=<?php echo "'index.php?page=" . max($page - 1, 1) . "&orderBy=" . $orderBy . "&itemsPerPage=" . $itemsPerPage . "'" ?>>&lt;Prev&gt;</a></li>
    <?php 
    $pageCount =$taskObj->getPageCount($itemsPerPage); 
    for ($i = 1; $i < $pageCount + 1; $i++) {
        echo "<li>";
        if ($i == $page) {
            echo "<u>" . $i . "</u>";
        } else {
            echo "<a href='index.php?page=" . $i . "&orderBy=" . $orderBy . "&itemsPerPage=" . $itemsPerPage . "'>&lt;" . $i . "&gt;</a>";
        }
        echo "</li>";
    }
    ?>
    <li><a href=<?php echo "'index.php?page=" . min($page + 1, $pageCount) . "&orderBy=" . $orderBy . "&itemsPerPage=" . $itemsPerPage . "'" ?>>&lt;Next&gt;</a></li>
</ul>

</body>
</html>