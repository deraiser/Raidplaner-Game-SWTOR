<?php

namespace rp\system\event\listener;

use rp\data\event\raid\attendee\EventRaidAttendee;
use rp\data\game\GameCache;
use rp\data\raid\RaidEditor;
use rp\system\cache\runtime\CharacterRuntimeCache;
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
 * Extended raid editor
 * 
 * @author      Marco Daries
 * @package     Daries\RP\System\Event\Listener
 */
class SWTORRaidEditorListener implements IParameterizedEventListener
{

    protected function addAttendees(array &$parameters): void
    {
        $attendeeIDs = $parameters['attendeeIDs'];

        foreach ($attendeeIDs as $attendeeID) {
            [$characterID, $fightStyleID] = \explode('_', $attendeeID, 2);

            $character = CharacterRuntimeCache::getInstance()->getObject($characterID);
            if ($character === null) continue;
            
            $fightStyles = $character->fightStyles;
            if (!isset($fightStyles[$fightStyleID])) continue;

            $fightStyle = $fightStyles[$fightStyleID];

            $parameters['attendees'][] = new EventRaidAttendee(null, [
                'characterID' => $character->characterID,
                'characterName' => $character->characterName,
                'classificationID' => $fightStyle['classificationID'],
                'roleID' => $fightStyle['roleID'],
            ]);
        }
    }

    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters): void
    {
        if (GameCache::getInstance()->getCurrentGame()->identifier !== 'swtor') return;
        if (!($eventObj instanceof RaidEditor)) return;

        switch ($eventName) {
            case 'addAttendees':
                $this->addAttendees($parameters);
                break;
        }
    }
}
