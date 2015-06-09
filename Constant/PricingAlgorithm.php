<?php
/**
 * Created by PhpStorm.
 * User: mr
 * Date: 11.03.2015
 * Time: 12:48
 */

namespace Htc\NowTaxiBundle\Constant;

/**
 * Алгоритмы соединения цены по расстоянию и цены по времени.
 * max - берется максимум из цен. sum или and - берется сумма.
 *
 * Class PricingAlgorithm
 * @package Htc\NowTaxiBundle\Constants
 */
final class PricingAlgorithm
{
    /** max - берется максимум из цен */
    const ALGORITHM_MAX = 'max';
    /** sum или and - берется сумма. */
    const ALGORITHM_SUM = 'sum';
    /** sum или and - берется сумма. */
    const ALGORITHM_AND = 'and';
}
