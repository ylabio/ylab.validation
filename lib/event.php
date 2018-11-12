<?php

namespace YLab\Validation;

use Bitrix\Main\Loader;

/**
 * Class Event
 * @package YLab\Validation
 */
class Event
{
    /**
     * @return bool
     * @throws \Bitrix\Main\LoaderException
     */
    public static function onPageStart()
    {
        if (!Loader::includeModule('ylab.validation')) {
            return false;
        }

        return true;
    }
}
