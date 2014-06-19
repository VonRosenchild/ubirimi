<?php
    $title = 'Completed Issues';
    $issues = $doneIssues;

    require_once __DIR__ . '/_reportSection.php';

    echo '<br />';

    $title = 'Issues Not Completed';
    $issues = $notDoneIssues;

    require __DIR__ . '/_reportSection.php';