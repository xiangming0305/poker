<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    
class User{
    
    private $data;
    private $id;
    public $balance, $playername, $chips, $email, $referral, $location, $gender, $api_id, $ssid,$registered, $totalrake, $comission, $country_geolocated, $show_realname, $show_email, $points_spend_registration, $referrals_before_affiliate;

    public function __construct($id, $col = "id"){
        $sql = new SQlConnection();
        $temp = $sql->getArray("SELECT pu.*, r.total_rake FROM poker_users pu LEFT JOIN poker_player_rake r ON pu.name = r.player_name WHERE pu.".$col."='$id'");
        if(count($temp)){
            $temp = $temp[0];
            $this->id = $temp['id'];
            $this->balance = $temp['balance'];
            $this->playername = $temp['name'];
            $this->chips = $temp['chips'];
            $this->email = $temp['email'];
            $this->referral = $temp['referral'];
            $this->password = $temp['password'];
            $this->realname = $temp['realname'];
            $this->location = $temp['location'];
            $this->gender = $temp['gender'];
            $this->api_id = $temp['api_id'];
            $this->registered = $temp['registered'];
            //$this->totalrake = $temp['totalrake'];
            $this->rake = $temp['total_rake'];
            $this->income = $temp['income']*1;
            $this->income_paid = $temp['income_paid']*1;
            $this->rake_parsed = $temp['rake_parsed'];
            $this->comission = $temp['comission']*1;
            $this->level2_comission = $temp['level2_comission']*1;
            $this->level2 = $temp["level2"];
            $this->ssid = $temp["ssid"];
            $this->points = $temp['points'];
            $this->points_dec = $temp['points_dec']*1;
            $this->tournaments_fee = $temp['tournaments_fee']*1;
            $this->referral_level = $temp["referral_level"]*1;
            $this->link2_commission = $temp["link2_commission"]*1;
            $this->tournaments_fee_aff = $temp["tournaments_fee_aff"]*1;
            $this->freeroll_fee_aff = $temp["freeroll_fee_aff"]*1;
            $this->rake_aff = $temp["rake_aff"]*1;
            $this->tournaments_fee_aff2 = $temp["tournaments_fee_aff2"]*1;
            $this->freeroll_fee_aff2 = $temp["freeroll_fee_aff2"]*1;
            $this->rake_aff2 = $temp["rake_aff2"]*1;
            $this->country_geolocated = $temp['country_geolocated'];
            $this->show_realname = $temp['show_realname']*1;
            $this->show_email = $temp['show_email']*1;
            $this->points_spend_registration = $temp['points_spend_registration']*1;
            $this->referrals_before_affiliate = $temp['referrals_before_affiliate'];
            $this->reset_password_token = $temp['reset_password_token'];
            $this->email = $temp['email'];
        }
    }
    
    public function getId(){
        return $this->id;
    }

    public function getAdminChangeAffiliate(){
         $sql = new SQLConnection;
         $amount = 0;
        $temp = $sql->getArray("SELECT SUM(`amount`) AS sum_amount FROM admin_affiliate_user WHERE `player_name`='{$this->playername}'");
        if($temp)
            $amount =  $temp[0]['sum_amount'];
        return $amount;
    }

