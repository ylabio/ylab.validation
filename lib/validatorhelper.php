<?php

namespace YLab\Validation;

use Bitrix\Main\IO\Path;


/**
 * Class ValidatorHelper
 * @package YLab\Validation
 */
class ValidatorHelper
{
    /**
     * @param array $arData
     * @param array $arRules
     * @param string $sFile
     * @param string $sLang
     * @return \Illuminate\Validation\Validator
     * @throws \Bitrix\Main\IO\InvalidPathException
     * @throws \Exception
     */
    public static function makeCustomValidator(array $arData, array $arRules, $sFile, $sLang = LANGUAGE_ID)
    {
        $arErrorDefault = ValidatorHelper::messages(__FILE__, $sLang);
        $arErrorMessages = ValidatorHelper::messages($sFile, $sLang);
        $arCustomMessages = ValidatorHelper::messages($sFile, $sLang, 'CUSTOM_');

        if (is_array($arErrorDefault) && is_array($arErrorMessages)) {
            $arErrorMessages = array_merge($arErrorDefault, $arErrorMessages);
        }

        return Validator::make($arData, $arRules, $sLang, $arErrorMessages, $arCustomMessages);
    }

    /**
     * @param \Illuminate\Validation\Validator $oValidator
     * @return array
     */
    public static function errorsToArray(\Illuminate\Validation\Validator $oValidator)
    {
        $arErrors = [];
        $arRules = $oValidator->getRules();
        if (count($arRules)) {
            foreach ($arRules as $sRule=>$arRule) {
                foreach ($oValidator->errors()->get($sRule) as $sMessage) {
                    $arErrors[] = $sMessage;
                }
            }
        }

        return $arErrors;
    }

    /**
     * @param string $sFile
     * @param string $sLang
     * @param string $sPrefix
     * @param bool $bReplace
     * @return array
     * @throws \Bitrix\Main\IO\InvalidPathException
     */
    public static function messages($sFile, $sLang = LANGUAGE_ID, $sPrefix = 'YV_', $bReplace = false)
    {
        $MESS = [];
        $arMessages = [];
        $sFile = Path::normalize($sFile);

        $sLangDir = '';
        $sFileName = '';
        $sFilePath = $sFile;

        while (($sSlashPos = strrpos($sFilePath, "/")) !== false) {
            $sFilePath = substr($sFilePath, 0, $sSlashPos);
            $sLangPath = $sFilePath . '/lang';
            if (is_dir($sLangPath)) {
                $sLangDir = $sLangPath;
                $sFileName = substr($sFile, $sSlashPos);
                break;
            }
        }

        if ($sLangDir <> '') {
            $sLangFile = $sLangDir . '/' . $sLang . $sFileName;
            if (file_exists($sLangFile)) {
                include $sLangFile;
            }
        }

        if (count($MESS) && is_array($MESS)) {
            foreach ($MESS as $sCode=>$sPhrase) {
                if (substr_count($sCode, $sPrefix)) {
                    $sCode = str_replace($sPrefix, '', $sCode);
                    if ($bReplace) {
                        $sCode = str_replace('_', '.', $sCode);
                    }

                    $arMessages[ToLower($sCode)] = $sPhrase;
                }
            }
        }

        return $arMessages;
    }
}
