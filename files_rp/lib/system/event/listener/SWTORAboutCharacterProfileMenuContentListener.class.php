<?php

namespace rp\system\event\listener;

use rp\data\classification\ClassificationCache;
use rp\data\game\GameCache;
use rp\data\role\RoleCache;
use rp\system\cache\runtime\CharacterProfileRuntimeCache;
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
 * Displays information about the character on the about page.
 * 
 * @author      Marco Daries
 * @package     Daries\RP\System\Event\Listener
 */
class SWTORAboutCharacterProfileMenuContentListener implements IParameterizedEventListener
{

    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters): void
    {
        if (GameCache::getInstance()->getCurrentGame()->identifier !== 'swtor') return;

        $characterID = isset($_REQUEST['id']) ? \intval($_REQUEST['id']) : 0;
        if ($characterID) {
            $character = CharacterProfileRuntimeCache::getInstance()->getObject($characterID);
            $classes = [];
            foreach ($character->classes as $key => $class) {
                if (!$class['classEnable']) continue;

                $classes[$key] = [
                    'classification' => ClassificationCache::getInstance()->getClassificationByID($class['classificationID'])?->getTitle() ?? '',
                    'role' => RoleCache::getInstance()->getRoleByID($class['roleID'])?->getTitle() ?? '',
                    'itemLevel' => $class['itemLevel'],
                    'implants' => $class['implants'],
                    'upgradeBlue' => $class['upgradeBlue'],
                    'upgradePurple' => $class['upgradePurple'],
                    'upgradeGold' => $class['upgradeGold'],
                ];
            }

            WCF::getTPL()->assign([
                'character' => $character,
                'classes' => $classes,
            ]);
        }
    }
}
