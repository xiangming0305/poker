<pre>
<?php
    $x;
    set_time_limit(0);
    function formatT($d){
        global $x;
        $x+=(round($d*100000)/100);
        return (round($d*100000)/100)." ms";
    }

  /*  $root = "C:/inetpub/wwwroot/poker 1/";

    $_SERVER['DOCUMENT_ROOT']=$root;
    $_SERVER['SCRIPT_FILENAME']=$root."/cron/index.php";*/
    date_default_timezone_set("Asia/Beirut");
    
    require_once "../classes/Classes.php";

    $sql = new SQLConnection();

    if (isset($_POST['query'])) {
        $temp = $sql->getArray($_POST['query']);
        echo '<table border="1">';
        foreach ($temp as $key => $value) {
            echo '<tr>';
            foreach($value as $key2=>$col) if (!is_numeric($key2)) echo '<td>'.$col.'</td>';
            echo '</tr>';
        }
        echo '</table>';
        die();
    }

    $temp = $sql->getArray("SELECT * FROM poker_users");
foreach ($temp as $key => $value) {
    $user = new User($value['id']);

    $dateAff = NULL;
    $dateAff2 = NULL;
    $affiliate = $user->getParentAffiliate();
    $affiliate2 = $affiliate->getParentAffiliate();
    if ($affiliate->isAffiliate()) {
        $dateAff = $sql->getArray("SELECT answered FROM `poker_affiliate_requests` WHERE user='".$affiliate->playername."'")[0][0];
    }
    if ($affiliate2->isAffiliate()) {
        $dateAff2 = $sql->getArray("SELECT answered FROM `poker_affiliate_requests` WHERE user='".$affiliate2->playername."'")[0][0];
    }

    if ($dateAff || $dateAff2) {
        $rake_aff = 0;
        $rake_aff2 = 0;
        $handData = Poker_Cache::getHandByPlayer($user->playername);
        foreach ($handData as $hand) {
            //$game = Poker_Cache::getRingGame($hand['ring_name']);
            if ($dateAff && $hand['date'] >= $dateAff) $rake_aff += $hand['player_rake']*1;
            if ($dateAff2 && $hand['date'] >= $dateAff2) $rake_aff2 += $hand['player_rake']*1;
        }
        $sql->query("UPDATE poker_users SET rake_aff=$rake_aff, rake_aff2=$rake_aff2 WHERE id=".$value['id']);
    }
}
require 'tournament_fees.php';
Poker_Calculations::recalculateUserPoints();


echo 'Finished';
?>
</pre>