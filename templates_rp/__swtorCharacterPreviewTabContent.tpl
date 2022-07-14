{if $popupFightStyles|isset}
    {foreach from=$popupFightStyles key='__key' item='fightStyle'}
        <div id="fightStyle{@$__key}" class="tabMenuContent">
            <dl class="plain dataList">
                <dt>{lang}rp.classification.title{/lang}</dt>
                <dd>{$fightStyle.classification}</dd>
                <dt>{lang}rp.role.title{/lang}</dt>
                <dd>{$fightStyle.role}</dd>
                <dt>{lang}rp.character.swtor.itemLevel{/lang}</dt>
                <dd>{#$fightStyle.itemLevel}</dd>
                <dt>{lang}rp.character.swtor.implants{/lang}</dt>
                <dd>{#$fightStyle.implants}</dd>
                <dt>{lang}rp.character.swtor.upgradeBlue{/lang}</dt>
                <dd>{#$fightStyle.upgradeBlue}</dd>
                <dt>{lang}rp.character.swtor.upgradePurple{/lang}</dt>
                <dd>{#$fightStyle.upgradePurple}</dd>
                <dt>{lang}rp.character.swtor.upgradeGold{/lang}</dt>
                <dd>{#$fightStyle.upgradeGold}</dd>
           </dl>
        </div>
    {/foreach}
{/if}