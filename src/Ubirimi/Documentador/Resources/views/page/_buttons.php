<?php
    use Ubirimi\Util;

?>
<table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
    <tr>
        <td><a href="/documentador/page/edit/<?php echo $entityId ?>" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit</a></td>
        <td><a href="/documentador/administration/space-tools/overview/<?php echo $spaceId ?>" class="btn ubirimi-btn">Space Tools</a></td>
        <td><a href="#" id="btnDocumentatorCreate" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Child Page</a></td>
        <td><a href="#" id="btnPageChildren" class="btn ubirimi-btn dropdown-toggle<?php if (!$childPages) echo ' disabled' ?>">Child Pages <span class="caret"></a></td>
        <td>
            <div class="btn-group">
                <a href="#" class="btn ubirimi-btn dropdown-toggle" data-toggle="dropdown">
                    Tools <span class="caret"></span>
                </a>
                <?php if (Util::checkUserIsLoggedIn()): ?>
                    <ul class="dropdown-menu">
                        <li><a href="/documentador/page/attachments/<?php echo $entityId ?>">Attachments</a></li>
                        <li><a href="/documentador/page/history/<?php echo $entityId ?>">Page History</a></li>
                        <li><span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span></li>

                        <li><a id="page_export_to_pdf" href="/documentador/page/export-pdf/<?php echo $entityId ?>">Export to PDF</a></li>
                        <li>
                            <?php if ($page['fav_id']): ?>
                                <a id="page_remove_favourite" href="#">Remove Favourite</a>
                            <?php else: ?>
                                <a id="page_make_favourite" href="#">Favourite</a>
                            <?php endif ?>
                        </li>
                        <li><span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span></li>
                        <li><a id="doc_page_remove" href="#">Remove</a></li>
                    </ul>
                <?php endif ?>
            </div>
        </td>
    </tr>
</table>