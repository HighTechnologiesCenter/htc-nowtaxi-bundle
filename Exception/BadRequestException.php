<?php
/**
 * Created by PhpStorm.
 * User: mr
 * Date: 10.03.2015
 * Time: 10:55
 */

namespace Htc\NowTaxiBundle\Exception;

use Httpful\Response;

/**
 * Исключение неправильного запроса
 *
 * Class AuthenticationException
 * @package Htc\NowTaxiBundle\Exception
 */
class BadRequestException extends NowTaxiRuntimeException
{
    /**
     * @var \Httpful\Response
     */
    private $response;

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
        parent::__construct(sprintf('Bad api request. Code: "%s", body: %s', $response->code, $response->raw_body));
    }
}