    public function submitChanges(){
        
        $rake_grabbed = $this->getRake();
        $rake_grabbed = $rake_grabbed['date'];
        if(!$rake_grabbed) $rake_grabbed = date("Y-m-d H:i:s");
        
        $sql = new SQLConnection;
        $q = "UPDATE poker_users
        SET 
            balance = {$this->balance}
            ,name = '{$this->playername}'
            ,points = {$this->points}
            ,points_dec = {$this->points_dec}
            ,tournaments_fee = {$this->tournaments_fee}
            ,chips = {$this->chips}
            ,email = '{$this->email}'
            ,referral = '{$this->referral}'
            ,referral_level = {$this->referral_level}
            ,password = '{$this->password}'
            ,realname = '{$this->realname}'
            ,location = '{$this->location}'
            ,gender = {$this->gender}
            ,api_id = {$this->api_id}
            ,registered = '{$this->registered}'
            ,totalrake = '{$this->totalrake}'
            ,rake = {$this->getRakeValue()}
            ,rake_parsed = '$rake_grabbed'
            ,comission = {$this->comission}
            ,level2 = {$this->level2}
            ,level2_comission = {$this->level2_comission}
            ,link2_commission = {$this->link2_commission}
            ,show_realname = {$this->show_realname}
            ,show_email = {$this->show_email}
            ,country_geolocated = '{$this->country_geolocated}'
            ,ssid = '{$this->ssid}'
            
        WHERE id = {$this->id}";
        $sql->query($q);
        
        if (mysqli_error($sql->DBSource)) Logger::addReport("User::submitChanges",Logger::STATUS_ERROR, mysqli_error
            ($sql->DBSource)." $q");
    }
    
    public function resetIncome($val){
        $sql = new SQLConnection;
        $val*=1;
        
        $q = "UPDATE poker_users
        SET income = $val
        WHERE id = {$this->id}";
        $sql->query($q);
        $this->income = $val;
    }
    
    public function payIncome(){
        $diff = $this->income - $this->income_paid;
        $sql = new SQLConnection;
        
        $q = "UPDATE poker_users
            SET income_paid = {$this->income}
            WHERE id = {$this->id}";
        $sql->query($q);
        
        $amount = $this->chipsToPay();
        $res = Poker_Accounts::IncBalance(["Player"=>$this->playername,"Amount"=>$amount]);
        
        if($res['Balance']){
            Poker_Transactions::chipsTransaction(NULL, $this, $amount, TRUE);
            $this->income_paid = $this->income = $res['Balance']*1;
            return $amount;
            
        }
        return FALSE;
    }
    
    public function rakeToPay(){
        return $this->income - $this->income_paid;
    }
    
    public function chipsToPay(){
        return round($this->rakeToPay()*$this->comission*100)/100;
    }
    
    public function chipsPaid(){
          return round($this->income_paid*$this->comission*100)/100;
    }
    
    public function requestFrame(){
        $sql = new SQLConnection();
        FrameRequests::request($this);
    }
    
    public function getRake(){
        return json_decode($this->totalrake,true);
    }
    
    public function getRakeValue(){
        /*$rake = $this->getRake();
        return $rake['rake']*1;*/
        return $this->rake?$this->rake:0;
    }
    
    public function cacheRake($rake){
        $this->totalrake=json_encode(array("rake"=>$rake,"date"=>date("Y-m-d H:i:s")));
        $this->submitChanges();
    }
    
    public function getReferrals(){
        
        $sql = new SQLConnection;
        $temp = $sql->getArray("SELECT * FROM poker_users WHERE referral=".$this->id);
        
        $res = [];
        foreach($temp as $u){
            $res[] = new User($u['id']);
        }
        
        return $res;
        
    }

    public function getAffilateBalance() {
        return $this->getAffilateBalanceDetails('all', 'all');
    }

