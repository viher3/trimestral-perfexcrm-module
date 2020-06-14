<?php

class DateLanguage
{
    /**
     * @return array
     */
    public static function monthsArray()
    {
        $months = [];

        for($i=1;$i<=12;$i++){
            $months[$i] = _l('month_' . $i);
        }

        return $months;
    }
}
