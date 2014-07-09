<table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
    <tr>
        <td><a href="/yongo/administration/project/edit/<?php echo $request->get('id') ?>" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit</a></td>
        <td><a id="btnDeleteClientProjectSummary" href="#" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete</a></td>
    </tr>
</table>
<input type="hidden" id="project_id" value="<?php echo $request->get('id') ?>" />
<div id="deleteClientProject"></div>