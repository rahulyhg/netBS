<?php

namespace SauvabelinBundle\ListModel;

use NetBS\CoreBundle\Form\Type\SwitchType;
use NetBS\CoreBundle\ListModel\Action\LinkAction;
use NetBS\CoreBundle\ListModel\Action\ModalAction;
use NetBS\CoreBundle\ListModel\Action\RemoveAction;
use NetBS\CoreBundle\ListModel\Column\ActionColumn;
use NetBS\CoreBundle\ListModel\Column\HelperColumn;
use NetBS\CoreBundle\ListModel\Column\XEditableColumn;
use NetBS\CoreBundle\Utils\Traits\EntityManagerTrait;
use NetBS\CoreBundle\Utils\Traits\RouterTrait;
use NetBS\ListBundle\Column\ClosureColumn;
use NetBS\ListBundle\Column\DateTimeColumn;
use NetBS\ListBundle\Column\SimpleColumn;
use NetBS\ListBundle\Model\BaseListModel;
use NetBS\ListBundle\Model\ListColumnsConfiguration;
use SauvabelinBundle\Entity\News;
use SauvabelinBundle\Entity\NewsChannel;

class NewsList extends BaseListModel
{
    use EntityManagerTrait, RouterTrait;

    /**
     * Retrieves all elements managed by this list
     * @return array
     */
    protected function buildItemsList()
    {
        return $this->entityManager->getRepository('SauvabelinBundle:News')->createQueryBuilder('n')
            ->orderBy('n.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns the class of items managed by this list
     * @return string
     */
    public function getManagedItemsClass()
    {
        return News::class;
    }

    /**
     * Returns this list's alias
     * @return string
     */
    public function getAlias()
    {
        return "sauvabelin.news";
    }

    /**
     * Configures the list columns
     * @param ListColumnsConfiguration $configuration
     */
    public function configureColumns(ListColumnsConfiguration $configuration)
    {
        $configuration
            ->addColumn("Titre", "titre", SimpleColumn::class)
            ->addColumn("Channel", "channel", ClosureColumn::class, [
                ClosureColumn::CLOSURE  => function(NewsChannel $channel) {
                    return "<span class='badge' style='background:{$channel->getColor()};color:white'>{$channel->getNom()}</span>";
                }
            ])
            ->addColumn("Importante", null, XEditableColumn::class, [
                XEditableColumn::PROPERTY   => "importante",
                XEditableColumn::TYPE_CLASS => SwitchType::class
            ])
            ->addColumn("Date", "createdAt", DateTimeColumn::class)
            ->addColumn("Publiée par", "user", HelperColumn::class)
            ->addColumn("Options", null, ActionColumn::class, [
                ActionColumn::ACTIONS_KEY   => [
                    ModalAction::class  => [
                        LinkAction::ROUTE   => function(News $news) {
                            return $this->router->generate('sauvabelin.news.edit_news', ['id' => $news->getId()]);
                        }
                    ],

                    RemoveAction::class
                ]
            ]);
    }
}