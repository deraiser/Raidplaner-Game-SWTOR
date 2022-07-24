<?php

use rp\data\game\GameCache;
use rp\data\point\account\PointAccount;
use rp\data\point\account\PointAccountEditor;
use rp\data\raid\event\RaidEventAction;
use rp\data\raid\event\RaidEventEditor;
use wcf\data\language\item\LanguageItemAction;
use wcf\data\package\PackageCache;
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;
use wcf\util\StringUtil;

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
 * @author      Marco Daries
 * @package     Daries\RP
 */
// Default Rank
$sql = "INSERT INTO rp" . WCF_N . "_rank
                    (rankName, gameID, showOrder, isDefault)
        VALUES      (?, ?, ?, ?)";
$statement = WCF::getDB()->prepareStatement($sql);
$statement->execute([
    'Default',
    GameCache::getInstance()->getGameByIdentifier('swtor')->gameID,
    1,
    1,
]);

/** @var PointAccount $pointAccount */
// raid events with point account 
$pointAccount = PointAccountEditor::create([
        'gameID' => GameCache::getInstance()->getGameByIdentifier('swtor')->gameID,
        'pointAccountName' => 'SWTOR 1.0',
        'showOrder' => 2
    ]);
insertEvent(getClassic(), $pointAccount);

$pointAccount = PointAccountEditor::create([
        'gameID' => GameCache::getInstance()->getGameByIdentifier('swtor')->gameID,
        'pointAccountName' => 'SWTOR 2.0',
        'showOrder' => 3
    ]);
insertEvent(getEvents(), $pointAccount);

$pointAccount = PointAccountEditor::create([
        'gameID' => GameCache::getInstance()->getGameByIdentifier('swtor')->gameID,
        'pointAccountName' => 'SWTOR 3.0',
        'showOrder' => 4
    ]);
insertEvent(getRevan(), $pointAccount);

$pointAccount = PointAccountEditor::create([
        'gameID' => GameCache::getInstance()->getGameByIdentifier('swtor')->gameID,
        'pointAccountName' => 'SWTOR 4.0',
        'showOrder' => 5
    ]);
insertEvent(getFallen(), $pointAccount);

$pointAccount = PointAccountEditor::create([
        'gameID' => GameCache::getInstance()->getGameByIdentifier('swtor')->gameID,
        'pointAccountName' => 'SWTOR 5.6',
        'showOrder' => 6
    ]);
insertEvent(getUprising(), $pointAccount);

$pointAccount = PointAccountEditor::create([
        'gameID' => GameCache::getInstance()->getGameByIdentifier('swtor')->gameID,
        'pointAccountName' => 'SWTOR 6.0',
        'showOrder' => 7
    ]);
insertEvent(getOnslaught(), $pointAccount);

$pointAccount = PointAccountEditor::create([
        'gameID' => GameCache::getInstance()->getGameByIdentifier('swtor')->gameID,
        'pointAccountName' => 'SWTOR 7.0',
        'showOrder' => 8
    ]);
insertEvent(getLotS(), $pointAccount);

function insertEvent(array $datas, PointAccount $pointAccount)
{
    foreach ($datas as $data) {
        $event = (new RaidEventAction([], 'create', [
                'data' => [
                    'gameID' => GameCache::getInstance()->getGameByIdentifier('swtor')->gameID,
                    'pointAccountID' => $pointAccount->pointAccountID,
                    'icon' => $data['icon'],
                    'showProfile' => 1,
                ]
                ]))->executeAction()['returnValues'];

        $eventEditor = new RaidEventEditor($event);
        $eventEditor->update(['eventName' => 'rp.raid.event.name' . $event->eventID]);

        insertLanguageItem($event->eventID, $data['name']);
    }
}

function insertLanguageItem(int $id, array $name)
{
    foreach (LanguageFactory::getInstance()->getLanguages() as $language) {
        if (!isset($name[$language->languageCode])) continue;

        (new LanguageItemAction([], 'create', [
            'data' => [
                'languageID' => $language->languageID,
                'languageItem' => 'rp.raid.event.name' . $id,
                'languageItemValue' => StringUtil::trim($name[$language->languageCode]),
                'languageCategoryID' => (LanguageFactory::getInstance()->getCategory('rp.raid.event'))->languageCategoryID,
                'packageID' => PackageCache::getInstance()->getPackageID('info.daries.rp'),
                'languageItemOriginIsSystem' => 1,
            ]
            ]))->executeAction();
    }
}

