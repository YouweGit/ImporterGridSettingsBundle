<?php

namespace ImporterObjectBundle\EventListener;

use ImporterObjectBundle\Event\DataObjectImportEvent;
use Pimcore\Model\DataObject\ClassDefinition;

/**
 * Class DataObjectImportListener
 * @package ImporterObjectBundle\EventListener
 */
class DataObjectImportListener
{
    /**
     * @param DataObjectImportEvent $event
     */
    public function posImportCsv(DataObjectImportEvent $event): void
    {
        $className = $event->getClassName();
        if (empty($className)) {
            return;
        }

        $classDefinition = ClassDefinition::getByName($className);
        if (empty($classDefinition)) {
            return;
        }

        $data = json_decode($event->getResponse()->getContent());
        if (empty($data) || empty($data->config) || empty($data->config->dataPreview)) {
            return;
        }

        $fields = [];

        // It's only needed the header
        foreach ($data->config->dataPreview[0] as $item => $value) {
            if ($value === 'id') {
                $fields[] = [
                    'isOperator' => true,
                    'attributes' => [
                        'type' => "operator",
                        'class' => "Ignore",
                        'isOperator' => true,
                        'childs' => []
                    ]
                ];
            } elseif ($item !== 'rowId') {
                $fields[] = $this->setGridColumns($classDefinition, $value);
            }
        }

        $data->config->importConfigId = "0"; // Necessary to the selected grid column works
        $data->config->selectedGridColumns = $fields;
        $event->getResponse()->setContent(json_encode($data));
    }

    /**
     * @param ClassDefinition $classDefinition
     * @param $value
     * @return array
     */
    private function setGridColumns(ClassDefinition $classDefinition, $value): array
    {
        $data = [];
        foreach ($classDefinition->getFieldDefinitions() as $fieldDefinition) {
            if ($fieldDefinition->getName() === $value) {
                $data = [
                    'isValue' => true,
                    'attributes' => [
                        'label' => $fieldDefinition->getTitle() . " ($value)",
                        'type' => "value",
                        'class' => "DefaultValue",
                        'attribute' => $value,
                        'dataType' => $fieldDefinition->getFieldtype(),
                        'mode' => 'default',
                        'doNotOverwrite' => false,
                        'skipEmptyValues' => false,
                        'childs' => []
                    ]
                ];

                break;
            }
        }

        return $data;
    }
}
