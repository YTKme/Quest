<?php

use Doctrine\ORM\Id\SequenceGenerator;

/**
 * @Entity
 * @Table(name="event")
 */
class EventModel {
	
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
	 * @Column(name="start", type="datetimetz")
	 */
	protected $start = NULL;
	
	/**
	 * @Column(name="length", type="time")
	 */
	protected $length = NULL;
	
	/**
	 * Constructor
	 * 
	 * @param string $id
	 * @param string $code
	 * @param string $name
	 * @param string $description
	 * @param string $start
	 * @param string $length
	 */
	public function __construct (
		$id = NULL,
		$code = NULL,
		$name = NULL,
		$description = NULL,
		$start = NULL,
		$length = NULL
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
	}
	
	/**
	 * To array
	 * 
	 * @return array
	 */
	public function toArray () {
		return array(
			'id' => $this->getId(),
			'code' => $this->getCode(),
			'name' => $this->getName(),
			'description' => $this->getDescription(),
			'start' => $this->getStart(),
			'length' => $this->getLength()
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
	 * Get code
	 * 
	 * @return string
	 */
	public function getCode () {
		return $this->code;
	}
	
	/**
	 * Set code
	 * 
	 * @param string $code
	 */
	public function setCode ($code = NULL) {
		$this->code = 
			empty($code)
				? $this->generateRandomCode()
				: $code;
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
	 * Get start
	 * 
	 * @return DateTime
	 */
	public function getStart () {
		return $this->start;
	}
	
	/**
	 * Set start
	 * 
	 * @param string $start
	 */
	public function setStart ($start = NULL) {
		$this->start = 
			empty($start)
				? NULL
				: new DateTime($start);
	}
	
	/**
	 * Get length
	 * 
	 * @return DateTime
	 */
	public function getLength () {
		return $this->length;
	}
	
	/**
	 * Set length
	 * 
	 * @param string $length
	 */
	public function setLength ($length = NULL) {
		$this->length =
			empty($length)
				? NULL
				: new DateTime($length);
	}
	
	/**
	 * Generate random code
	 * 
	 * @param integer $octetCount
	 * @param integer $octetLength
	 * @return string
	 */
	private function generateRandomCode ($octetCount = 4, $octetLength = 3) {
		// List of characters
		$character = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomCode = '';
		
		// Loop through octet count
		for ($i = 0; $i < $octetCount; $i++) {
			// Loop through octet length
			for ($j = 0; $j < $octetLength; $j++) {
				// Pick random character and add to code
				$randomCode .= $character[rand(0, strlen($character) - 1)];
			}
			
			// Add a separator
			$randomCode .= '-';
		}
		
		// Take out the last seperator before returing
		return substr($randomCode, 0, -1);
	}
	
}