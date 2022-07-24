<?php

namespace rp\system\event\listener;

use rp\data\character\CharacterList;
use rp\data\character\CharacterProfile;
use rp\data\classification\ClassificationCache;
use rp\data\game\GameCache;
use rp\data\role\RoleCache;
use rp\form\RaidAddForm;
use wcf\system\event\listener\IParameterizedEventListener;

/**
 *  Project:    Raidplaner: Game: SWTOR
 *  Package:    info.daries.rp.game.swtor
 *  Link:       http://daries.info
 *
 *  Copyright (C) 2018-2022 Daries.info Developer Team
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published
 *  by the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Extended raid add form
 * 
 * @author      Marco Daries
 * @package     Daries\RP\System\Event\Listener
 */
class SWTORRaidAddFormListener implements IParameterizedEventListener
{

    protected function attendeesCreateForm(array &$parameters): void
    {
        $charactersFormField = $parameters['charactersFormField'];
        $parameters['fieldChanged'] = true;

        $characterList = new CharacterList();
        $characterList->getConditionBuilder()->add('member.gameID = ?', [RP_DEFAULT_GAME_ID]);
        $characterList->getConditionBuilder()->add('member.isDisabled = ?', [0]);
        $characterList->readObjects();

        $options = [];
        /** @var CharacterProfile $character */
        foreach ($characterList->getObjects() as $characterID => $character) {
            $fightStyles = $character->fightStyles;
            foreach ($fightStyles as $fightStyleID => $fightStyle) {
                if (!$fightStyle['fightStyleEnable']) continue;

                $id = $character->characterID . '_' . $fightStyleID;

                $label = '';
                $classification = ClassificationCache::getInstance()->getClassificationByID($fightStyle['classificationID']);
                if ($classification) {
                    $label = $classification->getTitle();
                }

                $role = RoleCache::getInstance()->getRoleByID($fightStyle['roleID']);
                if ($role) {
                    if (!empty($label)) $label .= ', ';
                    $label .= $role->getTitle();
                }

                $options[] = [
                    'depth' => 0,
                    'label' => $character->getTitle() . ' (' . $label . ')',
                    'userID' => $character->userID,
                    'value' => $id,
                ];
            }
        }

        $charactersFormField->options($options, true);
    }

    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters): void
    {
        if (GameCache::getInstance()->getCurrentGame()->identifier !== 'swtor') return;
        if (!($eventObj instanceof RaidAddForm)) return;

        switch ($eventName) {
            case 'attendeesCreateForm':
                $this->attendeesCreateForm($parameters);
                break;
        }
    }
}
