<?php

namespace NetBS\CoreBundle\ListModel;

use NetBS\ListBundle\Model\RendererInterface;
use NetBS\ListBundle\Model\SnapshotTable;

class HugeListRenderer implements RendererInterface
{
    protected $engine;

    public function __construct(\Twig_Environment $engine)
    {
        $this->engine           = $engine;
    }

    /**
     * Returns this renderer's name
     * @return string
     */
    public function getName()
    {
        return 'huge';
    }

    /**
     * Renders the given prototype table
     * @param SnapshotTable $table
     * @return string
     */
    public function render(SnapshotTable $table)
    {
        return $this->engine->render('@NetBSCore/renderer/huge_list.renderer.twig', array(
            'table'     => $table
        ));
    }
}