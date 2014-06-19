<script type="text/javascript">

    <?php
        if ($allTags) {
            $allTags->data_seek(0);
            while ($tag = $allTags->fetch_array(MYSQLI_ASSOC)) {
                echo "availableTags.push('" . $tag['name'] . "');";
            }
        }
    ?>
</script>