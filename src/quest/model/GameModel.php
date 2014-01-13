<?php

use Doctrine\ORM\Id\SequenceGenerator;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="game")
 */
class GameModel {
	
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
	 * @Column(name="description", nullable=true)
	 */
	protected $description = NULL;
	
	/**
	 * @Column(name="location", nullable=true)
	 */
	protected $location = NULL;
	
	/**
	 * @OneToMany(targetEntity="AchievementModel", mappedBy="game")
	 */
	protected $achievements = NULL;
	
	/**
	 * @OneToMany(targetEntity="EventModel", mappedBy="game")
	 */
	protected $events = NULL;
	
	/**
	 * Constructor
	 * 
	 * @param string $id
	 * @param string $name
	 * @param string $description
	 * @param string $location
	 */
	public function __construct (
		$id = NULL,
		$name = NULL,
		$description = NULL,
		$location = NULL
	) {
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
		$this->location = $location;
		
		$this->events = new ArrayCollection();
		$this->achievements = new ArrayCollection();
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
			'description' => $this->getDescription(),
			'location' => $this->getLocation(),
			'achievements' => $this->getAchievementsArray()
		);
	}
	
	/**
	 * To achievement array
	 * 
	 * @return array
	 */
	public function toAchievementArray () {
		return array(
			'id' => $this->getId(),
			'name' => $this->getName(),
			'description' => $this->getDescription(),
			'location' => $this->getLocation()
		);
	}
	
	/**
	 * Get ID
	 * 
	 * @return integer
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
	 * Get description
	 * 
	 * @return string
	 */
	public function getDescription () {
		return $this->description;
	}
	
	/**
	 * Set description
	 * 
	 * @param string $description
	 */
	public function setDescription ($description = NULL) {
		$this->description = $description;
	}
	
	/**
	 * Get location
	 * 
	 * @return string
	 */
	public function getLocation () {
		return $this->location;
	}
	
	/**
	 * Set location
	 * 
	 * @param string $location
	 */
	public function setLocation ($location = NULL) {
		$this->location = $location;
	}
	
	/**
	 * Get events
	 * 
	 * @return ArrayCollection
	 */
	public function getEvents () {
		return $this->events;
	}
	
	/**
	 * Get achievements
	 * 
	 * @return ArrayCollection
	 */
	public function getAchievements () {
		return $this->achievements;
	}
	
	/**
	 * Get achievements array
	 * 
	 * @return array
	 */
	public function getAchievementsArray () {
		// Create new achievement array
		$achievementArray = array();
		
		// Loop through each achievement in the ArrayCollection
		foreach ($this->achievements as $achievement) {
			// Add each achievement to new achievement array
			array_push($achievementArray, $achievement->toGameArray());
		}
		
		// Return new achievement array
		return $achievementArray;
	}
	
}