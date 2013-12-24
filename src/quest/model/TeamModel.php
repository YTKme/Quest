<?php

use Doctrine\ORM\Id\SequenceGenerator;

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
	protected $id;
	
	/**
	 * @Column(name="name", unique=true)
	 */
	protected $name = NULL;
	
	/**
	 * @Column(name="point", type="integer")
	 */
	protected $point = NULL;
	
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
	
}