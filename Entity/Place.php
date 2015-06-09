<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 08.03.15
 * Time: 17:34
 */

namespace Htc\NowTaxiBundle\Entity;

/**
 * Class Place
 * @package Htc\NowTaxiBundle\Entity
 */
class Place
{
    /**
     * Полный адрес. Строка, разделенная запятыми в формате:
     * страна, область(не обязательно), район(не обязательно), город, улица, дом
     *
     * @var string
     */
    public $address;

    /**
     * Подъезд (не обязательно)
     *
     * @var integer
     */
    public $porch;

    /**
     * Долгота точки (от минус 180 градусов до 180 градусов) (не обязательно)
     *
     * @var float
     */
    public $lon;

    /**
     * Широта точки (от минус 90 градусов до 90 градусов) (не обязательно)
     * 
     * @var float
     */
    public $lat;

    /**
     * @var \DateTime
     */
    public $time;
}
