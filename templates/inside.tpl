{include file='header.tpl'}

        <div class="about">
            <h1>{$h1}</h1>
            {if $module == 'main'}
                {$Content}
            {else}
                {include file=$template}
            {/if}


        </div><!--/#about-->


{include file='bottom.tpl'}