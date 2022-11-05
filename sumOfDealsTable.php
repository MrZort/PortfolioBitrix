<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle(GetMessage("COMPANY_TITLE"));
$APPLICATION->AddChainItem(GetMessage("COMPANY_TITLE"));

use Bitrix\Main\Entity;

global $DB;

$users = \Bitrix\Main\UserTable::getList(
    [
        'select' => ['NAME', 'SECOND_NAME', 'LAST_NAME', 'ID'],
    ]
)->fetchAll();

$deals = \Bitrix\Crm\DealTable::getList(
    [
        'select' => ['UF_CRM_5BC969145F54A', 'COUNT'],
        'order' => ['COUNT' => 'DESC'],
        'group' => ['UF_CRM_5BC969145F54A'],
        'runtime' => [
            new Entity\ExpressionField('COUNT', 'COUNT(*)'),
        ]
    ]
)->fetchAll();

$qwe = [];
foreach ($users as $name) {
    $qwe[$name['ID']] = $name;
}

?>


<table>
    <thead>
    <tr>
        <th>Фамилия</th>
        <th>Имя</th>
        <th>Отчество</th>
        <th>Количество сделок</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($deals as $row): ?>
        <tr>
            <td><?= $qwe[$row['UF_CRM_5BC969145F54A']]['LAST_NAME']; ?></td>
            <td><?= $qwe[$row['UF_CRM_5BC969145F54A']]['NAME']; ?></td>
            <td><?= $qwe[$row['UF_CRM_5BC969145F54A']]['SECOND_NAME']; ?></td>
            <td><?= $row['COUNT']; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>