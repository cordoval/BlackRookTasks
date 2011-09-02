<?php

namespace BlackRook\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BlackRook\TaskBundle\Entity\Report
 *
 * @ORM\Table(name="report")
 * @ORM\Entity(repositoryClass="BlackRook\TaskBundle\Entity\ReportRepository")
 */
class Report
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var array $facts
     *
     * @ORM\Column(name="facts", type="array")
     */
    private $facts;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set facts
     *
     * @param array $facts
     */
    public function setFacts($facts)
    {
        $this->facts = $facts;
    }

    /**
     * Get facts
     *
     * @return array 
     */
    public function getFacts()
    {
        return $this->facts;
    }
}