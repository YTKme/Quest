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
	 * @OneToMany(targetEntity="TeamAchievementModel", mappedBy="team")
	 */
	protected $teamAchievements = NULL;
	
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
		$this->teamAchievements = new ArrayCollection();
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
			'point' => $this->getPoint(),
			'teamAchievements' => $this->getTeamAchievementsArray()
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
	 * Get team achievements
	 * 
	 * @return ArrayCollection
	 */
	public function getTeamAchievements () {
		return $this->teamAchievements;
	}
	
	/**
	 * Get team achievements array
	 * 
	 * @return array
	 */
	public function getTeamAchievementsArray () {
		// Create new team achievement array
		$teamAchievementArray = array();
		
		// Loop through each team achievement in the ArrayCollection
		foreach ($this->teamAchievements as $teamAchievement) {
			// Add each team achievement to new team achievement array
			array_push($teamAchievementArray, $teamAchievement->toArray());
		}
		
		// Return new team achievement array
		return $teamAchievementArray;
	}
	
}