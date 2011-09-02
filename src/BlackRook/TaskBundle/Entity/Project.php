<?php

namespace BlackRook\TaskBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * BlackRook\TaskBundle\Entity\Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="BlackRook\TaskBundle\Entity\ProjectRepository")
 */
class Project
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
     * @var text $name
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
     * One project to many tasks (INVERSE SIDE)
     * The inverse side of a bidirectional relationship must refer to its owning side by use of the mappedBy attribute of the OneToOne, OneToMany, or ManyToMany mapping declaration. The mappedBy attribute designates the field in the entity that is the owner of the relationship.
     * Orphan removal true removes the tasks that are not assigned to a project
     * @see: http://www.doctrine-project.org/docs/orm/2.0/en/reference/working-with-associations.html#orphan-removal
     * @ORM\OneToMany(targetEntity="Task", mappedBy="project", cascade={"persist"})
     * , orphanRemoval=true
     */
    protected $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

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
     * @param text $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return text 
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
     * Add tasks
     *
     * @param BlackRook\TaskBundle\Entity\Task $tasks
     */
    public function addTasks(\BlackRook\TaskBundle\Entity\Task $tasks)
    {
        $this->tasks[] = $tasks;
    }

    public function removeTasks(){
        $this->tasks = new ArrayCollection();
    }

    /**
     * Get tasks
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTasks()
    {
        return $this->tasks;
    }
}