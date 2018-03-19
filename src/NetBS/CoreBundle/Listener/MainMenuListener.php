<?php

namespace NetBS\CoreBundle\Listener;

use Doctrine\ORM\EntityManager;
use NetBS\CoreBundle\Event\ExtendMainMenuEvent;
use NetBS\SecureBundle\Mapping\BaseUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class MainMenuListener
{
    /**
     * @var TokenStorage
     */
    private $storage;

    /**
     * @var EntityManager
     */
    private $manager;

    public function __construct(TokenStorage $storage, EntityManager $manager)
    {
        $this->storage  = $storage;
        $this->manager  = $manager;
    }

    public function onMenuConfigure(ExtendMainMenuEvent $event)
    {
        $menu       = $event->getMenu();

        /** @var BaseUser $user */
        $user       = $this->storage->getToken()->getUser();

        $menu->registerCategory('app.home', 'Home')
            ->addLink('app.home.dashboard', 'Dashboard', 'fas fa-home', 'netbs.core.home.dashboard');

        $repo       = $this->manager->getRepository('NetBSCoreBundle:DynamicList');
        $lists      = $repo->findForUser($user);

        $categorie  = $menu->registerCategory('app', 'NetBS');
        $submenu    = $categorie->addSubMenu('app.lists', 'Listes', 'fas fa-list');
        $submenu
            ->addSubLink('Listes automatiques', 'netbs.core.automatic_list.view_lists')
            ->addSubLink('Gérer mes listes', 'netbs.core.dynamics_list.manage_lists');

        if(count($lists) > 0)
            foreach ($repo->findForUser($user) as $list)
                $submenu->addSubLink($list->getName() . " (" . count($list->_getItemIds()) . ")", 'netbs.core.dynamics_list.manage_list', array('id' => $list->getId()));

        $secureCat  = $menu->registerCategory('secure.admin', 'Administration');

        if($user->hasRole('ROLE_SG'))
            $secureCat->addLink('secure.admin.changelog', 'Modifications', 'fas fa-history', 'netbs.core.changelog.list');

        $secureCat->addLink('secure.admin.parameters', 'Paramètres', 'fas fa-cog', 'netbs.core.parameters.list');
    }
}