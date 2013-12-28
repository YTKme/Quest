<?php

use Doctrine\ORM\Id\SequenceGenerator;

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
	 * @Column(name="description")
	 */
	protected $description = NULL;
	
	/**
	 * @Column(name="location")
	 */
	protected $location = NULL;
	
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
	
}