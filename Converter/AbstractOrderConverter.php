<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 08.03.15
 * Time: 18:31
 */

namespace Htc\NowTaxiBundle\Converter;

/**
 * Class AbstractOrderConverter
 * @package Htc\NowTaxiBundle\Converter
 */
abstract class AbstractOrderConverter implements OrderConverterInterface
{
    /** Формат передаваемой строки времени */
    const DATE_TIME_STRING_FORMAT = 'Y-m-d H:i:s O';

    /**
     * @param array $addressParts
     * @return string
     */
    public static function makeAddressStringFromParts(array $addressParts)
    {
        $addressParts = self::arrayRemoveEmptyValues($addressParts);
        return join(', ', $addressParts);
    }

    /**
     * @param $array
     * @return array
     */
    public static function arrayRemoveEmptyValues($array)
    {
        $result = array();
        foreach($array as $val){
            $val = trim($val);

            if (empty($val)) {
                continue;
            }
            $result[] = $val;
        }

        return $result;
    }

    /**
     * @param $string
     * @return \DateTime
     */
    public static function stringToDateTime($string)
    {
        return \DateTime::createFromFormat(self::DATE_TIME_STRING_FORMAT, $string);
    }

    /**
     * @param \DateTime $dateTime
     * @return string
     */
    public static function dateTimeToString(\DateTime $dateTime)
    {
        return $dateTime->format(self::DATE_TIME_STRING_FORMAT);
    }
}
