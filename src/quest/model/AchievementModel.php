<?php

use Doctrine\ORM\Id\SequenceGenerator;

/**
 * @Entity
 * @Table(name="achievement")
 */
class AchievementModel {
	
	/**
	 * @Id
	 * @Column(name="id", type="smallint", options={"unsigned"=true})
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @Column(name="name", unique=true)
	 */
	protected $name = NULL;
	
	/**
	 * @Column(name="description")
	 */
	protected $description = NULL;
	
	/**
	 * @Column(name="picture")
	 */
	protected $picture = NULL;
	
	/**
	 * @Column(name="latitude")
	 */
	protected $latitude = NULL;
	
	/**
	 * @Column(name="longitude")
	 */
	protected $longitude = NULL;
	
	/**
	 * @Column(name="point", type="integer")
	 */
	protected $point = NULL;
	
	/**
	 * @ManyToOne(targetEntity="GameModel", inversedBy="achievements")
	 * @JoinColumn(name="game_id", referencedColumnName="id")
	 */
	protected $game = NULL;
	
	/**
	 * Constructor
	 * 
	 * @param string $id
	 * @param string $name
	 * @param string $description
	 * @param string $latitude
	 * @param string $longitude
	 * @param string $point
	 */
	public function __construct (
		$id = NULL,
		$name = NULL,
		$description = NULL,
		$picture = NULL,
		$latitude = NULL,
		$longitude = NULL,
		$point = NULL
	) {
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
		$this->picture = $picture;
		$this->latitude = $latitude;
		$this->longitude = $longitude;
		$this->point = $point;
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
			'picture' => $this->getPicture(),
			'latitude' => $this->getLatitude(),
			'longitude' => $this->getLongitude(),
			'point' => $this->getPoint(),
			'game' => $this->getGame()->toAchievementArray()
		);
	}
	
	/**
	 * To game array
	 * 
	 * @return array
	 */
	public function toGameArray () {
		return array(
			'id' => $this->getId(),
			'name' => $this->getName(),
			'description' => $this->getDescription(),
			'picture' => $this->getPicture(),
			'latitude' => $this->getLatitude(),
			'longitude' => $this->getLongitude(),
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
	 * Get picture
	 * 
	 * @return string
	 */
	public function getPicture () {
		return $this->picture;
	}
	
	/**
	 * Set picture
	 * 
	 * @param string $picture
	 */
	public function setPicture ($picture = NULL) {
		$this->picture = $picture;
	}
	
	/**
	 * Get latitude
	 * 
	 * @return string
	 */
	public function getLatitude () {
		return $this->latitude;
	}
	
	/**
	 * Set latitude
	 * 
	 * @param string $latitude
	 */
	public function setLatitude ($latitude = NULL) {
		$this->latitude = $latitude;
	}
	
	/**
	 * Get longitude
	 * 
	 * @return string
	 */
	public function getLongitude () {
		return $this->longitude;
	}
	
	/**
	 * Set longitude
	 * 
	 * @param string $longitude
	 */
	public function setLongitude ($longitude = NULL) {
		$this->longitude = $longitude;
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
	 * Get game
	 * 
	 * @return GameModel
	 */
	public function getGame () {
		return $this->game;
	}
	
	/**
	 * Set game
	 * 
	 * @param string $game
	 */
	public function setGame ($game = NULL) {
		// Check if the new game is NULL
		if ($game === NULL) {
			// Check if the game for this achievement is NULL
			if ($this->game !== NULL) {
				// Remove this achievement from the game
				$this->game->getAchievements()->removeElement($this);
			}
				
			// Set the game for this achievement to NULL
			$this->game = NULL;
		} else {
			// Check if the game for this achievement is NULL
			if ($this->game !== NULL) {
				// Remove this achievement from the game
				$this->game->getEvents()->removeElement($this);
			}
				
			// Set the game for this achievement
			$this->game = $game;
			// Add the achievement to the collection for the game
			$game->getAchievements()->add($this);
		}
	}
	
}