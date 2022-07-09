<div class="section swtorCharacterProfileAbout">
    <h2 class="contentItemTitle">{lang}rp.character.swtor.fightStyle.about{/lang}</h2>
    
    <div class="contentItemList">
        {foreach from=$fightStyles key='__key' item='fightStyle'}
            <div class="contentItem contentItemMultiColumn">
                <div class="contentItemLink">
                    <div class="contentItemContent">
                        <h2 class="contentItemTitle">{lang}rp.character.swtor.fightStyle{@$__key}{/lang}</h2>

                        <div class="contentItemDescription">
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
                    </div>
                </div>
            </div>
        {/foreach}
    </div>
</div>