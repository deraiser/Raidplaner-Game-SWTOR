<div class="section swtorCharacterProfileAbout">
    <h2 class="contentItemTitle">{lang}rp.character.swtor.class.about{/lang}</h2>
    
    <div class="contentItemList">
        {foreach from=$classes key='__key' item='class'}
            <div class="contentItem contentItemMultiColumn">
                <div class="contentItemLink">
                    <div class="contentItemContent">
                        <h2 class="contentItemTitle">{lang}rp.character.swtor.class{@$__key}{/lang}</h2>

                        <div class="contentItemDescription">
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
                    </div>
                </div>
            </div>
        {/foreach}
    </div>
</div>