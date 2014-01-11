<?php

use Doctrine\ORM\Id\SequenceGenerator;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="team_achievement")
 */
class TeamAchievementModel {
	
	/**
	 * @Id
	 * @ManyToOne(targetEntity="TeamModel", inversedBy="teamAchievements")
	 * @JoinColumn(name="team_id", referencedColumnName="id")
	 */
	protected $team = NULL;
	
	/**
	 * @Id
	 * @ManyToOne(targetEntity="AchievementModel", inversedBy="teamAchievements")
	 * @JoinColumn(name="achievement_id", referencedColumnName="id")
	 */
	protected $achievement = NULL;
	
	/**
	 * @Column(name="picture")
	 */
	protected $picture = NULL;
	
}