<?php
    use Ubirimi\Repository\User\User;
    use Ubirimi\Repository\Client;
    use Ubirimi\Yongo\Repository\Issue\Issue;

    require_once __DIR__ . '/_header.php';
?>
<body>

<?php require_once __DIR__ . '/_topMenu.php'; ?>
<div class="pageContent">
    <table width="100%" class="headerPageBackground">
        <tr>
            <td>
                <div class="headerPageText">
                    Admin Home > Clients > Overview
                </div>
            </td>
        </tr>
    </table>

    <?php require_once __DIR__ . '/_menu.php' ?>

    <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
        <tr>
            <td><a id="btnDeleteClient" href="#" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete</a></td>
        </tr>
    </table>
    <?php while ($client = $clients->fetch_array(MYSQLI_ASSOC)): ?>
    <div class="pageContent" style="float: left; width: 320px; padding-top: 0px; height: 160px; margin: 10px; margin-top: 30px; padding-bottom: 30px; border: solid 1px; border-color: #cacaca;">
        <table class="table table-condensed" >
            <tr>
                <td style="border-top: 0px;">
                    <h2><?php echo $client['company_name'] ?></h2>
                    <strong><?php echo count(Client::getProjects($client['id'], 'array')) ?></strong> project,
                    <strong><?php echo User::getByClientId($client['id'])->num_rows ?></strong> users,

                    <strong>
                        <?php
                            $issues = Issue::getByParameters(array('client_id' => $client['id']));
                            if (null !== $issues) {
                                echo count($issues->fetch_all(MYSQLI_ASSOC));
                            } else {
                                echo '0';
                            }
                        ?>
                    </strong> issues
                    <br />
                    <?php echo $client['company_domain'] ?><br />
                    <?php echo $client['contact_email'] ?><br />
                    <?php echo date('d F', strtotime($client['date_created'])) ?><br />
                    INSTALLED: <?php if ($client['installed_flag']) echo 'YES'; else echo 'NO' ?>
                    <br />
                    <strong>DELETE</strong> <input type="checkbox" value="1" id="el_check_<?php echo $client['id'] ?>" />
                </td>
            </tr>
        </table>
    </div>
    <?php endwhile ?>

    <div id="modalDeleteClient"></div>

</div>
</body>
