<?php

use Doctrine\ORM\Id\SequenceGenerator;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="team")
 */
class TeamModel {
	
	/**
	 * @Id
	 * @Column(name="id", type="smallint", options={"unsigned"=true})
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id = NULL;
	
	/**
	 * @Column(name="name", unique=true)
	 */
	protected $name = NULL;
	
	/**
	 * @Column(name="point", type="integer")
	 */
	protected $point = NULL;
	
	/**
	 * @ManyToMany(targetEntity="EventModel", inversedBy="teams")
	 * @JoinTable(name="team_event", joinColumns={@JoinColumn(name="team_id", referencedColumnName="id")}, inverseJoinColumns={@JoinColumn(name="event_id", referencedColumnName="id")})
	 */
	protected $events = NULL;
	
	/**
	 * @ManyToMany(targetEntity="AchievementModel", inversedBy="teams")
	 * @JoinTable(name="team_achievement", joinColumns={@JoinColumn(name="team_id", referencedColumnName="id")}, inverseJoinColumns={@JoinColumn(name="achievement_id", referencedColumnName="id")})
	 */
	protected $achievements = NULL;
	
	/**
	 * Constructor
	 * 
	 * @param string $id
	 * @param string $name
	 * @param string $point
	 */
	public function __construct (
		$id = NULL,
		$name = NULL,
		$point = NULL
	) {
		$this->id = $id;
		$this->name = $name;
		$this->point = $point;
		
		$this->events = new ArrayCollection();
	}
	
	/**
	 * To array
	 * 
	 * @return array
	 */
	public function toArray () {
		return array(
			'id' => $this->getId(),
			'name' => $this->getName(),
			'point' => $this->getPoint()
		);
	}
	
	/**
	 * Get ID
	 * 
	 * @return string
	 */
	public function getId () {
		return $this->id;
	}
	
	/**
	 * Get name
	 * 
	 * @return string
	 */
	public function getName () {
		return $this->name;
	}
	
	/**
	 * Set name
	 * 
	 * @param string $name
	 */
	public function setName ($name = NULL) {
		$this->name = $name;
	}
	
	/**
	 * Get point
	 * 
	 * @return integer
	 */
	public function getPoint () {
		return $this->point;
	}
	
	/**
	 * Set point
	 * 
	 * @param string $point
	 */
	public function setPoint ($point = NULL) {
		$this->point = $point;
	}
	
	/**
	 * Get events
	 * 
	 * @return array
	 */
	public function getEvents () {
		// Create new event array
		$eventArray = array();
		
		// Loop through each event in the ArrayCollection
		foreach ($this->events as $event) {
			// Add each event to new event array
			array_push($eventArray, $event->toArray());
		}
		
		// Return new event array
		return $eventArray;
	}
	
	/**
	 * Add event
	 * 
	 * @param EventModel $event
	 */
	public function addEvent (EventModel $event = NULL) {
		// Synchronously updating inverse side
		$event->addTeam($this);
		
		// Add event to the ArrayCollection
		$this->events[] = $event;
	}
	
	/**
	 * Get achievements
	 * 
	 * @return array
	 */
	public function getAchievements () {
		// Create new achievement array
		$achievementArray = array();
		
		// Loop through each achievement in the ArrayCollection
		foreach ($this->achievements as $achievement) {
			// Add each achievement to new achievement array
			array_push($achievementArray, $achievement->toArray());
		}
		
		// Return new achievement array
		return $achievementArray;
	}
	
	/**
	 * Add achievement
	 * 
	 * @param AchievementModel $achievement
	 */
	public function addAchievement (AchievementModel $achievement = NULL) {
		// Synchronously updating inverse side
		$achievement->addTeam($this);
		
		// Add achievement to the ArrayCollection
		$this->achievements[] = $achievement;
	}
	
}