{if $popupFightStyles|isset}
    {foreach from=$popupFightStyles key='__key' item='fightStyle'}
        <li><a href="#fightStyle{@$__key}">{lang}rp.character.swtor.fightStyle{@$__key}{/lang}</a></li>
    {/foreach}
{/if}