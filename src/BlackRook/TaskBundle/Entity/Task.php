<?php

namespace BlackRook\TaskBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BlackRook\TaskBundle\Entity\Task
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="BlackRook\TaskBundle\Entity\TaskRepository")
 */
class Task
{
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var text $estimatedDuration
     *
     * @ORM\Column(name="estimated_duration", type="integer", nullable=true)
     */
    private $estimatedDuration;

    /**
     * @var datetime $dueAt
     *
     * @ORM\Column(name="due_at", type="datetime", nullable=true)
     */
    private $dueAt;

    /**
     * @var datetime $startAt
     *
     * @ORM\Column(name="start_at", type="datetime", nullable=true)
     */
    private $startAt;
 
    /**
     * @var datetime $completedAt
     * @ORM\Column(name="completed_at", type="datetime", nullable=true)
     */
    private $completedAt;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * Many tasks to one project (OWNING SIDE)
     * The owning side of a bidirectional relationship must refer to its inverse side by use of the inversedBy attribute of the OneToOne, ManyToOne, or ManyToMany mapping declaration. The inversedBy attribute designates the field in the entity that is the inverse side of the relationship.
     * The many side of OneToMany/ManyToOne bidirectional relationships must be the owning side, hence the mappedBy element can not be specified on the ManyToOne side.
     * The owning side of a relationship determines the updates to the relationship in the database.
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="tasks")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="task", cascade={"destroy", "persist"})
     */
    private $activities;

    /**
      * @ORM\Column(name="lft", type="integer")
      * @Gedmo\TreeLeft
      */
    private $lft;

    /**
      * @ORM\Column(name="rgt", type="integer")
      * @Gedmo\TreeRight
      */
    private $rgt;

    /**
      * @ORM\Column(name="root", type="integer", nullable=true)
      * @Gedmo\TreeRoot
      */
    private $root;

    /**
      * @ORM\Column(type="integer")
      * @Gedmo\TreeLevel
      */
    private $lvl;

    /**
      * @ORM\ManyToOne(targetEntity="Task", inversedBy="children")
      * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
      * @Gedmo\TreeParent
      */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="users_assigned_tasks",
     *      joinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     */
    private $assignees;

    /**
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="tasks_tags",
     *      joinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    private $tags;

    public function __construct()
    {
        $this->activities = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assignees = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set id
     *
     * @param integer $id
     */
    public function setId($id)
     {
        // $this->id = $id;
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
     * Set estimated duration
     *
     * @param text $estimatedDuration
     */
    public function setEstimatedDuration($estimatedDuration)
    {
        $this->estimatedDuration = $estimatedDuration;
    }

    /**
     * Get estimated duration
     *
     * @return text 
     */
    public function getEstimatedDuration()
    {
        return $this->estimatedDuration;
    }

    /**
     * Set completedAt
     *
     * @param datetime $completedAt
     */
    public function setCompletedAt($completedAt)
    {
        $this->completedAt = $completedAt;
    }

    /**
     * Get completedAt
     *
     * @return boolean 
     */
    public function getCompletedAt()
    {
        return $this->completedAt;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreated($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreated()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdated($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime 
     */
    public function getUpdated()
    {
        return $this->updatedAt;
    }

    /**
     * Get dueAt
     *
     * @return datetime 
     */
    public function getDueAt()
    {
        return $this->dueAt;
    }

    /**
     * Set due
     *
     * @param datetime $dueAt
     */
    public function setDueAt($dueAt)
    {
        $this->dueAt = $dueAt;
    }

    /**
     * Get start
     *
     * @return datetime 
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Set start
     *
     * @param datetime $startAt
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    }


    /**
     * Set lft
     *
     * @param integer $lft
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
    }

    /**
     * Get lft
     *
     * @return integer 
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
    }

    /**
     * Get rgt
     *
     * @return integer 
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * Get root
     *
     * @return integer 
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;
    }

    /**
     * Get lvl
     *
     * @return integer 
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set project
     *
     * @param BlackRook\TaskBundle\Entity\Project $project
     */
    public function setProject(\BlackRook\TaskBundle\Entity\Project $project = null)
    {
        $this->project = $project;
    }

    /**
     * Get project
     *
     * @return BlackRook\TaskBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add activities
     *
     * @param BlackRook\TaskBundle\Entity\Activity $activities
     */
    public function addActivities(\BlackRook\TaskBundle\Entity\Activity $activities)
    {
        $this->activities[] = $activities;
    }

    /**
     * Get activities
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getActivities()
    {
        return $this->activities;
    }

    public function getDuration() {
        $total = 0;
        foreach ($this->activities as $activity) {
            $total += $activity->getDuration();
        }
        return $total;
    }

    /**
     * Set parent
     *
     * @param BlackRook\TaskBundle\Entity\Task $parent
     */
    public function setParent(\BlackRook\TaskBundle\Entity\Task $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return BlackRook\TaskBundle\Entity\Task 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param BlackRook\TaskBundle\Entity\Task $children
     */
    public function addChildren(\BlackRook\TaskBundle\Entity\Task $children)
    {
        $this->children[] = $children;
    }

    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add assignees
     *
     * @param BlackRook\TaskBundle\Entity\User $assignees
     */
    public function addAssignees(\BlackRook\TaskBundle\Entity\User $assignees)
    {
        $this->assignees[] = $assignees;
    }

    /**
     * Get assignees
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAssignees()
    {
        return $this->assignees;
    }

    /**
     * Add tags
     *
     * @param BlackRook\TaskBundle\Entity\Tag $tags
     */
    public function addTags(\BlackRook\TaskBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    }

    /**
     * Get tags
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }
}