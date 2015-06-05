<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 08.03.15
 * Time: 17:56
 */

namespace Htc\NowTaxiBundle\Constant;

/**
 * Список категорий тарифов
 *
 * Class TariffCategory
 * @package Htc\NowTaxiBundle\Constants
 */
final class TariffCategory
{
    /** Стандартные тарифы по данному региону */
    const STANDARD = 'standard';
    /** Тарифы с повышенной комфортностью */
    const COMFORT = 'comfort';
    /** Тарифы с высокой комфортностью */
    const BUSINESS = 'business';
    /** Минивены */
    const MINIVAN = 'minivan';

}