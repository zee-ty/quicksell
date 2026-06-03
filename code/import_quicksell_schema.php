<?php
// One-time QuickSell schema importer.
// Run this once and then delete or rename it for security.

$sqlFile = __DIR__ . '/../quicksell_schema.sql';
if (!file_exists($sqlFile)) {
    die('Schema file not found: ' . htmlspecialchars($sqlFile));
}

$sql = file_get_contents($sqlFile);
$mysqli = new mysqli('localhost', 'root', '');
if ($mysqli->connect_errno) {
    die('MySQL connection failed: ' . $mysqli->connect_error);
}

if (!$mysqli->multi_query($sql)) {
    die('Import failed: ' . $mysqli->error);
}

// Drain the results.
do {
    if ($result = $mysqli->store_result()) {
        $result->free();
    }
} while ($mysqli->more_results() && $mysqli->next_result());

echo '<p>QuickSell database created and schema imported successfully.</p>';
echo '<p>Delete this file once done: <code>code/import_quicksell_schema.php</code></p>';