//Operation Swtor 1.0
function getClassic()
{
    return [
        [
            'name' => [
                'de' => 'Ewige Kammer (Story)',
                'en' => 'The Eternity Vault (Story)',
            ],
            'icon' => 'swtorStory',
        ],
        [
            'name' => [
                'de' => 'Ewige Kammer (Veteran)',
                'en' => 'The Eternity Vault (Veteran)',
            ],
            'icon' => 'swtorVeteran',
        ],
        [
            'name' => [
                'de' => 'Ewige Kammer (Meister)',
                'en' => 'The Eternity Vault (Master)',
            ],
            'icon' => 'swtorMaster',
        ],
        [
            'name' => [
                'de' => 'Karaggas Palast (Story)',
                'en' => 'Karaggas Palace (Story)',
            ],
            'icon' => 'swtorStory',
        ],
        [
            'name' => [
                'de' => 'Karaggas Palast (Veteran)',
                'en' => 'Karaggas Palace (Veteran)',
            ],
            'icon' => 'swtorVeteran',
        ],
        [
            'name' => [
                'de' => 'Karaggas Palast (Meister)',
                'en' => 'Karaggas Palace (Master)',
            ],
            'icon' => 'swtorMaster',
        ],
        [
            'name' => [
                'de' => 'Explosiv Konflikt (Story)',
                'en' => 'Explosiv Conflict (Story)',
            ],
            'icon' => 'swtorStory',
        ],
        [
            'name' => [
                'de' => 'Explosiv Konflikt (Veteran)',
                'en' => 'Explosiv Conflict (Veteran)',
            ],
            'icon' => 'swtorVeteran',
        ],
        [
            'name' => [
                'de' => 'Explosiv Konflikt (Meister)',
                'en' => 'Explosiv Conflict (Master)',
            ],
            'icon' => 'swtorMaster',
        ],
    ];
}

//Operation Swtor 2.0
function getEvents()
{
    return [
        [
            'name' => [
                'de' => 'Abschaum und Verkommenheit (Story)',
                'en' => 'Scum and Villainy (Story)',
            ],
            'icon' => 'swtorStory',
        ],
        [
            'name' => [
                'de' => 'Abschaum und Verkommenheit (Veteran)',
                'en' => 'Scum and Villainy (Veteran)',
            ],
            'icon' => 'swtorVeteran',
        ],
        [
            'name' => [
                'de' => 'Abschaum und Verkommenheit (Meister)',
                'en' => 'Scum and Villainy (Master)',
            ],
            'icon' => 'swtorMaster',
        ],
        [
            'name' => [
                'de' => 'Schrecken aus der Tiefe (Story)',
                'en' => 'Terror from Beyond (Story)',
            ],
            'icon' => 'swtorStory',
        ],
        [
            'name' => [
                'de' => 'Schrecken aus der Tiefe (Veteran)',
                'en' => 'Terror from Beyond (Veteran)',
            ],
            'icon' => 'swtorVeteran',
        ],
        [
            'name' => [
                'de' => 'Schrecken aus der Tiefe (Meister)',
                'en' => 'Terror from Beyond (Master)',
            ],
            'icon' => 'swtorMaster',
        ],
        [
            'name' => [
                'de' => 'Schreckensfestung (Story)',
                'en' => 'Dread Fortress (Story)',
            ],
            'icon' => 'swtorStory',
        ],
        [
            'name' => [
                'de' => 'Schreckensfestung (Veteran)',
                'en' => 'Dread Fortress (Veteran)',
            ],
            'icon' => 'swtorVeteran',
        ],
        [
            'name' => [
                'de' => 'Schreckensfestung (Meister)',
                'en' => 'Dread Fortress (Master)',
            ],
            'icon' => 'swtorMaster',
        ],
        [
            'name' => [
                'de' => 'Schreckenspalast (Story)',
                'en' => 'Dread Palace (Story)',
            ],
            'icon' => 'swtorStory',
        ],
        [
            'name' => [
                'de' => 'Schreckenspalast (Veteran)',
                'en' => 'Dread Palace (Veteran)',
            ],
            'icon' => 'swtorVeteran',
        ],
        [
            'name' => [
                'de' => 'Schreckenspalast (Meister)',
                'en' => 'Dread Palace (Master)',
            ],
            'icon' => 'swtorMaster',
        ],
        [
            'name' => [
                'de' => "Toborro's Hof (Story)",
                'en' => 'Golden Fury (Story)',
            ],
            'icon' => 'swtorStory',
        ],
        [
            'name' => [
                'de' => "Toborro's Hof (Veteran)",
                'en' => 'Golden Fury (Veteran)',
            ],
            'icon' => 'swtorVeteran',
        ],
    ];
}

