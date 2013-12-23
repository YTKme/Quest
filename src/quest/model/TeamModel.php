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
	
}