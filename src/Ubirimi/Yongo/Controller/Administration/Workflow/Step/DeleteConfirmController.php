<?php
    $deletePossible = $_GET['delete_possible'];
    if ($deletePossible)
        echo 'Are you sure you want to delete this workflow step?';
    else {
        echo 'This step has incoming transitions. It can not be deleted';
    }
