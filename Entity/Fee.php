<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 08.03.15
 * Time: 17:37
 */

namespace Htc\NowTaxiBundle\Entity;

/**
 * Параметры вознаграждений
 *
 * Class Fee
 * @package Htc\NowTaxiBundle\Entity
 */
class Fee
{
    /**
     * Фиксированная наценка на заказ
     *
     * @var integer
     */
    public $fix;

    /**
     * Наценка на заказ (процент)
     *
     * @var integer
     */
    public $relative;
}