    /**
     * @param $level : all / link1level1 / link1level2 / link2level2
     * @return int
     */
    public function getAffilateBalanceDetails($details, $feeDetails){
        $sql = new SQLConnection;
        /*
        $temp = $sql->getArray("SELECT * FROM poker_player_rake");
        $rakeUser = [];
        $totalHandRakeReferral = $totalTournamentFee = $totalFreerollFeeReferal = 0;

        $totalBalance = 0;
        if($temp){
            foreach ($temp as $key => $value) {
                $rakeUser[$value['player_name']] = $value['total_rake'];
            }
        }*/

        $refs = $this->getReferrals();
        $totalBalance = 0;
        
        foreach($refs as $u) {
            if ($u->referral_level == 1) {

                $rake = $u->rake_aff;
                $uRake = 0;
                if ($feeDetails == 'all' || $feeDetails == 'rake') $uRake += $rake;
                if ($feeDetails == 'all' || $feeDetails == 'tournament') $uRake += $u->tournaments_fee_aff;
                if ($feeDetails == 'all') $uRake -= $u->freeroll_fee_aff; // Substract
                else if ($feeDetails == 'freeroll') $uRake += $u->freeroll_fee_aff;

                if ($feeDetails == 'all') $uRake = $uRake * $this->comission;
                if ($details == 'all' || $details == 'link1level1') $totalBalance = $totalBalance + $uRake;

                if ($this->level2) {
                    if ($details == 'all' || $details == 'link1level2') {
                        if ($feeDetails == 'all') $totalBalance = $totalBalance + $u->countAffiliatesRakeDetails(2, $feeDetails) * $this->level2_comission;
                        else $totalBalance = $totalBalance + $u->countAffiliatesRakeDetails(2, $feeDetails);
                    }
                }
            }
            else {

                // if($this->level2){
                /* foreach ($refs as $u) {
                     if ($u->referral_level == 1) {
                         continue;
                     }*/
                if ($details == 'all' || $details == 'link2level2') {
                   // foreach ($u->getReferrals() as $u2) {
                        if ($feeDetails == 'all') $totalBalance = $totalBalance + $u->countAffiliatesRakeDetails(2, $feeDetails) * $this->link2_commission;
                        else $totalBalance = $totalBalance + $u->countAffiliatesRakeDetails(2, $feeDetails);
                  //  }
                    //}
                    //}
                }
            }
        }

        if ($details != 'all') return $totalBalance;

        $transferHistorySql =  $sql->getArray("SELECT * FROM poker_users_transfer WHERE `playername`= '$this->playername' AND `status` < 2"); // Deduct pending and accepted => once refused, we put it back
        $transerHistoryBalance = 0;
        if($transferHistorySql){
            foreach ($transferHistorySql as $key => $value) {
                $transerHistoryBalance += $value['amount'];
            }
        }

        return $totalBalance - $transerHistoryBalance;
    }
    
    
    public function getReferralsArray(){
        $sql = new SQLConnection;
        $temp = $sql->getArray("SELECT pu.*, r.total_rake FROM poker_users pu LEFT JOIN poker_player_rake r ON pu.name = r.player_name WHERE referral=".$this->id);
        
        $res = [];
        foreach($temp as $i=>$v){
            /*
            $rake = json_decode($v['totalrake'],true);
            $rake = $rake['rake'];*/
            
            $res[] = array(
                    "realname"=>$v['realname'] 
                    ,"playername"=>($v['referral_level']*1 ==2 ?"[L2] " : "").$v['name']
                    ,"email"=>$v['email']
                    ,"comission"=>$v['comission']*1
                    ,"level2_comission"=>$v["level2_comission"]*1
                    ,"earned"=>$v['comission']*$v['total_rake']/100
                    ,"referral_level"=>$v['referral_level']*1
                    ,"rake"=>$v['total_rake']
                    ,"tournaments_fee"=>$v['tournaments_fee']
                    ,"registered"=>$v['registered']
                    ,"id"=>$v['id']
                );
        }
        
        return $res;
    }
    
    public function allowedLevel2(){
        
        return $this->level2 ? true : false;
    }

    public function countAffiliatesRake($level) {
        return $this->countAffiliatesRakeDetails($level, 'all');
    }

