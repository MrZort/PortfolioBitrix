<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);
?>

<div class="news-list">
    <?php foreach ($arResult["ITEMS"] as $arItem): ?>
        <p class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <?php foreach ($arItem["FIELDS"] as $code=>$value): ?>
            <small>
                <?= GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
            </small>
            <br />
        <?php endforeach; ?>
        <?php foreach ($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty): ?>
            <small>
                <?= $arProperty["NAME"] ?>:&nbsp;
                <?php if (is_array($arProperty["DISPLAY_VALUE"])): ?>
                    <?= implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]); ?>
                <?php else: ?>
                    <?= $arProperty["DISPLAY_VALUE"]; ?>
                <?php endif ?>
            </small>
                <?php if ($arProperty["NAME"] == "Исполнитель"): ?>
                    <?php foreach ($arItem["PHOTO_URL"] as $photoId => $photo): ?>
                        <img src="<?= $photo["src"]; ?>">
                    <?php endforeach; ?>
                <?php endif ?>
            <br />
        <?php endforeach; ?>
        </p>
    <?php endforeach; ?>
    <?php if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
        <br />
        <?= $arResult["NAV_STRING"] ?>
    <?php endif; ?>
</div>
