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
	
	/**
	 * Constructor
	 */
	public function __construct (
		TeamModel $team = NULL,
		AchievementModel $achievement = NULL,
		$picture = NULL
	) {
		$this->team = $team;
		$this->achievement = $achievement;
		$this->picture = $picture;
	}
	
	/**
	 * Get team
	 * 
	 * @return TeamModel
	 */
	public function getTeam () {
		return $this->team;
	}
	
	/**
	 * Set team
	 * 
	 * @param TeamModel $team
	 */
	public function setTeam (TeamModel $team = NULL) {
		$this->team = $team;
	}
	
	/**
	 * Get achievement
	 * 
	 * @return AchievementModel
	 */
	public function getAchievement () {
		return $this->achievement;
	}
	
	/**
	 * Set achievement
	 * 
	 * @param AchievementModel $achievement
	 */
	public function setAchievement (AchievementModel $achievement = NULL) {
		$this->achievement = $achievement;
	}
	
	/**
	 * Get picture
	 * 
	 * @return string
	 */
	public function getPicture () {
		return $this->picture;
	}
	
	/**
	 * Set picture
	 * 
	 * @param string $picture
	 */
	public function setPicture ($picture = NULL) {
		$this->picture = $picture;
	}
	
}