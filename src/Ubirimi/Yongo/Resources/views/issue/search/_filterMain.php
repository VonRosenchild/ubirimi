<tr>
    <td align="left">
        <span>Search</span>
        <input id="search_query" class="inputText" type="text"
               style="width: 200px"
               value="<?php if (isset($getSearchParameters['search_query'])) echo $getSearchParameters['search_query'] ?>"
               name="query"/>
        <br/>
        <input id="search_summary_flag"
               type="checkbox" <?php if ((count($_GET) == 0) || (isset($getSearchParameters['summary_flag']) && $getSearchParameters['summary_flag'])) echo 'checked="checked"' ?>
               value="1" name="summary_flag"/>
        <label for="search_summary_flag">Summary</label>
        <input id="search_description_flag"
               type="checkbox" <?php if (isset($getSearchParameters['description_flag']) && $getSearchParameters['description_flag']) echo 'checked="checked"' ?>
               value="1" name="description_flag"/>
        <label for="search_description_flag">Description</label>
        <input id="search_comments_flag"
               type="checkbox" <?php if (isset($getSearchParameters['comments_flag']) && $getSearchParameters['comments_flag']) echo 'checked="checked"' ?>
               value="1" name="comments_flag"/>
        <label for="search_comments_flag">Comments</label>
    </td>
</tr>