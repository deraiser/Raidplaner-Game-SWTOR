<?php

namespace rp\system\event\listener;

use rp\data\character\CharacterProfileAction;
use rp\data\game\GameCache;
use rp\system\cache\runtime\CharacterProfileRuntimeCache;
use rp\util\SWTORUtil;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\WCF;

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
 * Extended character profile action's
 * 
 * @author      Marco Daries
 * @package     Daries\RP\System\Event\Listener
 */
class SWTORCharacterProfileActionListener implements IParameterizedEventListener
{

    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters): void
    {
        if (GameCache::getInstance()->getCurrentGame()->identifier !== 'swtor') return;
        if (!($eventObj instanceof CharacterProfileAction)) return;

        switch ($eventObj->getActionName()) {
            case 'getPopover':
                $this->getPopover($eventObj);
                break;
        }
    }

    protected function getPopover(CharacterProfileAction $eventObj): void
    {
        $characterID = $eventObj->getObjectIDs()[0] ?? 0;
        if (!$characterID) return;

        $characterProfile = CharacterProfileRuntimeCache::getInstance()->getObject($characterID);
        if ($characterProfile === null) return;

        WCF::getTPL()->assign('popupFightStyles', SWTORUtil::getClassArrayList($characterProfile->getDecoratedObject()));
    }
}
