<?php

namespace SauvabelinBundle\Entity;

use NetBS\FichierBundle\Mapping\BaseGroupe;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class BSGroupe
 * @package SauvabelinBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="sauvabelin_netbs_groupes")
 * @ORM\HasLifecycleCallbacks()
 */
class BSGroupe extends BaseGroupe
{
    /**
     * On crée une vue SQL pour les accès au groupe, groupName<->username
     * @ORM\Column(name="nc_group_name", type="string", length=255, nullable=true)
     */
    protected $ncGroupName = null;

    /**
     * @var boolean
     * @ORM\Column(name="nc_mapped", type="boolean", length=255)
     */
    protected $ncMapped = false;

    public function getSlug() {

    }

    /**
     * @return string
     */
    public function getNcGroupName()
    {
        return $this->ncGroupName;
    }

    public function updateNCGroupName()
    {
        $this->ncGroupName = "[" . $this->id . "] " . $this->nom;

        if($this->getGroupeType())
            $this->ncGroupName .= " (" . $this->getGroupeType()->getNom() . ")";
    }

    /**
     * @return bool
     */
    public function isNcMapped()
    {
        return $this->ncMapped;
    }

    /**
     * @param bool $ncMapped
     */
    public function setNcMapped($ncMapped)
    {
        if($ncMapped && $this->ncGroupName === null)
            $this->updateNCGroupName();

        $this->ncMapped = $ncMapped;
    }
}