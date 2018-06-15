<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

/**
 * Class ylab_validation
 */
class ylab_validation extends CModule
{
    /**
     * @var string Код модуля
     */
    var $MODULE_ID = 'ylab.validation';

    /**
     * ylab_validation constructor.
     */
    public function __construct()
    {
        $arModuleVersion = array();

        include __DIR__ . '/version.php';
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('YLAB_VALIDATION_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('YLAB_VALIDATION_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
    }

    /**
     * @return bool
     */
    public function DoInstall()
    {
        /** \CMain $APPLICATION */
        global $APPLICATION;

        if (!class_exists('\Illuminate\Validation\Validator')) {
            $APPLICATION->ThrowException(Loc::getMessage('YLAB_VALIDATION_INSTALL_ERROR'));

            return false;
        }

        $this->InstallDB();
        ModuleManager::registerModule($this->MODULE_ID);

        return true;
    }

    /**
     * Удаление модуля
     */
    public function DoUninstall()
    {
        $this->UnInstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}