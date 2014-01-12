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
	 * @Column(name="id", type="smallint", options={"unsigned"=true})
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id = NULL;
	
	/**
	 * @ManyToOne(targetEntity="TeamModel", inversedBy="teamAchievements")
	 * @JoinColumn(name="team_id", referencedColumnName="id")
	 */
	protected $team = NULL;
	
	/**
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
	 * 
	 * @param TeamModel $team
	 * @param AchievementModel $achievement
	 * @param string $picture
	 */
	public function __construct (
		$id = NULL,
		TeamModel $team = NULL,
		AchievementModel $achievement = NULL,
		$picture = NULL
	) {
		$this->id = $id;
		$this->team = $team;
		$this->achievement = $achievement;
		$this->picture = $picture;
	}
	
	/**
	 * To array
	 * 
	 * @return array
	 */
	public function toArray () {
		return array(
			'id' => $this->getId(),
			'picture' => $this->getPicture()
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
		// Check if the team is NULL
		if ($team === NULL) {
			// Check if the team for this team achievement is NULL
			if ($this->team !== NULL) {
				// Remove this team achievement from the team
				$this->team->getTeamAchievements()->removeElement($this);
			}
			
			// Set the team for this team achievement to NULL
			$this->team = NULL;
		} else {
			// Check if the team for this team achievement is NULL
			if ($this->team !== NULL) {
				// Remove this team achievement from the team
				$this->team->getTeamAchievements()->removeElement($this);
			}
			
			// Set the team for this team achievement
			$this->team = $team;
			// Add the team achievement to the collection for the team
			$team->getTeamAchievements()->add($this);
		}
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