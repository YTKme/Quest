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
		$latitude = NULL,
		$longitude = NULL,
		$point = NULL
	) {
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
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
	
}