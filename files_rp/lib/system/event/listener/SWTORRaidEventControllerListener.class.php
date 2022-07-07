<?php

namespace rp\system\event\listener;

use rp\data\character\CharacterProfile;
use rp\data\event\Event;
use rp\data\game\GameCache;
use rp\system\event\RaidEventController;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\IntegerFormField;
use wcf\system\form\builder\field\SingleSelectionFormField;
use wcf\util\StringUtil;

/*  Project:    Raidplaner: Game: SWTOR
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
 * Extended raid event controller
 * 
 * @author      Marco Daries
 * @package     Daries\RP\System\Event\Listener
 */
class SWTORRaidEventControllerListener implements IParameterizedEventListener
{

    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters): void
    {
        if (GameCache::getInstance()->getCurrentGame()->identifier !== 'swtor') return;
        if (!($eventObj instanceof RaidEventController)) return;

        switch ($eventName) {
            case 'availableCharacters':
                $this->availableCharacters($eventObj, $parameters);
                break;
            case 'beforeSetFormObjectData':
                $parameters = \array_merge(
                    [
                        'requiredLevel',
                        'requiredItemLevel',
                        'requiredImplants',
                        'requiredUpgradeBlue',
                        'requiredUpgradePurple',
                        'requiredUpgradeGold',
                    ],
                    $parameters,
                );
                break;
            case 'createForm':
                $this->createForm($parameters);
                break;
        }
    }

    protected function availableCharacters(RaidEventController $eventObj, array &$parameters): void
    {
        $parameters['availableCharacters'] = [];

        /** @var Event $event */
        $event = $eventObj->getEvent();

        /** @var CharacterProfile $character */
        foreach ($parameters['characters'] as $characterID => $character) {
            $classes = $character->classes;
            foreach ($classes as $class) {
                if (!$class['classEnable']) continue;

                foreach (['requiredItemLevel', 'requiredImplants'] as $required) {
                    $name = StringUtil::firstCharToLowerCase(\str_replace('required', '', $required));

                    if ($event->{$required} == 0) continue;
                    if ($class[$name] < $event->{$required}) {
                        continue 2;
                    }
                }

                $highUpgradeCount = 0;
                $notAccess = false;
                foreach (['requiredUpgradeGold', 'requiredUpgradePurple', 'requiredUpgradeBlue'] as $required) {
                    $name = StringUtil::firstCharToLowerCase(\str_replace('required', '', $required));

                    if ($event->{$required} !== 0) {
                        if ($class[$name] < $event->{$required}) {
                            $max = $highUpgradeCount + $class[$name];
                            if ($max < 14) $notAccess = true;
                            break;
                        }
                    }

                    $highUpgradeCount += $class[$name];
                }

                if (!$notAccess) {
                    $parameters['availableCharacters'][] = [
                        'characterID' => $character->characterID,
                        'characterName' => $character->getTitle(),
                        'classificationID' => $class['classificationID'],
                        'icon' => $character->getAvatar()->getImageTag(16),
                        'roleID' => $class['roleID'],
                        'userID' => $character->userID,
                    ];
                }
            }
        }
    }

    protected function createForm(array &$parameters): void
    {
        $form = $parameters['form'];

        /** @var FormContainer $conditionContainer */
        $conditionContainer = $form->getNodeById('condition');
        $conditionContainer->appendChildren([
                IntegerFormField::create('requiredLevel')
                ->label('rp.character.swtor.level')
                ->minimum(0)
                ->maximum(80)
                ->value(0),
                IntegerFormField::create('requiredItemLevel')
                ->label('rp.character.swtor.itemLevel')
                ->minimum(0)
                ->maximum(330)
                ->value(0),
                SingleSelectionFormField::create('requiredImplants')
                ->label('rp.character.swtor.implants')
                ->options(function () {
                    return [
                    '0' => 'rp.character.swtor.implants.0',
                    '1' => 'rp.character.swtor.implants.1',
                    '2' => 'rp.character.swtor.implants.2'
                    ];
                }),
                IntegerFormField::create('requiredUpgradeBlue')
                ->label('rp.character.swtor.upgradeBlue')
                ->minimum(0)
                ->maximum(14)
                ->value(0),
                IntegerFormField::create('requiredUpgradePurple')
                ->label('rp.character.swtor.upgradePurple')
                ->minimum(0)
                ->maximum(14)
                ->value(0),
                IntegerFormField::create('requiredUpgradeGold')
                ->label('rp.character.swtor.upgradeGold')
                ->minimum(0)
                ->maximum(14)
                ->value(0)
        ]);
    }
}
