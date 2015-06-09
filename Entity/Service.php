<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 08.03.15
 * Time: 19:41
 */

namespace Htc\NowTaxiBundle\Entity;

/**
 * Параметры службы, выполняющей заказ
 *
 * Class Service
 * @package Htc\NowTaxiBundle\Entity
 */
class Service
{
    /**
     * Название службы
     *
     * @var string
     */
    public $title;

    /**
     * Телефоны службы (если несколько, то через запятую)
     *
     * @var string
     */
    public $phone;
}
