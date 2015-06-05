<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 08.03.15
 * Time: 19:44
 */

namespace Htc\NowTaxiBundle\Entity;

/**
 * Class Driver
 * @package Htc\NowTaxiBundle\Entity
 */
class Driver
{
    /**
     * Имя водителя (обязательное поле)
     *
     * @var string
     */
    public $name;

    /**
     * Строка описывающая марку и цвет машины (обязательное поле)
     *
     * @var string
     */
    public $car;

    /**
     * Номер машины (может отсутствовать)
     *
     * @var string
     */
    public $number;

    /**
     * Телефон водителя (или его диспетчера)
     *
     * @var string
     */
    public $phone;

    /**
     * Информация о службе, исполняющей заказ
     *
     * @var Service
     */
    public $service;

    /**
     * Точка в которой находился назначенный на заказ водитель.
     * Передается только в “активных” статусах заказа (confirm, driving, waiting, transporting).
     *
     * @var Place
     */
    public $point;
}