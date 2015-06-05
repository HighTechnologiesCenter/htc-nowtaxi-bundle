<?php
/**
 * Created by PhpStorm.
 * User: aztec
 * Date: 09.03.15
 * Time: 20:36
 */

namespace Htc\NowTaxiBundle\Event;


use Symfony\Component\EventDispatcher\Event;

class DriversPositionEvent extends Event
{
    /**
     * @var array
     */
    private $positions;

    /**
     * @param array $positions
     */
    public function __construct(array $positions)
    {
        $this->positions = $positions;
    }

    /**
     * @return array
     */
    public function getPositions()
    {
        return $this->positions;
    }

}