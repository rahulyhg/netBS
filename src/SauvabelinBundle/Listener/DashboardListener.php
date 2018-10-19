<?php

namespace SauvabelinBundle\Listener;

use Doctrine\ORM\EntityManager;
use NetBS\CoreBundle\Block\CardBlock;
use NetBS\CoreBundle\Block\ListBlock;
use NetBS\CoreBundle\Event\PreRenderLayoutEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class DashboardListener
{
    protected $stack;

    protected $manager;

    public function __construct(RequestStack $stack, EntityManager $manager)
    {
        $this->stack    = $stack;
        $this->manager  = $manager;
    }

    /**
     * @param PreRenderLayoutEvent $event
     * @throws \Exception
     */
    public function extendsDashboard(PreRenderLayoutEvent $event) {

        $route  = $this->stack->getCurrentRequest()->get('_route');

        if($route !== "netbs.core.home.dashboard")
            return;

        $config = $event->getConfigurator();

        // Add other services
        $row    = $config->getRow(0);
        $firstCol = $row->getColumns()[0];

        $firstCol->addRow()->addColumn(0, 12)->setBlock(CardBlock::class, [
            'title'     => 'Services liés',
            'subtitle'  => "Liste des sites accessibles avec les même identifiants",
            'template'  => '@Sauvabelin/block/services.block.twig'
        ]);

        /*
        $firstCol->addRow()->addColumn(0, 12)->setBlock(ListBlock::class, [
            'alias'     => 'bs.displayable_mailing_lists',
            'title'     => 'Mailing listes',
            'subtitle'  => "Toutes les mailing listes BS"
        ]);
        */

        $row->addColumn(1, 8, 7, 12)->setBlock(CardBlock::class, array(
            'title'     => 'Calendrier BS',
            'subtitle'  => 'Calendriers internes et publiques',
            'template'  => '@Sauvabelin/block/calendrier.block.twig'
        ));
    }
}