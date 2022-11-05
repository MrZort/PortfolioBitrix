<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle(GetMessage("COMPANY_TITLE"));
$APPLICATION->AddChainItem(GetMessage("COMPANY_TITLE"));

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\Query\Join;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\ORM\Fields\Relations\Reference;

global $DB;

$startDate = \Bitrix\Main\Type\DateTime::createFromUserTime("01.01.2022");
$finishDate = \Bitrix\Main\Type\DateTime::createFromUserTime("18.10.2022");

//вывести таблицу сделок с фильтром за 2022 год сгруппировать их по типам и по ответственным, по сумме
//Вывести имя ответственного, кол-во сделок, общая сумма

$query = \Bitrix\Crm\DealTable::query()
    ->registerRuntimeField(
        new Entity\ExpressionField('COUNT', 'COUNT(*)'))
    ->registerRuntimeField(
        new Entity\ExpressionField('TOTAL_SUM', 'SUM(OPPORTUNITY)'))
    ->addSelect('ASSIGNED_BY_ID')
    ->addSelect('TYPE_BY.NAME')
    ->addSelect('COUNT')
    ->addSelect('TOTAL_SUM')
    ->addSelect('ASSIGNED_BY.SHORT_NAME')
    ->whereBetween('DATE_CREATE', $startDate, $finishDate)
    ->addGroup('ASSIGNED_BY_ID')
    ->addGroup('TYPE_ID')
    ->fetchAll();

?>

<table>
    <thead>
        <tr>
            <th>Менеджер</th>
            <th>Тип сделки</th>
            <th>Количество сделок</th>
            <th>Общая сумма</th>
        </tr>
    </thead>
    <tbody>
    <? foreach ($query as $row): ?>
        <tr>
            <td><?=$row['CRM_DEAL_ASSIGNED_BY_SHORT_NAME']; ?></td>
            <td><?=$row['CRM_DEAL_TYPE_BY_NAME']; ?></td>
            <td><?=$row['COUNT']; ?></td>
            <td><?=$row['TOTAL_SUM']; ?> Руб.</td>
        </tr>
    <? endforeach; ?>
    </tbody>
</table>

<?php

//сделки за последний месяц:
//где первый столбец Менеджер сделки (UF_CRM_5BC969145F54A)
//Второй кол-во уникальных ID сделок
//третий сумма Счетчик конс (UF_CRM_1494155374)

$startDate = \Bitrix\Main\Type\DateTime::createFromUserTime("01.09.2022");
$finishDate = \Bitrix\Main\Type\DateTime::createFromUserTime("31.09.2022");

$query = \Bitrix\Crm\DealTable::query()
    ->registerRuntimeField(
        new Entity\ExpressionField('COUNT', 'COUNT(*)'))
    ->registerRuntimeField(
        new Entity\ExpressionField('TOTAL', 'SUM(UF_CRM_1494155374)'))
    ->registerRuntimeField(new Reference(
            'USER',
            \Bitrix\Main\UserTable::class,
            Join::on('this.UF_CRM_5BC969145F54A', 'ref.ID')))
    ->addSelect('USER.NAME')
    ->addSelect('USER.SECOND_NAME')
    ->addSelect('USER.LAST_NAME')
    ->addSelect('UF_CRM_5BC969145F54A')
    ->addSelect('COUNT')
    ->addSelect('TOTAL')
    ->whereBetween('DATE_CREATE', $startDate, $finishDate)
    ->addGroup('UF_CRM_5BC969145F54A')
    ->fetchAll();

?>
<br>
<table>
    <thead>
        <tr>
            <th>Менеджер</th>
            <th>Количество сделок</th>
            <th>Счётчик</th>
        </tr>
    </thead>
    <tbody>
    <? foreach ($query as $row): ?>
        <tr>
            <td>
                <?=$row['CRM_DEAL_USER_LAST_NAME']; ?>
                <?=$row['CRM_DEAL_USER_NAME']; ?>
                <?=$row['CRM_DEAL_USER_SECOND_NAME']; ?>
            </td>
            <td><?=$row['COUNT']; ?></td>
            <td><?=$row['TOTAL']; ?></td>
        </tr>
    <? endforeach; ?>
    </tbody>
</table>

<?php

// sumOfDealsTable.php

$query = \Bitrix\Crm\DealTable::query()
    ->registerRuntimeField(
        new Entity\ExpressionField('COUNT', 'COUNT(*)'))
    ->registerRuntimeField(new Reference(
        'USER',
        \Bitrix\Main\UserTable::class,
        Join::on('this.UF_CRM_5BC969145F54A', 'ref.ID')))
    ->addSelect('UF_CRM_5BC969145F54A')
    ->addSelect('COUNT')
    ->addSelect('USER.NAME')
    ->addSelect('USER.SECOND_NAME')
    ->addSelect('USER.LAST_NAME')
    ->addGroup('UF_CRM_5BC969145F54A')
    ->addOrder('COUNT', 'DESC')
    ->fetchAll();

?>

<br>
<table>
    <thead>
        <tr>
            <th>Менеджер</th>
            <th>Количество сделок</th>
        </tr>
    </thead>
    <tbody>
    <? foreach ($query as $row): ?>
        <tr>
            <td>
                <?=$row['CRM_DEAL_USER_LAST_NAME']; ?>
                <?=$row['CRM_DEAL_USER_NAME']; ?>
                <?=$row['CRM_DEAL_USER_SECOND_NAME']; ?>
            </td>
            <td><?=$row['COUNT']; ?></td>
        </tr>
    <? endforeach; ?>
    </tbody>
</table>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>