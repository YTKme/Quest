<?php

use Doctrine\ORM\Id\SequenceGenerator;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="user")
 */
class UserModel {
	
	/**
	 * @Id
	 * @Column(name="id", type="smallint", options={"unsigned"=true})
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id = NULL;
	
	/**
	 * @Column(name="username", unique=true)
	 */
	protected $username = NULL;
	
	/**
	 * @Column(name="password")
	 */
	protected $password = NULL;
	
	/**
	 * @Column(name="role")
	 */
	protected $role = NULL;
	
	/**
	 * @Column(name="first_name")
	 */
	protected $firstName = NULL;
	
	/**
	 * @Column(name="last_name")
	 */
	protected $lastName = NULL;
	
	/**
	 * Constructor
	 * 
	 * @param string $id
	 * @param string $username
	 * @param string $password
	 */
	public function __construct (
		$id = NULL,
		$username = NULL,
		$password = NULL,
		$role = NULL,
		$firstName = NULL,
		$lastName = NULL
	) {
		$this->id = $id;
		$this->username = $username;
		$this->password = hash('sha256', hash('sha256', $password) . $this->getUsername());
		$this->role = $role;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
	}
	
	/**
	 * To array
	 * 
	 * @return array
	 */
	public function toArray () {
		return array(
			'id' => $this->getId(),
			'username' => $this->getUsername(),
			'role' => $this->getRole(),
			'firstName' => $this->getFirstName(),
			'lastName' => $this->getLastName()
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
	 * Get username
	 * 
	 * @return string
	 */
	public function getUsername () {
		return $this->username;
	}
	
	/**
	 * Set uesrname
	 * 
	 * @param string $username
	 */
	public function setUsername ($username = NULL) {
		$this->username = $username;
	}
	
	/**
	 * Get password
	 * 
	 * @return string
	 */
	public function getPassword () {
		return $this->password;
	}
	
	/**
	 * Set password
	 * 
	 * @param string $password
	 */
	public function setPassword ($password = NULL) {
		$this->password = hash('sha256', hash('sha256', $password) . $this->getUsername());
	}
	
	/**
	 * Get role
	 * 
	 * @return string
	 */
	public function getRole () {
		return $this->role;
	}
	
	/**
	 * Set role
	 * 
	 * @param string $role
	 */
	public function setRole ($role = NULL) {
		$this->role = $role;
	}
	
	/**
	 * Get first name
	 * 
	 * @return string
	 */
	public function getFirstName () {
		return $this->firstName;
	}
	
	/**
	 * Set first name
	 * 
	 * @param string $firstName
	 */
	public function setFirstName ($firstName = NULL) {
		$this->firstName = $firstName;
	}
	
	/**
	 * Get last name
	 * 
	 * @return string
	 */
	public function getLastName () {
		return $this->lastName;
	}
	
	/**
	 * Set last name
	 * 
	 * @param string $lastName
	 */
	public function setLastName ($lastName = NULL) {
		$this->lastName = $lastName;
	}
	
}