<?php

namespace rp\util;

use rp\data\character\Character;
use rp\data\classification\ClassificationCache;
use rp\data\role\RoleCache;

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
 * Contains swtor-related functions.
 *
 * @author      Marco Daries
 * @package     Daries\RP\Util
 */
final class SWTORUtil
{

    /**
     * Returns an array with information about character specific fight style.
     */
    public static function getClassArrayList(Character $character): array
    {
        $fightStyles = [];
        foreach ($character->fightStyles as $key => $fightStyle) {
            if (!$fightStyle['fightStyleEnable']) continue;

            $fightStyles[$key] = [
                'classification' => ClassificationCache::getInstance()->getClassificationByID($fightStyle['classificationID'])?->getTitle() ?? '',
                'role' => RoleCache::getInstance()->getRoleByID($fightStyle['roleID'])?->getTitle() ?? '',
                'itemLevel' => $fightStyle['itemLevel'],
                'implants' => $fightStyle['implants'],
                'upgradeBlue' => $fightStyle['upgradeBlue'],
                'upgradePurple' => $fightStyle['upgradePurple'],
                'upgradeGold' => $fightStyle['upgradeGold'],
            ];
        }

        return $fightStyles;
    }
}
