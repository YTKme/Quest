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
	 * @Column(name="description", nullable=true)
	 */
	protected $description = NULL;
	
	/**
	 * @Column(name="icon", nullable=true)
	 */
	protected $icon = NULL;
	
	/**
	 * @Column(name="latitude", nullable=true)
	 */
	protected $latitude = NULL;
	
	/**
	 * @Column(name="longitude", nullable=true)
	 */
	protected $longitude = NULL;
	
	/**
	 * @Column(name="point", type="integer", nullable=true)
	 */
	protected $point = NULL;
	
	/**
	 * @ManyToOne(targetEntity="GameModel", inversedBy="achievements")
	 * @JoinColumn(name="game_id", referencedColumnName="id")
	 */
	protected $game = NULL;
	
	/**
	 * @OneToMany(targetEntity="TeamAchievementModel", mappedBy="achievement")
	 */
	protected $teamAchievements = NULL;
	
	/**
	 * Constructor
	 * 
	 * @param string $id
	 * @param string $name
	 * @param string $description
	 * @param string $picture
	 * @param string $latitude
	 * @param string $longitude
	 * @param string $point
	 */
	public function __construct (
		$id = NULL,
		$name = NULL,
		$description = NULL,
		$icon = NULL,
		$latitude = NULL,
		$longitude = NULL,
		$point = NULL
	) {
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
		$this->icon = $icon;
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
			'achievementId' => $this->getId(),
			'achievementName' => $this->getName(),
			'achievementDescription' => $this->getDescription(),
			'achievementIcon' => $this->getIcon(),
			'achievementLatitude' => $this->getLatitude(),
			'achievementLongitude' => $this->getLongitude(),
			'achievementPoint' => $this->getPoint(),
			'achievementGame' => empty($this->getGame())
				? NULL
				: $this->getGame()->toAchievementArray()
		);
	}
	
	/**
	 * To game array
	 * 
	 * @return array
	 */
	public function toGameArray () {
		return array(
			'achievementId' => $this->getId(),
			'achievementName' => $this->getName(),
			'achievementDescription' => $this->getDescription(),
			'achievementIcon' => $this->getIcon(),
			'achievementLatitude' => $this->getLatitude(),
			'achievementLongitude' => $this->getLongitude(),
			'achievementPoint' => $this->getPoint()
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
	 * Get icon
	 * 
	 * @return string
	 */
	public function getIcon () {
		return $this->icon;
	}
	
	/**
	 * Set icon
	 * 
	 * @param string $icon
	 */
	public function setIcon ($icon = NULL) {
		$this->icon = $icon;
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
	 * @param GameModel $game
	 */
	public function setGame (GameModel $game = NULL) {
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