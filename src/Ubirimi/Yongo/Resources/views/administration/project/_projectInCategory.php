<?php if ($projectCategories): ?>
    <?php while ($category = $projectCategories->fetch_array(MYSQLI_ASSOC)): ?>
        <h3><?php echo $category['name'] ?></h3>

        <?php
            $found = false;
            $categoryId = $category['id'];
        ?>
        <?php for ($i = 0; $i < count($projects); $i++): ?>
            <?php if ($projects[$i]['category_id'] == $categoryId): ?>
                <?php $found = true; ?>
            <?php endif ?>
        <?php endfor ?>

        <?php if ($found): ?>
            <?php require __DIR__ . '/_projectList.php' ?>
        <?php else: ?>
            <div>There are no projects in this category.</div>
        <?php endif ?>
    <?php endwhile ?>
<?php endif ?>


<?php $found = false ?>
<?php for ($i = 0; $i < count($projects); $i++): ?>
    <?php if ($projects[$i]['category_id'] == null): ?>
        <?php $found = true; ?>
    <?php endif ?>
<?php endfor ?>

<?php if ($found): ?>
    <h3>Uncategorized projects</h3>
    <?php $categoryId = null; ?>
    <?php require __DIR__ . '/_projectList.php' ?>
<?php endif ?>