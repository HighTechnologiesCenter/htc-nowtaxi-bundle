<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 08.03.15
 * Time: 17:36
 */

namespace Htc\NowTaxiBundle\Entity;

/**
 * Требования к заказу (наличие не обязательно)
 *
 * Class Requirements
 * @package Htc\NowTaxiBundle\Entity
 */
class Requirements
{
    /**
     * Наличие кондиционера
     *
     * @var boolean
     */
    public $hasConditioner;

    /**
     * Не курить (1), будет курить (0), не важно (опция отсутствует)
     *
     * @var boolean
     */
    public $noSmoking;

    /**
     * Требуется детское кресло
     *
     * @var boolean
     */
    public $childChair;

    /**
     * Будет перевозиться животное
     *
     * @var boolean
     */
    public $animalTransport;

    /**
     * Кузов Универсал
     *
     * @var boolean
     */
    public $universal;

    /**
     * WiFi в машине
     *
     * @var boolean
     */
    public $wifi;

    /**
     * Выписка чека
     *
     * @var boolean
     */
    public $check;

    /**
     * Оплата карточкой
     *
     * @var boolean
     */
    public $card;

    /**
     * Безнальный заказ
     *
     * @var boolean
     */
    public $noncash;

    /**
     * Желтый номер (может ехать по выделенным полосам)
     *
     * @var boolean
     */
    public $specialNumber;

    /**
     * Не раскрашена под такси (не брендирована)
     *
     * @var boolean
     */
    public $noBrand;
}