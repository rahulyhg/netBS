<?php

namespace Ovesco\FacturationBundle\ListModel;

use NetBS\CoreBundle\ListModel\Column\XEditableColumn;
use NetBS\CoreBundle\Utils\Traits\EntityManagerTrait;
use NetBS\ListBundle\Model\BaseListModel;
use NetBS\ListBundle\Model\ListColumnsConfiguration;
use Ovesco\FacturationBundle\Entity\Compte;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ComptesList extends BaseListModel
{
    use EntityManagerTrait;

    /**
     * Retrieves all elements managed by this list
     * @return array
     */
    protected function buildItemsList()
    {
        return $this->entityManager->getRepository('OvescoFacturationBundle:Compte')->findAll();
    }

    /**
     * Returns the class of items managed by this list
     * @return string
     */
    public function getManagedItemsClass()
    {
        return Compte::class;
    }

    /**
     * Returns this list's alias
     * @return string
     */
    public function getAlias()
    {
        return 'facturation.accounts';
    }

    /**
     * Configures the list columns
     * @param ListColumnsConfiguration $configuration
     */
    public function configureColumns(ListColumnsConfiguration $configuration)
    {
        $configuration
            ->addColumn('CCP', null, XEditableColumn::class, [
                XEditableColumn::PROPERTY   => 'ccp',
                XEditableColumn::TYPE_CLASS => TextType::class
            ])
            /*
            ->addColumn('IBAN', null, XEditableColumn::class, [
                XEditableColumn::PROPERTY   => 'iban',
                XEditableColumn::TYPE_CLASS => TextType::class
            ])
            */
            ->addColumn('Ligne 1', null, XEditableColumn::class, [
                XEditableColumn::PROPERTY   => 'line1',
                XEditableColumn::TYPE_CLASS => TextType::class
            ])
            ->addColumn('Ligne 2', null, XEditableColumn::class, [
                XEditableColumn::PROPERTY   => 'line2',
                XEditableColumn::TYPE_CLASS => TextType::class
            ])
            ->addColumn('Ligne 3', null, XEditableColumn::class, [
                XEditableColumn::PROPERTY   => 'line3',
                XEditableColumn::TYPE_CLASS => TextType::class
            ])
            ->addColumn('Ligne 4', null, XEditableColumn::class, [
                XEditableColumn::PROPERTY   => 'line4',
                XEditableColumn::TYPE_CLASS => TextType::class
            ])
            ->addColumn('Remarques', null, XEditableColumn::class, [
                XEditableColumn::PROPERTY   => 'remarques',
                XEditableColumn::TYPE_CLASS => TextType::class
            ])
        ;
    }
}