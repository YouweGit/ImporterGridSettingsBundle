<?php

namespace ImporterObjectBundle\Listener;

use ImporterObjectBundle\Event\DataObjectImportEvent;
use ImporterObjectBundle\EventListener\DataObjectImportListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Class RequestListener
 * @package ImporterObjectBundle\Listener
 */
class RequestListener
{
    const IMPORT_FILE_URL = '/object-helper/import-get-file-info';

    const IMPORT_CONFIG_PARAMETER = "importConfigId";

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (strpos($event->getRequest()->getPathInfo(), $this::IMPORT_FILE_URL) !== false
                && empty($event->getRequest()->get($this::IMPORT_CONFIG_PARAMETER))) {
            $importEvent = new DataObjectImportEvent(
                $event->getRequest()->get("className"),
                $event->getResponse()
            );

            $eventDispatcher = new EventDispatcher();
            $eventDispatcher->addListener(
                DataObjectImportEvent::EVENT_NAME,
                array(
                    new DataObjectImportListener(),
                    DataObjectImportEvent::METHOD_NAME
                )
            );

            $eventDispatcher->dispatch(DataObjectImportEvent::EVENT_NAME, $importEvent);
        }
    }
}
