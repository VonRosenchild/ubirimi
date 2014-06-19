<div class="headerPageText"><?php echo $title ?></div>
<?php if ($issues): ?>
    <table class="table table-hover table-condensed">
        <thead>
            <tr>
                <th width="10%">Key</th>
                <th width="60%">Summary</th>
                <th width="10%">Issue Type</th>
                <th width="10%">Priority</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($issue = $issues->fetch_array(MYSQLI_ASSOC)): ?>
                <tr>
                    <td><a href="/yongo/issue/<?php echo $issue['id'] ?>"><?php echo $issue['project_code'] . '-' . $issue['nr'] ?></a></td>
                    <td><?php echo $issue['summary'] ?></td>
                    <td><?php echo $issue['type_name'] ?></td>
                    <td><?php echo $issue['priority_name'] ?></td>
                    <td><?php echo $issue['status_name'] ?></td>
                </tr>
            <?php endwhile ?>
        </tbody>
    </table>
<?php else: ?>
    <div>There are no completed issues.</div>
<?php endif ?>