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
	 * @Column(name="code", unique=true)
	 */
	protected $code = NULL;
	
	/**
	 * @Column(name="name", unique=true)
	 */
	protected $name = NULL;
	
	/**
	 * @Column(name="description")
	 */
	protected $description = NULL;
	
	/**
	 * @Column(name="start", type="datetimez")
	 */
	protected $start = NULL;
	
	/**
	 * @Column(name="length", type="time")
	 */
	protected $length = NULL;
	
	/**
	 * @Column(name="location")
	 */
	protected $location = NULL;
	
	/**
	 * Constructor
	 */
	public function __construct (
		$id = NULL,
		$code = NULL,
		$name = NULL,
		$description = NULL,
		$start = NULL,
		$length = NULL,
		$location = NULL
	) {
		$this->id = $id;
		$this->code = $code;
		$this->name = $name;
		$this->description = $description;
		$this->start =
			empty($start)
				? NULL
				: new DateTime($start);
		$this->length = 
			empty($length)
				? NULL
				: new DateTime($length);
		$this->location = $location;
	}
	
	/**
	 * To array
	 * @return array
	 */
	public function toArray () {
		
	}
	
	/**
	 * Get ID
	 * @return integer
	 */
	public function getId () {
		return $this->id;
	}
	
	/**
	 * Get code
	 * @return string
	 */
	public function getCode () {
		return $this->code;
	}
	
	/**
	 * Set code
	 * @param string $code
	 */
	public function setCode ($code = NULL) {
		$this->code = $code;
	}
	
	/**
	 * Get name
	 * @return string
	 */
	public function getName () {
		return $this->name;
	}
	
	/**
	 * Set name
	 * @param string $name
	 */
	public function setName ($name = NULL) {
		$this->name = $name;
	}
	
	/**
	 * Get description
	 * @return string
	 */
	public function getDescription () {
		return $this->description;
	}
	
	/**
	 * Set description
	 * @param string $description
	 */
	public function setDescription ($description = NULL) {
		$this->description = $description;
	}
	
}