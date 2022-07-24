<?php

namespace rp\system\event\listener;

use rp\acp\form\CharacterAddForm;
use rp\data\classification\ClassificationCache;
use rp\data\game\GameCache;
use rp\data\race\RaceCache;
use rp\data\role\RoleCache;
use rp\data\server\ServerCache;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\container\TabFormContainer;
use wcf\system\form\builder\container\TabTabMenuFormContainer;
use wcf\system\form\builder\data\processor\CustomFormDataProcessor;
use wcf\system\form\builder\data\processor\VoidFormDataProcessor;
use wcf\system\form\builder\field\CheckboxFormField;
use wcf\system\form\builder\field\dependency\NonEmptyFormFieldDependency;
use wcf\system\form\builder\field\IntegerFormField;
use wcf\system\form\builder\field\SingleSelectionFormField;
use wcf\system\form\builder\field\validation\FormFieldValidationError;
use wcf\system\form\builder\field\validation\FormFieldValidator;
use wcf\system\form\builder\IFormDocument;
use wcf\system\form\builder\IFormNode;
use wcf\system\form\builder\field\IFormField;

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
 * Creates the character equipment form.
 * 
 * @author      Marco Daries
 * @package     Daries\RP\System\Event\Listener
 */
class SWTORCharacterAddFormBuilderListener implements IParameterizedEventListener
{
    protected static $maxFightStyle = 2;

