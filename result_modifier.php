<?php

$ispolnitelId = [];
foreach ($arResult["ITEMS"] as $item){
    for ($i = 0; $i <= 1; $i++){
        $ispolnitelId[] = $item["PROPERTIES"]["ISPOLNITEL"]["VALUE"][$i];
    }
}

$uniqueId = array_unique($ispolnitelId);

$photo = \Bitrix\Main\UserTable::query()
    ->addSelect('ID')
    ->addSelect('SHORT_NAME')
    ->addSelect('PERSONAL_PHOTO')
    ->whereIn('ID', $uniqueId)
    ->fetchAll();

$photoId = array_column($photo, 'PERSONAL_PHOTO', 'ID');

$toResizeImg = $_SERVER['DOCUMENT_ROOT'].'/local/Pavel-Img/photo_2022-10-20_16-17-25.jpg';
$resultImg = $_SERVER['DOCUMENT_ROOT']."/local/Pavel-Img/photo_small.jpg";

$boolean = "";
foreach ($photoId as $key => $id){
    if ($id){
        $file[$key] = CFile::ResizeImageGet(
            $id,
            ['width' => 50, 'height' => 50],
            BX_RESIZE_IMAGE_PROPORTIONAL,
            true
        );
    }else{
        $boolean = CFile::ResizeImageFile(
            $toResizeImg,
            $resultImg,
            ['width' => 50, 'height' => 50],
            BX_RESIZE_IMAGE_PROPORTIONAL,
        );
        if ($boolean){
            $file[$key]['src'] = str_replace($_SERVER['DOCUMENT_ROOT'], "", $resultImg);
        }
    }
}

foreach ($arResult["ITEMS"] as &$item){
    for ($i = 0; $i < count($item["PROPERTIES"]["ISPOLNITEL"]["VALUE"]); $i++){
        $valueId = $item["PROPERTIES"]["ISPOLNITEL"]["VALUE"][$i];
        $item["PHOTO_URL"][$valueId] = $file[$valueId];
    }
}