<?php

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

/** @global \CMain $APPLICATION */
global $APPLICATION;

Loc::loadMessages(__FILE__);

defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'ylab.validation');

if (!$USER->isAdmin()) {
    $APPLICATION->authForm('Nope');
}

try {
    $oApp = Application::getInstance();
    $oContext = $oApp->getContext();
    $oRequest = $oContext->getRequest();

    Loc::loadMessages($oContext->getServer()->getDocumentRoot() . "/bitrix/modules/main/options.php");

    $arErrors = [];
    $arTabs = [
        [
            "DIV" => "edit1",
            "TAB" => Loc::getMessage("MAIN_TAB_SET"),
            "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_SET"),
            'OPTIONS' => [
                [
                    'ylab_validation_autoload',
                    Loc::getMessage('YLAB_VALIDATION_AUTOLOAD'),
                    '',
                    ['checkbox']
                ]
            ]
        ],
    ];

    $oTabControl = new CAdminTabControl("tabControl", $arTabs);

    if (!empty($save) && $oRequest->isPost() && check_bitrix_sessid()) {
        foreach ($arTabs as $arTab) {
            foreach ($arTab['OPTIONS'] as $arOption) {
                if ($arOption[0] === 'ylab_validation_autoload') {
                    $sVal = $oRequest->get('ylab_validation_autoload');
                    if ($sVal === 'Y') {
                        \Bitrix\Main\EventManager::getInstance()->registerEventHandler(
                            'main',
                            'OnPageStart',
                            ADMIN_MODULE_NAME,
                            '\YLab\Validation\Event',
                            'onPageStart'
                        );
                    } else {
                        \Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler(
                            'main',
                            'OnPageStart',
                            ADMIN_MODULE_NAME,
                            '\YLab\Validation\Event',
                            'onPageStart'
                        );
                    }
                }

                __AdmSettingsSaveOption(ADMIN_MODULE_NAME, $arOption);
            }
        }
    }

    if (count($arErrors) > 0) {
        $arMessage = [
            "MESSAGE" => implode("\n", $arErrors),
            "HTML" => true,
            "TYPE" => "ERROR",
        ];
        $oCAdminMessage = new \CAdminMessage($arMessage);
        $oCAdminMessage->ShowMessage($arMessage);
    }
} catch (\Exception $oError) {
    ShowError($oError->getMessage());
}

$oTabControl->begin();
?>

<form method="post"
      action="<?= sprintf('%s?mid=%s&lang=%s', $oRequest->getRequestedPage(), urlencode(ADMIN_MODULE_NAME),
          LANGUAGE_ID) ?>">
    <?php
    foreach ($arTabs as $arTab) {
        if ($arTab['OPTIONS']) {
            $oTabControl->BeginNextTab();
            __AdmSettingsDrawList(ADMIN_MODULE_NAME, $arTab['OPTIONS']);
        }
    }
    $oTabControl->beginNextTab();
    $oTabControl->buttons();
    ?>
    <input type="submit"
           name="save"
           value="<?= Loc::getMessage("MAIN_SAVE") ?>"
           title="<?= Loc::getMessage("MAIN_OPT_SAVE_TITLE") ?>"
           class="adm-btn-save"
    />
    <?php
    echo bitrix_sessid_post();
    $oTabControl->end();
    ?>
</form>