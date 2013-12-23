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
	
}