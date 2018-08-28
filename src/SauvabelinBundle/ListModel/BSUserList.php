<?php

namespace SauvabelinBundle\ListModel;

use NetBS\CoreBundle\Form\Type\SwitchType;
use NetBS\CoreBundle\ListModel\Action\ModalAction;
use NetBS\CoreBundle\ListModel\Column\ActionColumn;
use NetBS\CoreBundle\ListModel\Column\XEditableColumn;
use NetBS\ListBundle\Model\ListColumnsConfiguration;
use NetBS\SecureBundle\ListModel\UsersList;
use SauvabelinBundle\Entity\BSUser;

class BSUserList extends UsersList
{
    public function configureColumns(ListColumnsConfiguration $configuration)
    {
        parent::configureColumns($configuration);

        $actions    = $configuration->removeColumn(count($configuration->getColumns()) - 1);
        $params     = $actions->getParams();
        $params[ActionColumn::ACTIONS_KEY][ModalAction::class] = [
            ModalAction::ICON   => 'fas fa-key',
            ModalAction::ROUTE  => function(BSUser $user) {
                return $this->router->generate('sauvabelin.user.admin_change_password_modal', ['id' => $user->getId()]);
            }
        ];

        $configuration
            ->addColumn('Accès nextcloud', null, XEditableColumn::class, [
            XEditableColumn::PROPERTY       => 'nextcloudAccount',
            XEditableColumn::TYPE_CLASS     => SwitchType::class
            ])
            ->addColumn('Admin nextcloud', null, XEditableColumn::class, [
                XEditableColumn::PROPERTY       => 'nextcloudAdmin',
                XEditableColumn::TYPE_CLASS     => SwitchType::class
            ])
            ->addColumn('Accès au wiki', null, XEditableColumn::class, [
                XEditableColumn::PROPERTY   => 'wikiAccount',
                XEditableColumn::TYPE_CLASS => SwitchType::class
            ])
            ->addColumn('Admin wiki', null, XEditableColumn::class, [
                XEditableColumn::PROPERTY   => 'wikiAdmin',
                XEditableColumn::TYPE_CLASS => SwitchType::class
            ])
            ->addColumn($actions->getHeader(), $actions->getAccessor(), $actions->getClass(), $params)
        ;
    }
}