    protected function createForm(CharacterAddForm $eventObj): void
    {
        /** @var FormContainer $characterGeneral */
        $characterGeneral = $eventObj->form->getNodeById('characterGeneralSection');
        $characterGeneral->appendChildren([
            IntegerFormField::create('level')
            ->label('rp.character.swtor.level')
            ->required()
            ->minimum(1)
            ->maximum(90)
            ->value(0),
            SingleSelectionFormField::create('raceID')
            ->label('rp.race.title')
            ->required()
            ->options(['' => 'wcf.global.noSelection'] + RaceCache::getInstance()->getRaces())
            ->addValidator(new FormFieldValidator('uniqueness', function (SingleSelectionFormField $formField) {
            $value = $formField->getSaveValue();

            if (empty($value)) {
                $formField->addValidationError(new FormFieldValidationError('empty'));
            }
            else {
                $role = RaceCache::getInstance()->getRaceByID($value);
                if ($role === null) {
                    $formField->addValidationError(new FormFieldValidationError(
                        'invalid',
                        'rp.race.error.invalid'
                    ));
                }
            }
        })),
            SingleSelectionFormField::create('serverID')
            ->label('rp.server.title')
            ->required()
            ->options(['' => 'wcf.global.noSelection'] + ServerCache::getInstance()->getServers()),
        ]);

        /** @var TabTabMenuFormContainer $characterTab */
        $characterTab = $eventObj->form->getNodeById('characterTab');

        for ($i = 0; $i < self::$maxFightStyle; $i++) {
            $fightStyleEnable = CheckboxFormField::create('fightStyleEnable' . $i)
                ->label('rp.character.swtor.fightStyleEnable')
                ->value($i === 0)
                ->addValidator(new FormFieldValidator('checkFirstEnable', function (CheckboxFormField $formField) {
                $id = $formField->getId();
                if ($id === 'fightStyleEnable0') {
                    $value = $formField->getSaveValue();
                    if (!$value) {
                        $formField->addValidationError(new FormFieldValidationError('empty'));
                    }
                }
            }));

            $characterFightStyleTab = TabFormContainer::create('characterFightStyle' . $i)
                ->label('rp.character.swtor.fightStyle' . $i)
                ->appendChildren([
                FormContainer::create('characterFightStyleSection' . $i)
                ->appendChildren([
                    $fightStyleEnable,
                    SingleSelectionFormField::create('classificationID' . $i)
                    ->label('rp.classification.title')
                    ->required()
                    ->options(['' => 'wcf.global.noSelection'] + ClassificationCache::getInstance()->getClassifications())
                    ->addValidator(new FormFieldValidator('uniqueness', function (SingleSelectionFormField $formField) {
                $value = $formField->getSaveValue();

                if (empty($value)) {
                    $formField->addValidationError(new FormFieldValidationError('empty'));
                }
                else {
                    $role = ClassificationCache::getInstance()->getClassificationByID($value);
                    if ($role === null) {
                        $formField->addValidationError(new FormFieldValidationError(
                                'invalid',
                                'rp.classification.error.invalid'
                            ));
                    }
                }
            }))
                    ->addDependency(
                    NonEmptyFormFieldDependency::create('fightStyleEnable' . $i)
                    ->field($fightStyleEnable)
                ),
                    SingleSelectionFormField::create('roleID' . $i)
                    ->label('rp.role.title')
                    ->required()
                    ->options(['' => 'wcf.global.noSelection'] + RoleCache::getInstance()->getRoles())
                    ->addValidator(new FormFieldValidator('uniqueness', function (SingleSelectionFormField $formField) {
                $value = $formField->getSaveValue();

                if (empty($value)) {
                    $formField->addValidationError(new FormFieldValidationError('empty'));
                }
                else {
                    $role = RoleCache::getInstance()->getRoleByID($value);
                    if ($role === null) {
                        $formField->addValidationError(new FormFieldValidationError(
                                'invalid',
                                'rp.role.error.invalid'
                            ));
                    }
                }
            }))
                    ->addDependency(
                    NonEmptyFormFieldDependency::create('fightStyleEnable' . $i)
                    ->field($fightStyleEnable)
                ),
                ]),
                FormContainer::create('fightStyleEquipment' . $i)
                ->label('rp.character.category.swtor.equipment')
                ->appendChildren([
                    IntegerFormField::create('itemLevel' . $i)
                    ->label('rp.character.swtor.itemLevel')
                    ->required()
                    ->minimum(1)
                    ->maximum(330)
                    ->value(0)
                    ->addDependency(
                    NonEmptyFormFieldDependency::create('fightStyleEnable' . $i)
                    ->field($fightStyleEnable)
                ),
                    SingleSelectionFormField::create('implants' . $i)
                    ->label('rp.character.swtor.implants')
                    ->options(function () {
                return [
                '0' => 'rp.character.swtor.implants.0',
                '1' => 'rp.character.swtor.implants.1',
                '2' => 'rp.character.swtor.implants.2'
                ];
            })
                    ->addDependency(
                    NonEmptyFormFieldDependency::create('fightStyleEnable' . $i)
                    ->field($fightStyleEnable)
                ),
                    IntegerFormField::create('upgradeBlue' . $i)
                    ->label('rp.character.swtor.upgradeBlue')
                    ->minimum(0)
                    ->maximum(14)
                    ->value(0)
                    ->addDependency(
                    NonEmptyFormFieldDependency::create('fightStyleEnable' . $i)
                    ->field($fightStyleEnable)
                ),
                    IntegerFormField::create('upgradePurple' . $i)
                    ->label('rp.character.swtor.upgradePurple')
                    ->minimum(0)
                    ->maximum(14)
                    ->value(0)
                    ->addDependency(
                    NonEmptyFormFieldDependency::create('fightStyleEnable' . $i)
                    ->field($fightStyleEnable)
                ),
                    IntegerFormField::create('upgradeGold' . $i)
                    ->label('rp.character.swtor.upgradeGold')
                    ->minimum(0)
                    ->maximum(14)
                    ->value(0)
                    ->addDependency(
                    NonEmptyFormFieldDependency::create('fightStyleEnable' . $i)
                    ->field($fightStyleEnable)
                ),
                ]),
            ]);
            $characterTab->appendChild($characterFightStyleTab);

            $eventObj->form->getDataHandler()->addProcessor(new VoidFormDataProcessor('fightStyleEnable' . $i));
            $eventObj->form->getDataHandler()->addProcessor(new VoidFormDataProcessor('classificationID' . $i));
            $eventObj->form->getDataHandler()->addProcessor(new VoidFormDataProcessor('roleID' . $i));
            $eventObj->form->getDataHandler()->addProcessor(new VoidFormDataProcessor('itemLevel' . $i));
            $eventObj->form->getDataHandler()->addProcessor(new VoidFormDataProcessor('implants' . $i));
            $eventObj->form->getDataHandler()->addProcessor(new VoidFormDataProcessor('upgradeBlue' . $i));
            $eventObj->form->getDataHandler()->addProcessor(new VoidFormDataProcessor('upgradePurple' . $i));
            $eventObj->form->getDataHandler()->addProcessor(new VoidFormDataProcessor('upgradeGold' . $i));
        }

        $eventObj->form->getDataHandler()->addProcessor(
            new CustomFormDataProcessor(
            'fightStyles',
        static function (IFormDocument $document, array $parameters) {
            $fightStyles = [];

            for ($i = 0; $i < self::$maxFightStyle; $i++) {
                /** @var CheckboxFormField $fightStyleEnable */
                $fightStyleEnable = $document->getNodeById('fightStyleEnable' . $i);

                $newFightStyle = [
                    'fightStyleEnable' => $fightStyleEnable->getSaveValue(),
                ];

                if ($fightStyleEnable->getSaveValue()) {
                    /** @var SingleSelectionFormField $classificationID */
                    $classificationID = $document->getNodeById('classificationID' . $i);
                    $newFightStyle['classificationID'] = $classificationID->getSaveValue();

                    /** @var SingleSelectionFormField $roleID */
                    $roleID = $document->getNodeById('roleID' . $i);
                    $newFightStyle['roleID'] = $roleID->getSaveValue();

                    /** @var IntegerFormField $itemLevel */
                    $itemLevel = $document->getNodeById('itemLevel' . $i);
                    $newFightStyle['itemLevel'] = $itemLevel->getSaveValue();

                    /** @var SingleSelectionFormField $implants */
                    $implants = $document->getNodeById('implants' . $i);
                    $newFightStyle['implants'] = $implants->getSaveValue();

                    /** @var IntegerFormField $upgradeBlue */
                    $upgradeBlue = $document->getNodeById('upgradeBlue' . $i);
                    $newFightStyle['upgradeBlue'] = $upgradeBlue->getSaveValue();

                    /** @var IntegerFormField $upgradePurple */
                    $upgradePurple = $document->getNodeById('upgradePurple' . $i);
                    $newFightStyle['upgradePurple'] = $upgradePurple->getSaveValue();

                    /** @var IntegerFormField $upgradeGold */
                    $upgradeGold = $document->getNodeById('upgradeGold' . $i);
                    $newFightStyle['upgradeGold'] = $upgradeGold->getSaveValue();
                }

                $fightStyles[$i] = $newFightStyle;
            }

            $parameters['data']['fightStyles'] = $fightStyles;

            return $parameters;
        }
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters): void
    {
        if (GameCache::getInstance()->getCurrentGame()->identifier !== 'swtor')
            return;

        switch ($eventName) {
            case 'createForm':
                $this->createForm($eventObj);
                break;
            case 'readData':
                $this->readData($eventObj);
                break;
        }
    }

    protected function readData(CharacterAddForm $eventObj): void
    {
        if (empty($_POST) && $eventObj->formObject !== null) {
            $fightStyles = $eventObj->formObject->fightStyles;

            foreach ($fightStyles as $key => $fightStyle) {
                foreach ($fightStyle as $fightStyleKey => $fightStyleValue) {
                    /** @var IFormField $field */
                    $field = $eventObj->form->getNodeById($fightStyleKey . $key);
                    if ($field !== null) {
                        $field->value($fightStyleValue);
                    }
                }
            }
        }
    }
}
