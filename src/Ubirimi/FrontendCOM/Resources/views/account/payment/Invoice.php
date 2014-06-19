<div class="container page-container profile" style="width: 1000px; height: 500px;">
    <p class="leading">
        <?php require_once __DIR__ . '/../_menu.php' ?>
    </p>
    <?php if (0 == count($invoices)): ?>
        You have no invoices yet.
    <?php else: ?>
        Here are the last <strong><?php echo count($invoices) ?></strong> invoices
        <br />
        <br />
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td>
                        <?php echo $invoice['number'] ?>
                    </td>
                    <td>
                        <?php echo date('d F Y', strtotime($invoice['date_created'])) ?>
                    </td>
                    <td>
                        $ <?php echo $invoice['amount'] ?>
                    </td>
                    <td>
                        <a href="/assets/invoice/Ubirimi_<?php echo $invoice['number'] ?>.pdf">Download</a>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>
</div>