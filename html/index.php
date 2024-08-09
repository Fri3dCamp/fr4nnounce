<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../classes/Session.php';

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified 
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 
header("Pragma: no-cache"); // HTTP/1.0 

$pagetoshow = $_GET['page'] ?? 'grid'; // currently not used
$roomtoshow = $_GET['room'] ?? null;

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates' );

$json = file_get_contents('https://content.fri3d.be/fri3dcamp2024/schedule/export/schedule.json');
$json_data = json_decode($json,true);

$ts = isset($_GET['now']) && is_numeric($_GET['now']) ? intval( $_GET['now'] ) : time(); 

$schedule = $json_data["schedule"]["conference"]["days"];

$daynames = ['onbekend','maandag','dinsdag','woensdag','donderdag','vrijdag','zaterdag','zondag'];

$now = new \DateTime();
$now->setTimezone(new DateTimeZone('Europe/Brussels'));
$now->setTimestamp($ts);

$daynr = intval($now->format('N'));

$sessionsToShow = [];

foreach ($schedule as $day) {
	foreach( $day["rooms"] as $room => $sessions) {
		foreach($sessions as $s) {
			$maybe = new Session($s, $now);
			if (true == $maybe->future || (true == $maybe->inprogress && $maybe->distance > -10 ) ) {
				if (null === $roomtoshow || $roomtoshow == $room ) {
					$sessionsToShow[] = $maybe;
				}
			}
		}
	}
}


usort($sessionsToShow, function($a, $b) {
    if($a->starttime > $b->starttime) {
        return 1;
    }
    elseif($a->starttime < $b->starttime) {
        return -1;
    }
    else {
        return 0;
    }
});

$twig = new \Twig\Environment($loader, [
    'cache'      => __DIR__ . '/../templatecache',
	'autoescape' => false,
	'debug'      => true,
]);

$params = [];

$params['title'] = $daynames[$daynr];
$params['sessions'] = $sessionsToShow;
$params['time'] = $now->format('Y-m-d H:i:sP');
echo $twig->render('grid.twig', $params);