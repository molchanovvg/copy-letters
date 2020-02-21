<?php


namespace app\helpers;


class MonthTitle
{
    public static function get($month): string
    {
        $monthList = [
            'январь',
            'февраль',
            'март',
            'апрель',
            'май',
            'июнь',
            'июль',
            'август',
            'сентябрь',
            'октябрь',
            'ноябрь',
            'декабрь'
        ];
        return $monthList[$month-1];
    }
}