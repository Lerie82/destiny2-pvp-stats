<!doctype html>
<html lang="en">
<head>
	<title></title>
	<link rel="stylesheet" href="css/d2.css" />
</head>
<body>

<?php
$html = NULL;
$data = json_decode(file_get_contents("https://api.tracker.gg/api/v2/destiny-2/standard/profile/psn/lerie82/sessions?fetchCount=5&groupBy=day&perspective=pvp"));
//$data = json_decode(file_get_contents("matches.tmp"));

for($i=0;$i<5;$i++)
{
	$match = $data->data->items[0]->matches[$i];

		$match_completed = $match->metadata->completed->displayValue;
        	$match_playlist = $match->metadata->playlist->displayValue;
        	$match_map = $match->metadata->map->displayValue;
        	$match_result = $match->metadata->result->displayValue;
        	//$playlist_icon = $match->metadata->playlistIconUrl->displayValue;
        	$match_start = $match->metadata->startDate->displayValue;

		//parse end date
        	$match_date = date_parse($match->metadata->endDate->displayValue);
		$match_end = $match_date['month'].'/'.$match_date['day'].'/'.$match_date['year'].' @ '.$match_date['hour'].':'.str_pad($match_date['minute'], 2, "0", STR_PAD_LEFT);

        	$stats_score = $match->stats->score->displayValue;
        	$stats_assists = $match->stats->assists->displayValue;
        	$stats_deaths = $match->stats->deaths->displayValue;
        	$stats_kills = $match->stats->kills->displayValue;
        	$stats_kd = $match->stats->kd->displayValue;
        	$stats_kda = $match->stats->kda->displayValue;

                $glory = $data->data->items[0]->matches[$i]->stats->glory->metadata->value;
		$wins = $data->data->items[0]->stats->wins->displayValue;
		$losses = $data->data->items[0]->stats->losses->displayValue;

                $stats = [
                        'completed' => $match_completed,
                        'playlist' => $match_playlist,
                        'map' => $match_map,
                        'result' => $match_result,
                        'duration' => $match_end,
                        'score' => $stats_score,
                        'assists' => $stats_assists,
                        'deaths' => $stats_deaths,
                        'kills' => $stats_kills,
                        'kd' => $stats_kd,
                        'kda' => $stats_kda,
        		'glory' => $glory,
			'wins' => $wins,
			'losses' => $losses
                ];

        $html .= getTemplate("match", $stats);
}

function getTemplate($name, $vars)
{
        $templ = "templates/".$name.".tpl";
        $src = file_get_contents($templ);

        foreach ($vars as $key => $value)
        {
                if($value == NULL) $value = "-";

                $src = preg_replace("/{#$key}/", $value, $src);
        }

        return $src;
}

?>

<p><a href="https://tracker.gg/" title="tracker.gg"><img src="trackernet.png" width="150" alt="tracker.gg" /></a></p>
<?=$html?>

</body>
</html>
