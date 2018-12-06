<?php

namespace ImporterObjectBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DataObjectImportEvent
 * @package ImporterObjectBundle\Event
 */
class DataObjectImportEvent extends Event
{
    const EVENT_NAME = 'dataobject.csv.posImportCsv';

    const METHOD_NAME = 'posImportCsv';

    /** @var $className */
    private $className;

    /** @var $response */
    private $response;

    /**
     * DataObjectImportEvent constructor.
     * @param $className
     * @param $response
     */
    public function __construct($className, Response $response)
    {
        $this->className = $className;
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}
