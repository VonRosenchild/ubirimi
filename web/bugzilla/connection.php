<?php

$connectionBugzilla = new \mysqli(
    '127.0.0.1',
    'root',
    '12wqasxz',
    'bugzilla'
);

$connectionUbirimi = new \mysqli(
    '127.0.0.1',
    'root',
    '12wqasxz',
    'yongo'
);

if ($connectionBugzilla->connect_errno) {
    throw new \Exception(
        sprintf(
            'Failed to connect to database Bugzilla (%d). Reason: %s',
            $connectionBugzilla->connect_errno,
            $connectionBugzilla->connect_error
        )
    );
}

if ($connectionUbirimi->connect_errno) {
    throw new \Exception(
        sprintf(
            'Failed to connect to database Ubirimi (%d). Reason: %s',
            $connectionUbirimi->connect_errno,
            $connectionUbirimi->connect_error
        )
    );
}

$query = "SET NAMES 'utf8'";

if ($stmt = $connectionBugzilla->prepare($query)) {
    $stmt->execute();
}

if ($stmt = $connectionUbirimi->prepare($query)) {
    $stmt->execute();
}