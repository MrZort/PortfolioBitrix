<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Техподдержка");
$APPLICATION->AddChainItem(GetMessage("COMPANY_TITLE"));

$arrFilter = ["=PROPERTY_KATEGORIYA_N_VALUE" => "CRM"];

$APPLICATION->IncludeComponent("bitrix:news.list", "pavel", [
        'IBLOCK_TYPE' => 'bitrix_processes',
        'IBLOCK_ID' => 79,
        'NEWS_COUNT' => 10,
        'SORT_BY1' => 'ID',
        'SORT_ORDER1' => 'ASC',
        'SORT_BY2' => 'NAME',
        'SORT_ORDER2' => 'ASC',
        'USE_FILTER' => 'Y',
        'FILTER_NAME' => 'arrFilter',
        'FIELD_CODE' => ['NAME'],
        'PROPERTY_CODE' => [
            'KRITICHNOST',
            'OPISANIE_PROBLEMY',
            'KRAYNIY_SROK',
            'STATUS_ZAYAVKI',
            'ISPOLNITEL',
            'ZADACHA_SSYLKA'
        ],
        'CACHE_TYPE' => 'A'
    ]
);

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
