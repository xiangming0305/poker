<?php
/**
 * Created by PhpStorm.
 * User: daudau
 * Date: 7/17/18
 * Time: 10:31 PM
 */

class PrivateTournament
{
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECT = 2;

    public static function request(
        $game,
        $tables,
        $seats,
        $start_time,
        $status,
        $user_id,
        $invite_user_ids,
        $chips_to_pay,
        $buyin
    ) {
        $sql = new SQLConnection();
        $sql->query("INSERT INTO poker_tournament_requests(`game`, `table`, `seat`, `start_time`, `status`, `user_id` , `created_at`, `invite_user_ids`, `chips_to_pay`, `buyin`) 
          VALUES ('{$game}', '{$tables}', '{$seats}', '{$start_time}', '{$status}', '{$user_id}', NOW() , '{$invite_user_ids}', '{$chips_to_pay}', '{$buyin}')");

        if (mysqli_error($sql->DBSource)) {
            Logger::addReport("PrivateTournament::request", Logger::STATUS_ERROR, mysqli_error($sql->DBSource));
            die(mysqli_error($sql->DBSource));
            die('Cannot request tournament');
        }

        $id = mysqli_insert_id($sql->DB());

        $invitedIds = explode(',', $invite_user_ids);

        //need improve, but my need end fast
        foreach ($invitedIds as $invitedId) {
            $sql->query("INSERT INTO poker_tournament_request_invited_users (`tournament_request_id`, `user_id`) VALUES ({$id}, {$invitedId})");
        }

        return $id;
    }

    public static function getById($id)
    {
        $sql = new SQLConnection();
        $result = $sql->getAssocArray("SELECT * FROM poker_tournament_requests where id = {$id}");
        if (count($result)) {
            return $result[0];
        }
        return null;

    }

    public static function pending($from_date , $to_date, $playername,$invited_user,$tournament_name,$create_from_date,$create_to_date)
    {
	if($from_date != ''){
	$from_date = "AND t.start_time > '$from_date'";
	}
	if($to_date != ''){
	$to_date = "AND t.start_time < '$to_date'";
	}
	if($playername != ''){
	$playername = "AND u.name='$playername'";
	}

	if($create_from_date != ''){
	$create_from_date = "AND t.created_at > '$create_from_date'";
	}
	if($create_to_date != ''){
	$create_to_date = "AND t.created_at < '$create_to_date'";
	}
	if($tournament_name != ''){
	$tournament_name = "AND t.name='$tournament_name'";
	}
	$userIn=[];
	$inviId='';
	$sql = new SQLConnection();
		if($invited_user != ''){
			$temp = $sql->getArray("SELECT * FROM poker_users WHERE name='$invited_user'");
			if(count($temp)){
            $temp = $temp[0];
            $inviId = $temp['id'];
			$inviId = "AND ('$inviId'= t.invite_user_ids or '$inviId' = c.user_id)";
			}
			else {
			$inviId = "AND false";
			}
			
		}

        $query = "SELECT  t.*, GROUP_CONCAT(c.user_id ORDER BY c.id) AS add_invited_user , u.name as playername FROM poker_tournament_requests as t 
                INNER JOIN poker_users as u on t.user_id = u.id
                LEFT JOIN poker_tournament_request_invited_users as c on c.tournament_request_id = t.id 
                where t.name <> 'null' and t.status = '" . self::STATUS_PENDING . "' {$create_from_date} {$create_to_date} {$to_date} {$from_date}{$playername} {$tournament_name} {$inviId}
                group by t.id 
                order by t.created_at asc
                ";
        $results = $sql->getAssocArray($query);

        return $results;
    }

    public static function updateColumns($id, $data)
    {
        $sql = new SQLConnection();
        foreach ($data as $column => $value) { // need refactor this
            $sql->query("UPDATE poker_tournament_requests SET `{$column}` = '{$value}' where id = '{$id}'");
        }
    }

    public static function genKey($name)
    {
        return 'tournament_' . str_replace('/\s+/', '_', strtolower($name));
    }

    public static function delete($name)
    {
        Poker_Tournaments::Offline(['Name' => $name, 'Now' => 'Yes']);
        Poker_Tournaments::Delete(['Name' => $name]);
    }

    /**
     * @param array $userIds
     * @return array
     */
    public static function countUsersInvitation(
        $userIds,
        $fromDate = '',
        $toDate = '',
        $createdFromDate = '',
        $createdToDate = ''
    ) {
        $lstUserIds = implode(',', $userIds);
        $sql = new SQLConnection();
        $query = "SELECT c.name, count(a.user_id) as total 
FROM `poker_tournament_requests` as a join poker_tournament_request_invited_users as b on a.id = b.tournament_request_id
join poker_users c on a.user_id = c.id
where a.name != '' and a.user_id in ({$lstUserIds}) 
and (date(a.start_time) >= '{$fromDate}' or '$fromDate' = '') and (date(a.start_time)  <= '{$toDate}' or '$toDate' = '')
and (date(a.created_at) >= '{$createdFromDate}' or '$createdFromDate' = '') and (date(a.created_at)  <= '{$createdToDate}')
group by a.user_id";
        $result = $sql->getAssocArray($query);
//        echo $query;

        $query2 = "select c.name, count(a.user_id) as total 
from poker_tournament_request_invited_users a join poker_tournament_requests b on a.tournament_request_id = b.id
join poker_users c on a.user_id = c.id
where b.name != '' and a.user_id in ({$lstUserIds}) 
and (date(b.start_time) >= '{$fromDate}' or '$fromDate' = '') and (date(b.start_time)  <= '{$toDate}' or '$toDate' = '')
and (date(b.created_at) >= '{$createdFromDate}' or '$createdFromDate' = '') and (date(b.created_at)  <= '{$createdToDate}' or '$createdToDate' = '')
GROUP by a.user_id";
        $result2 = $sql->getAssocArray($query2);

        $expected = [];
        foreach ($result as $item) {
            $expected[$item['name']] = [
                'sent'     => $item['total'],
                'received' => 0
            ];
        }

        foreach ($result2 as $item) {
            if (isset($expected[$item['name']])) {
                $expected[$item['name']]['received'] = $item['total'];
            } else {
                $expected[$item['name']] = [
                    'sent'     => 0,
                    'received' => $item['total']
                ];
            }
        }

        return $expected;

    }

    public static function adminConfigFields()
    {
        return [
            ['name' => 'Shootout', 'type' => 'select', 'data' => ['Yes', 'No']],
            ['name' => 'Auto', 'type' => 'select', 'data' => ['Yes', 'No']],
            ['name' => 'SuspendChatAllIn', 'type' => 'select', 'data' => ['Yes', 'No']],
            ['name' => 'StartFull', 'type' => 'select', 'data' => ['Yes', 'No']],
            ['name' => 'StartMin', 'type' => 'number', 'min' => 0, 'max' => 10],
            ['name' => 'StartCode', 'type' => 'number', 'min' => 0, 'max' => 999999],
            ['name' => 'RegMinutes', 'type' => 'number', 'min' => 0, 'max' => 999999],
            ['name' => 'LateRegMinutes', 'type' => 'number', 'min' => 0, 'max' => 999999],
            ['name' => 'MinPlayers', 'type' => 'number', 'min' => 2, 'max' => 1000],
            ['name' => 'RecurMinutes', 'type' => 'number', 'min' => 1, 'max' => 999999],
            ['name' => 'ResetSeconds', 'type' => 'number', 'min' => 10, 'max' => 999999],
            ['name' => 'NoShowMinutes', 'type' => 'number', 'min' => 0, 'max' => 999999],
            ['name' => 'BuyIn', 'type' => 'number'],
            ['name' => 'EntryFee', 'type' => 'number'],
            ['name' => 'PrizeBonus', 'type' => 'number'],
            ['name' => 'MultiplyBonus', 'type' => 'select', 'data' => ['Yes', 'No', 'Min']],
            ['name' => 'Chips', 'type' => 'number', 'min' => 10, 'max' => 25000],
            ['name' => 'AddOnChips', 'type' => 'number', 'min' => 0, 'max' => 5000],
            ['name' => 'TurnClock', 'type' => 'number', 'min' => 10, 'max' => 120],
            ['name' => 'TurnWarning', 'type' => 'number', 'min' => 5, 'max' => 119],
            ['name' => 'TimeBank', 'type' => 'number', 'min' => 0, 'max' => 600],
            ['name' => 'BankSync', 'type' => 'select', 'data' => ['Yes', 'No']],
            ['name' => 'BankReset', 'type' => 'number', 'min' => 0, 'max' => 999999],
            ['name' => 'DisProtect', 'type' => 'select', 'data' => ['Yes', 'No']],
            ['name' => 'Level', 'type' => 'number', 'min' => 1, 'max' => 1000],
            ['name' => 'RebuyLevels', 'type' => 'number', 'min' => 1, 'max' => 1000],
            ['name' => 'Threshold', 'type' => 'number', 'min' => 0, 'max' => 999999],
            ['name' => 'MaxRebuys', 'type' => 'number'],
            ['name' => 'RebuyCost', 'type' => 'number'],
            ['name' => 'RebuyFee', 'type' => 'number'],
            ['name' => 'BreakTime', 'type' => 'number', 'min' => 0, 'max' => 60],
            ['name' => 'BreakLevels', 'type' => 'number', 'min' => 0, 'max' => 1000],
            ['name' => 'StopOnChop', 'type' => 'select', 'data' => ['Yes', 'No']],
            ['name' => 'BringInPercent', 'type' => 'number', 'min' => 1, 'max' => 99],
            ['name' => 'Blinds', 'type' => 'text'],
            ['name' => 'Payout', 'type' => 'text'],
            ['name' => 'UnregLogout', 'type' => 'select', 'data' => ['Yes', 'No']],
        ];
    }
}