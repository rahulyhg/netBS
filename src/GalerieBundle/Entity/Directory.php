<?php

namespace GalerieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use GalerieBundle\Util\WebdavTrait;

/**
 * Directory
 *
 * @ORM\Table(name="netbs_galerie_directory")
 * @ORM\Entity(repositoryClass="GalerieBundle\Repository\DirectoryRepository")
 */
class Directory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="webdavUrl", type="string", length=255)
     */
    private $webdavUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="remote_id", type="string", length=255)
     */
    private $remoteId;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set webdavUrl.
     *
     * @param string $webdavUrl
     *
     * @return Directory
     */
    public function setSearchPath($webdavUrl)
    {
        $this->webdavUrl = $webdavUrl;

        return $this;
    }

    /**
     * Get webdavUrl.
     *
     * @return string
     */
    public function getSearchPath()
    {
        return $this->webdavUrl;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Directory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Directory
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getRemoteId()
    {
        return $this->remoteId;
    }

    /**
     * @param string $remoteId
     */
    public function setRemoteId($remoteId)
    {
        $this->remoteId = $remoteId;
    }
}