//Operation Swtor 3.0
function getRevan()
{
    return [
        [
            'name' => [
                'de' => 'Die Wüter (Story)',
                'en' => 'The Ravagers (Story)',
            ],
            'icon' => 'swtorStory',
        ],
        [
            'name' => [
                'de' => 'Die Wüter (Veteran)',
                'en' => 'The Ravagers (Veteran)',
            ],
            'icon' => 'swtorVeteran',
        ],
        [
            'name' => [
                'de' => 'Die Wüter (Meister)',
                'en' => 'The Ravagers (Master)',
            ],
            'icon' => 'swtorMaster',
        ],
        [
            'name' => [
                'de' => 'Tempel des Opfers (Story)',
                'en' => 'Tempel of Sacrifice (Story)',
            ],
            'icon' => 'swtorStory',
        ],
        [
            'name' => [
                'de' => 'Tempel des Opfers (Veteran)',
                'en' => 'Tempel of Sacrifice (Veteran)',
            ],
            'icon' => 'swtorVeteran',
        ],
        [
            'name' => [
                'de' => 'Tempel des Opfers (Meister)',
                'en' => 'Tempel of Sacrifice (Master)',
            ],
            'icon' => 'swtorMaster',
        ],
    ];
}

//Operation Swtor 4.0 Fallen
function getFallen()
{
    return [
        [
            'name' => [
                'de' => 'Gewaltiger Monolith (Story)',
                'en' => 'Colossal Monolith (Story)',
            ],
            'icon' => 'swtorStory',
        ],
        [
            'name' => [
                'de' => 'Gewaltiger Monolith (Veteran)',
                'en' => 'Colossal Monolith (Veteran)',
            ],
            'icon' => 'swtorVeteran',
        ],
        [
            'name' => [
                'de' => 'Gewaltiger Monolith (Meister)',
                'en' => 'Colossal Monolith (Master)',
            ],
            'icon' => 'swtorMaster',
        ],
        [
            'name' => [
                'de' => 'Götter aus der Maschiene (Story)',
                'en' => 'Gods from the Machine Tyth (Story)',
            ],
            'icon' => 'swtorStory',
        ],
        [
            'name' => [
                'de' => 'Götter aus der Maschiene (Veteran)',
                'en' => 'Gods from the Machine Tyth (Veteran)',
            ],
            'icon' => 'swtorVeteran',
        ],
    ];
}

//Operation Swtor 5.6 Uprising
function getUprising()
{
    return [
        [
            'name' => [
                'de' => 'Mutierte Genosianische Königin (Story)',
                'en' => 'Mutated Geonosian Queen (Story)',
            ],
            'icon' => 'swtorStory',
        ],
        [
            'name' => [
                'de' => 'Mutierte Genosianische Königin (Veteran)',
                'en' => 'Mutated Geonosian Queen (Veteran)',
            ],
            'icon' => 'swtorVeteran',
        ],
    ];
}

//Operation Swtor 6.0 Onslaught
function getOnslaught()
{
    return [
        [
            'name' => [
                'de' => 'Dxun (Story)',
                'en' => 'Dxun (Story)',
            ],
            'icon' => 'swtorStory',
        ],
        [
            'name' => [
                'de' => 'Dxun (Veteran)',
                'en' => 'Dxun (Veteran)',
            ],
            'icon' => 'swtorVeteran',
        ],
    ];
}

//Operation Swtor 7.0 Legacy of the Sith
function getLotS()
{
    return [
        [
            'name' => [
                'de' => 'R-4 Anomalie (Story)',
                'en' => 'R-4 Anomaly (Story)',
            ],
            'icon' => 'swtorStory',
        ],
        [
            'name' => [
                'de' => 'R-4 Anomalie (Veteran)',
                'en' => 'R-4 Anomaly (Veteran)',
            ],
            'icon' => 'swtorVeteran',
        ],
        [
            'name' => [
                'de' => 'R-4 Anomalie (Meister)',
                'en' => 'R-4 Anomaly (Master)',
            ],
            'icon' => 'swtorMaster',
        ],
    ];
}
