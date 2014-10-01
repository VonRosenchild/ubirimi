<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td id="sectDates" class="sectionDetail"><span class="sectionDetailTitle">Dates and Times</span></td>
    </tr>
    <tr>
        <td>
            Due After
            <input type="text"
                   class="inputText"
                   id="search_date_due_after"
                   style="width: 80px"
                   value="<?php if (isset($getSearchParameters['date_due_after'])) echo $getSearchParameters['date_due_after'] ?>"
                   name="search_date_due_after" />
        </td>
    </tr>
    <tr>
        <td>
            Due Before
            <input type="text"
                   class="inputText"
                   id="search_date_due_before" style="width: 80px"
                   value="<?php if (isset($getSearchParameters['date_due_before'])) echo $getSearchParameters['date_due_before'] ?>"
                   name="search_date_due_before" />
        </td>
    </tr>
    <tr>
        <td>
            Created After
            <input type="text"
                   class="inputText"
                   id="search_date_created_after"
                   style="width: 80px"
                   value="<?php if (isset($getSearchParameters['date_created_after'])) echo $getSearchParameters['date_created_after'] ?>"
                   name="search_date_created_after" />
        </td>
    </tr>
    <tr>
        <td>
            Created Before
            <input type="text"
                   class="inputText"
                   id="search_date_created_before"
                   style="width: 80px"
                   value="<?php if (isset($getSearchParameters['date_created_before'])) echo $getSearchParameters['date_created_before'] ?>"
                   name="search_date_created_before" />
        </td>
    </tr>
</table>
