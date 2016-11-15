<?php

/**
 * Created by PhpStorm.
 * User: dkhodakovskiy
 * Date: 15.11.16
 * Time: 11:35
 */
class EveLogger
{

    const LEVEL_NOTICE  = 'NOTICE';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_FATAL   = 'FATAL';

    public static function log($category, $level, $message)
    {
        if (!file_exists(Yii::app()->params['logsPath'])) {
            if (!mkdir(Yii::app()->params['logsPath'], 0777, true)) exit;
        }

        $dateTime = (new DateTime())->format('d-m-Y H:i:s');
        $message = <<<MSG
$dateTime [$level] $message

MSG;

        file_put_contents(Yii::app()->params['logsPath'] . "/{$category}.log", $message, FILE_APPEND);
        self::rotate($category);
    }

    public static function rotate($category)
    {
        if (filesize(Yii::app()->params['logsPath'] . "/{$category}.log") > 100000) {
            $timeStamp = time();
            rename(
                Yii::app()->params['logsPath'] . "/{$category}.log",
                Yii::app()->params['logsPath'] . "/{$category}_{$timeStamp}.log"
            );
        }
    }

}
