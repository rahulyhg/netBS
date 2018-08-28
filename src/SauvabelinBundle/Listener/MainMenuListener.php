<?php

namespace SauvabelinBundle\Listener;

use NetBS\CoreBundle\Event\ExtendMainMenuEvent;
use SauvabelinBundle\Entity\BSUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class MainMenuListener
{
    /**
     * @var TokenStorage
     */
    private $storage;

    public function __construct(TokenStorage $storage)
    {
        $this->storage  = $storage;
    }

    public function onMenuConfigure(ExtendMainMenuEvent $event)
    {
        /** @var BSUser $user */
        $menu   = $event->getMenu();
        $links  = $menu->getCategory('app')->getLinks();
        $user   = $this->storage->getToken()->getUser();

        foreach($links as $link)
            if($link->getKey() === 'fichier')
                $link->addSubLink('Ajouter un membre', 'sauvabelin.membre.add_membre');

        $adminCategory  = $menu->getCategory('secure.admin');

        if(!$user->hasRole("ROLE_SG"))
            return;

        $adminCategory->addLink("admin.news", "Gestion des news", "fas fa-newspaper", "sauvabelin.news.manage");
        $adminCategory->getLink('netbs.secure.admin.users')
            ->addSubLink('Derniers comptes', 'sauvabelin.user.latest_created');


        if(!$user->hasRole("ROLE_ADMIN"))
            return;

        $mailsMenu      = $adminCategory->addSubMenu('bs.mails', 'Mails BS', 'fas fa-envelope');
        $mailsMenu->addSubLink("Mailing listes", "sauvabelin.mailing_lists.lists_mailing_lists");
    }
}
