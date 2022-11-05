<?php

use Bitrix\Main;
use Bitrix\Main\ORM\Fields\EnumField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Query\Join;

public function getMap()
{
    return [
        new Reference(
            'PROJECT_PARTICIPANTS',
            Main\UserTable::class,
            Join::on('this.PROJECT_PARTICIPANTS_ID', 'ref.ID'),
        ),
        new Reference(
            'RESPONSIBLE_FOR_TESTING',
            Main\UserTable::class,
            Join::on('this.RESPONSIBLE_FOR_TESTING_ID', 'ref.ID'),
        ),
        new Reference(
            'RESPONSIBLE_FOR_IMPLEMENTATION',
            Main\UserTable::class,
            Join::on('this.RESPONSIBLE_FOR_IMPLEMENTATION_ID', 'ref.ID'),
        ),
        new Reference(
            'PROJECT_MANAGER',
            Main\UserTable::class,
            Join::on('this.PROJECT_MANAGER_ID', 'ref.ID'),
        ),
        new IntegerField('ID', [
            'primary' => true,
            'autocomplete' => true
        ]),
        new IntegerField('EMPLOYEE_ID'),
        new TextField('TITLE_OF_NEW_PROJECT', [
            'required' => true
        ]),
        new EnumField('MODULE_WHICH_BE_FINALIZE', [
            'required' => true,
            'values' => [
                'Битрикс 24',
                'Клиентская база',
                'Личный кабинет',
                'Сайт СтопДолг'
            ]
        ]),
        new TextField('CRITICAL_RELATED_SYSTEMS'),
        new TextField('DETAILING_DEVELOPMENT_ENVIRONMENT', [
            'required' => true
        ]),
        new IntegerField('PROJECT_PARTICIPANTS_ID', [
            'required' => true,
        ]),
        new IntegerField('RESPONSIBLE_FOR_TESTING_ID'),
        new TextField('ACCEPTANCE_CRITERIA', [
            'required' => true
        ]),
        new IntegerField('RESPONSIBLE_FOR_IMPLEMENTATION_ID'),
        new IntegerField('PROJECT_MANAGER_ID'),
        new TextField('COMMENT'),

    ];
}