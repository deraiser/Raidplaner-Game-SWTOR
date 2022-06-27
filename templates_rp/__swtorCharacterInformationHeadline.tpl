{if $popupClasses|isset}
    <div class="tabMenuContainer swtorCharacterInformationHeadline">
        <nav class="tabMenu">
            <ul>
                {foreach from=$popupClasses key='__key' item='class'}
                    <li><a href="#class{@$__key}">{lang}rp.character.swtor.class{@$__key}{/lang}</a></li>
                {/foreach}
            </ul>
        </nav>
    
        {foreach from=$popupClasses key='__key' item='class'}
            <div id="class{@$__key}" class="tabMenuContent">
                <dl class="plain dataList">
                    <dt>{lang}rp.classification.title{/lang}</dt>
                    <dd>{$class.classification}</dd>
                    <dt>{lang}rp.role.title{/lang}</dt>
                    <dd>{$class.role}</dd>
                    <dt>{lang}rp.character.swtor.itemLevel{/lang}</dt>
                    <dd>{#$class.itemLevel}</dd>
                    <dt>{lang}rp.character.swtor.implants{/lang}</dt>
                    <dd>{#$class.implants}</dd>
                    <dt>{lang}rp.character.swtor.upgradeBlue{/lang}</dt>
                    <dd>{#$class.upgradeBlue}</dd>
                    <dt>{lang}rp.character.swtor.upgradePurple{/lang}</dt>
                    <dd>{#$class.upgradePurple}</dd>
                    <dt>{lang}rp.character.swtor.upgradeGold{/lang}</dt>
                    <dd>{#$class.upgradeGold}</dd>
               </dl>
            </div>
        {/foreach}
    </div>
{/if}