    /**
     * @param $details : all/rake/tournament/freeroll
     * @return int
     */
    public function countAffiliatesRakeDetails($level, $details){
        $refs = $this->getReferrals();
        $sum=0;
        foreach($refs as $u){
           // if(!$this->level2){
             //   if($u->referral_level == 1){
                    if ($details == 'all' || $details == 'rake' ) {
                        if ($level == 1) $sum+=$u->rake_aff;
                        else $sum+=$u->rake_aff2;
                    }
               // }else{
                   // $sum+=$u->countAffiliatesRakeDetails($details);
               // }
           /*
            }else{
                    $sum+=$u->countAffiliatesRakeDetails($details);
                    if ($details == 'all' || $details == 'rake' ) $sum+=$u->rake;
            }*/
            if ($details == 'all' || $details == 'tournament' ) {
                if ($level == 1) $sum+=$u->tournaments_fee_aff;
                else $sum+=$u->tournaments_fee_aff2;
            }
            if ($details == 'all') {
                if ($level == 1) $sum-=$u->freeroll_fee_aff;
                else $sum-=$u->freeroll_fee_aff2;
            }
            else if  ($details == 'freeroll' ) {
                if ($level == 1) $sum+=$u->freeroll_fee_aff;
                else $sum+=$u->freeroll_fee_aff2;
            }
        }
        
        
        if ($details == 'all') $this->resetIncome($sum);
        return $sum;
        
    }

    public function getRealReferrals(){
        $sql = new SQLConnection;
        $temp = $sql->getArray("SELECT * FROM poker_users WHERE referral=".$this->id." AND registered<DATE_SUB(NOW(), INTERVAL ".Poker_Variables::get("referral_mintime")." DAY)");
        
        $res = [];
        foreach($temp as $u){
            $res[] = new User($u['id']);
        }
        
        return $res;
    }
    
    public function getRealReferralsCount()
    {
        $sql = new SQLConnection;
        $temp = $sql->getArray("SELECT COUNT(*) FROM poker_users WHERE referral=" . $this->id . " AND registered<DATE_SUB(NOW(), INTERVAL " . Poker_Variables::get("referral_mintime") . " DAY)");
        return $temp[0][0];
    }
    
    public function getRealAffiliateReferralsCount(){
        $sql = new SQLConnection;
        $temp = $sql->getArray("SELECT COUNT(*) FROM poker_users pu LEFT JOIN poker_player_rake r ON pu.name = r.player_name WHERE referral=".$this->id." AND IFNULL(total_rake, 0) + tournaments_fee >=".Poker_Variables::get("invitations_affiliate_rake"));
        return $temp[0][0];
    }
    
    public function getPointBalance(){
        return $this->points;// - $this->points_dec);
    }

    public function getAdminPointsTransfer() {
        $sql = new SQLConnection;
        return $sql->getArray("SELECT IFNULL(SUM(amount), 0) FROM admin_points_transfer WHERE player_name = '".$this->playername."'")[0][0];
    }
    
    public function isAffiliate(){
        if(AffiliateRequests::hasRequest($this)){
            $request = AffiliateRequests::getUserRequest($this);
            if ($request['status']==AffiliateRequests::STATUS_ACCEPTED){
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getAffiliateStartDate() {
        $sql = new SQLConnection;
        return $sql->getArray("SELECT answered FROM poker_affiliate_requests par WHERE par.user = '".$this->playername."'")[0][0];
    }

    public function getParentAffiliate() {
        return new User($this->referral);
    }

    public function getOwnerAffiliateName(){
        
        if(!$this->referral){
            return "[None]";
        }
        $ref = new User($this->referral);
        if($this->rereferral_level==2){
            return $ref->playername." [L2]";    
        }
        return $ref->playername;
    }

    public function getReferralPoints($countOnly = false) {
        $pointsperref = Poker_Variables::get("points_invitation")*1;
        $rakeforref = Poker_Variables::get("points_invitation_rake")*1;

        if ($countOnly) $pointsperref = 1;

        $q = "SELECT COUNT(*)*$pointsperref AS points FROM
            poker_users pu LEFT JOIN poker_player_rake r ON pu.name = r.player_name
            WHERE referral = {$this->id}
            AND IFNULL(total_rake, 0) + tournaments_fee >= $rakeforref";
        $sql = new SQLConnection;
        return $sql->getArray($q)[0][0];
    }

    public function upateResetPasswordToken($token) {
        $query = "UPDATE poker_users set reset_password_token = '{$token}' where id = {$this->id}";
        $sql = new SQLConnection();
        return $sql->query($query);
    }
}