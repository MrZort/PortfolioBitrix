<?php

//Вывод списков с менеджерами и общим количеством их добавленных контактов (клиентов) за определённый период времени
//первый список реботает с датой создания сделки, второй список с датой модификации

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle(GetMessage("COMPANY_TITLE"));
$APPLICATION->AddChainItem(GetMessage("COMPANY_TITLE"));

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\Query\Join;
use Bitrix\Main\ORM\Fields\Relations\Reference;

global $DB;

$startDate = [
    'DD' => 1,
    'MM' => 6,
    'YYYY' => 2022,
];
$unixStartDate = \Bitrix\Main\Type\DateTime::createFromUserTime(
        date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT")),
            mktime(
                0, 0, 0,
               $startDate['MM'],
               $startDate['DD'],
               $startDate['YYYY']
            )
        )
);

$finishDate = [
    'DD' => 30,
    'MM' => 9,
    'YYYY' => 2022,
];
$unixFinishDate = \Bitrix\Main\Type\DateTime::createFromUserTime(
        date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT")),
            mktime(
                0, 0, 0,
                $finishDate['MM'],
                $finishDate['DD'],
                $finishDate['YYYY']
            )
        )
);

$dealsDateCreate = \Bitrix\Crm\DealTable::query()
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
    ->where('CONTACT_BY.LAST_NAME', 'Переключение 822')
    ->whereBetween('CONTACT_BY.DATE_CREATE', $unixStartDate, $unixFinishDate)
    ->fetchAll();

$dealsDateModify = \Bitrix\Crm\DealTable::query()
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
    ->where('CONTACT_BY.LAST_NAME', 'Переключение 822')
    ->whereBetween('CONTACT_BY.DATE_MODIFY', $unixStartDate, $unixFinishDate)
    ->fetchAll();

?>
    <table>
        <thead>
        <tr>
            <th>Менеджер</th>
            <th>Количество контактов</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($dealsDateCreate as $row): ?>
            <tr>
                <td>
                    <?=$row['CRM_DEAL_USER_LAST_NAME']; ?>
                    <?=$row['CRM_DEAL_USER_NAME']; ?>
                    <?=$row['CRM_DEAL_USER_SECOND_NAME']; ?>
                </td>
                <td><?=$row['COUNT']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<p></p>
    <table>
        <thead>
        <tr>
            <th>Менеджер</th>
            <th>Количество контактов</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($dealsDateModify as $row): ?>
            <tr>
                <td>
                    <?=$row['CRM_DEAL_USER_LAST_NAME']; ?>
                    <?=$row['CRM_DEAL_USER_NAME']; ?>
                    <?=$row['CRM_DEAL_USER_SECOND_NAME']; ?>
                </td>
                <td><?=$row['COUNT']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php

//Вывод календарей каждого менеджера, в которых указано сколько было добавлено контактов по дням

$unixFirstDate = \Bitrix\Main\Type\DateTime::createFromUserTime(
        date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT")),
            mktime(
                0, 0, 0,
                8,
                1,
                2022
            )
        )
);

$unixSecondDate = \Bitrix\Main\Type\DateTime::createFromUserTime(
        date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT")),
            mktime(
                0, 0, 0,
                8,
                31,
                2022
            )
        )
);

$days = \Bitrix\Crm\DealTable::query()
    ->registerRuntimeField(new Reference(
        'USER',
        \Bitrix\Main\UserTable::class,
        Join::on('this.UF_CRM_5BC969145F54A', 'ref.ID')))
    ->addSelect('ID')
    ->addSelect('UF_CRM_5BC969145F54A')
    ->addSelect('CONTACT_BY.DATE_MODIFY', 'DATE')
    ->addSelect('USER.NAME')
    ->addSelect('USER.SECOND_NAME')
    ->addSelect('USER.LAST_NAME')
    ->where('CONTACT_BY.LAST_NAME', 'Переключение 822')
    ->whereBetween('CONTACT_BY.DATE_MODIFY', $unixFirstDate, $unixSecondDate)
    ->fetchAll();

$nameById = [];
$countOfDate = [];
foreach ($days as $day)
{
    $day["DATE"] = $day["DATE"]->format("d.m.Y");
    $countOfDate[$day["UF_CRM_5BC969145F54A"]][$day["DATE"]] += 1;
    $nameById[$day["UF_CRM_5BC969145F54A"]] = $day;
}

$dateStart = new DateTime($unixFirstDate);
$i = 0;
while($dateStart->format("l") != "Monday") {
    $dateStart = $dateStart->modify('-1 day');
    $i++;
    if ($i > 7) {
        break;
    }
}
$date = $dateStart;

$i = 0;
$dateFinal = new DateTime($unixSecondDate);
$calendar[$date->format("d.m.Y")] = (int)$date->format("d");
while($date->format("l") != "Sunday" || $date < $dateFinal) {
    $date = $date->modify('1 day');
    $calendar[$date->format("d.m.Y")] = (int)$date->format("d");
    $i++;
    if ($i > 90) {
        break;
    }
}
$dateStart = new DateTime($unixFirstDate);

?>

<style>
    .calendar{
        display: flex;
        align-items: flex-start;
        flex-wrap: wrap;
        width: 700px;
    }
    .title-cell, .cell-empty, .cell-full, .opacity-cell{
        width: 90px;
        border: 1px solid black;
        padding: 3px;
    }
    .title-cell{
        text-align: center;
        background: #f5f5dc;
    }
    .opacity-cell{
        display: flex;
        justify-content: space-between;
        height: 100px;
    }
    .cell-empty, .cell-full{
        display: flex;
        justify-content: space-between;
        height: 100px;
    }
    .cell-empty{
        background: #fa8072;
    }
    .cell-full{
        background: #98fb98;
    }
    .day{
        margin: 0;
        align-items: flex-start;
    }
    .opacity-cell .day{
        opacity: 0.2;
    }
    .result{
        border: 2px solid black;
        border-radius: 100px;
        background: #f5f5dc;
        padding: 5px;
        margin: 0;
        align-self: flex-end;
        color: black;
    }
</style>

<?php foreach ($countOfDate as $key => $cOD):
    $daysInCalendar = 0;
?>
    <div>
        <br>
        <h1>
            <?=$nameById[$key]['CRM_DEAL_USER_LAST_NAME']; ?>
            <?=$nameById[$key]['CRM_DEAL_USER_NAME']; ?>
            <?=$nameById[$key]['CRM_DEAL_USER_SECOND_NAME']; ?>
        </h1>
        <div class="calendar">
            <div class="title-cell">Понедельник</div>
            <div class="title-cell">Вторник</div>
            <div class="title-cell">Среда</div>
            <div class="title-cell">Четверг</div>
            <div class="title-cell">Пятница</div>
            <div class="title-cell">Суббота</div>
            <div class="title-cell">Воскресенье</div>
            <?php foreach ($calendar as $keyDate => $val):
                $newDate = new DateTime($keyDate); ?>
                <?php if ($newDate >= $dateStart && $newDate <= $dateFinal): ?>
                <div class="<?= $cOD[$keyDate] ? "cell-full" : "cell-empty" ?>">
                    <h4 class="day"><?= $val ?></h4>
                    <h2 class="result"><?= $cOD[$keyDate] ?? 0 ?></h2>
                </div>
                <?php else: ?>
                <div class="opacity-cell">
                    <h4 class="day"><?= $val?></h4>
                </div>
                <?php endif ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>