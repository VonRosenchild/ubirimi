<table class="table table-hover table-condensed">
    <thead>
        <tr>
            <th width="20px"></th>
            <th>Name</th>
            <th width="10%">Modified</th>
            <th width="10%">Options</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($file = $pageFiles->fetch_array(MYSQLI_ASSOC)): ?>
            <tr>
                <td>
                    <img id="details_file_<?php echo $file['id'] ?>" src="/img/plus.png" width="16px" />
                </td>
                <td><?php echo $file['name'] ?></td>
                <td><?php echo $file['date_created'] ?></td>
                <td>
                    <a class="btn ubirimi-btn" href="#" id="delete_doc_file_<?php echo $file['id'] ?>">Delete</a>
                </td>
            </tr>
            <tr id="details_file_content_<?php echo $file['id'] ?>">
            </tr>
        <?php endwhile ?>
    </tbody>
</table>