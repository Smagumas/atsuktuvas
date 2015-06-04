<div class="newsa no-margin">
    <h1>NAUJIENOS TEST</h1>
    {foreach from=$news item=newItem}
        <div class="line5"></div>
        <div>
            <span>{$newItem.Date_Created} |</span> <strong><a href="{$baseLink}/{$newItem.Alias}">
                    {$newItem.Title}</a></strong>
        </div>
        <br>
        <img src="{$newItem.Image}" width="200" style="float: left;margin-right: 10px;" />
        {$newItem.Short}<p><a href="{$baseLink}/{$newItem.Alias}">{$tr_read_more}</a>
        </p>
        <div style="clear: both;"></div>
    {/foreach}
    <div style="clear: both;"></div>
    <div class="pages">{$pages}</div>
</div><!--/#news-->