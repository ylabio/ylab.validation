<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
?>

<form action="" method="post" class="form form-block">
    <?= bitrix_sessid_post() ?>
    <? if (count($arResult['ERRORS'])): ?>
        <p><?= implode('<br/>', $arResult['ERRORS']) ?></p>
    <?elseif ($arResult['SUCCESS']):?>
        <p>Успешная валидация</p>
    <? endif; ?>
    <div>
        <label>
            Пользователь<br>
            <select name="user">
                <option value="">Выбрать</option>
                <option value="1">Иван Иванов</option>
                <option value="2">Петр Петров</option>
                <option value="3">Мария Шарова</option>
            </select>
        </label>
    </div>
    <div>
        <label>
            Дата<br>
            <input type="text" name="date"/>
        </label>
    </div>
    <div>
        <label>
            Оценка<br>
            <input type="text" name="rating"/>
        </label>
    </div>
    <div class="btn green">
        <button type="submit" name="submit">Отправить</button>
    </div>
</form>