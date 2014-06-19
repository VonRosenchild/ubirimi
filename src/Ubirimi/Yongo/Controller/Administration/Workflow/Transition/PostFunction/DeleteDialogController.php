<?php
    $deletable = $_GET['deletable'];
    if ($deletable)
        echo 'Are you sure you want to delete this post function?';
    else
        echo 'This post function can not be deleted.';