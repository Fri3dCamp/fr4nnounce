<?php

class Session {
	public $sessionvars;
	public \DateTime $starttime;
	public \DateTime $endtime;
	public bool $future = false;
	public bool $inprogress = false;
	public int $distance;
	public function __construct(Array $pretalxSession, \DateTime $now){
		$this->sessionvars = $pretalxSession;
		$this->starttime = new \DateTime( $pretalxSession['date'] );
		$this->endtime = new \DateTime( $pretalxSession['date'] );
		$seconds = $this->durationToSeconds( $pretalxSession['duration'] );
		// Create interval
		$interval = \DateInterval::createFromDateString( $seconds . " second");

		// Add interval
		$this->endtime->add($interval);
		$this->sessionvars['end'] = $this->endtime->format('H:i');

		if ($now < $this->starttime) {
			$this->future = true;
		}

		$distance = $now->diff($this->starttime);
		$this->distance = $distance->d*24*60*60 + $this->distance = $distance->h*60 + $distance->i;
		if (1 === $distance->invert) {
			$this->distance *= -1;
		}

		if ($now >= $this->starttime && $now < $this->endtime) {
			$this->inprogress = true;
		}
		$this->sessionvars['future'] = $this->future;
		$this->sessionvars['inprogress'] = $this->inprogress;
		$this->sessionvars['distance'] = $this->distance;
	}

	public function durationToSeconds(string $dur) {
		$comps = explode(":", $dur);
		$hrs   = intval($comps[0]);
		$mins  = intval($comps[1]);
		return $hrs * 60 * 60 + $mins * 60;
	}
}