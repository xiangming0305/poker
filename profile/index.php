<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/profile/auth.php";
    $user = UserMachine::getCurrentUser();

    $cookie = "918462935623654682";

    if ($_COOKIE['adssid']==$cookie && isset($_GET['player']) && !empty($_GET['player'])){
       $user = UserMachine::getUserByPlayerName(Core::escape($_GET['player']));
    }

    $refs = $user->getReferrals();
    $sql = new SQLConnection;
    $temp = $sql->getArray("SELECT * FROM poker_player_rake");
    $rakeUser = [];
    $totalHandRakeReferral = $totalTournamentFee = $totalFreerollFeeReferal = 0;
    if($temp){
        foreach ($temp as $key => $value) {
            $rakeUser[$value['player_name']] = $value['total_rake'];
        }
    }

    foreach($refs as $u){
        if($u->referral_level != 1){
            continue;
        }

        $uRake = (isset($rakeUser[$u->playername])) ? $rakeUser[$u->playername] : 0;
        $totalHandRakeReferral += $uRake;
        $totalTournamentFee +=$u->tournaments_fee;
        $totalFreerollFeeReferal +=$u->points_dec;
    }

?>

<!doctype html>
<html>
    <head>
        <?php require_once $_SERVER['DOCUMENT_ROOT']."/views/head.php";?>
        
        <title>My Profile</title>
        <link rel='stylesheet' href='/css/profile.css' />
        <script src='/js/profile.js'></script>
    </head>
    
    <body>
        <?php require_once $_SERVER['DOCUMENT_ROOT']."/views/header.php";?>
        <main>
            <div class='hello'>
                <h2>Hello, <?php echo ($_COOKIE['adssid'] ==$cookie  && isset($_GET['player']) && !empty($_GET['player'])) ? 'Administrator you are view profile of '.$user->playername : $user->playername ?>!</h2>
                <p>We are glad to meet you at our poker community, where you can easily play and earn real money even without playing! Know more about our affiliate program.</p>
            </div>
            <a href='/profile/play' class='button'> Play game</a>
            
            <div class='wrapper'>
                <div class='column'>

                    <fieldset class='change-password-fieldset'>
                        <hgroup>
                            <h3>Account</h3>
                            <p>Here you can change your password : <input type="button" id="changePassword_button" class="button" value="Change password" /></p>
                        </hgroup>
                    </fieldset>
                    
                    
                    <fieldset class='transfer-chips'>
                        <hgroup>
                            <h3>Transfer chips</h3>
                            <p>Here you can select any player and transfer some amount of your chips to him.</p>
                        </hgroup>
                        
                        <form id='transferChips'>
                            <label>
                                <span>Player:</span>
                                <input type='text' id='transfer_player' list='players' required/>
                                <datalist id='players'>
                                    <template id='player-option'>
                                        <option value='{{name}}'>{{name}}</option>
                                    </template>
                                </datalist>
                            </label>
                            
                            <label>
                                <span>Chips to be transferred:</span>
                                <input type='number' min='1' max='<?php echo $user->balance?>' id='transfer_amount'/>
                                <span>of <b class='accBalance'><?php echo $user->balance?></b></span>
                            </label>
                            
                            <input type='submit' class='button' value='Send' />
                            <p id='transfer-status' class='status'></p>
                        </form>
                    </fieldset>
                    
                    <fieldset class='become-affiliate'>
                        <hgroup>
                            <h2>Terms to become affiliate</h2>
                            <p>Here you can find terms how to become partner of our system and earn money instead of points!</p>
                            <p>When you become affiliate you will not earn from your referral rake but from your own rakes only</p>
                        </hgroup>
                        
                        <div>
                            
                            <article>
                                <hgroup>
                                    <h3>Progress:</h3>
                                    <p>To become able to send partner request, you need to meet following conditions:</p>
                                </hgroup>
                                
                                <div>
                                    <p>You need to have <span class='number'><?php echo Poker_Variables::get("invitations_affiliate")?></span> real referrals with summary rake not less than <span class='number'><?php echo Poker_Variables::get("invitations_affiliate_rake")?></span>.</p>
                                    <p>You earn <span class='number'><?php echo Poker_Variables::get("points_invitation")?></span> points for every real referral, who has summary rake not less than <span class='number'><?php echo Poker_Variables::get("points_invitation_rake")?></span></p>
                                </div>
                                
                                <div>
                                    <p>Your total rake: <span class='number'><?php echo $user->rake?> (<?php echo  round($user->rake / Poker_Variables::get("points_formula_rake"), 2)?> points)</span></p>
                                    <p>Your total tournament fees: <span class='number'><?php echo $user->tournaments_fee?> (<?php echo round($user->tournaments_fee / Poker_Variables::get("points_formula_rake"), 2)?> points)</span></p>
                                    <p>Referrals with rake+tournament fee more than <?php echo Poker_Variables::get("points_invitation_rake")?>: <span class='number'><?php echo $user->referrals_before_affiliate?> (<?php echo $user->referrals_before_affiliate * Poker_Variables::get("points_invitation")?> points)</span></p>
                                    <p>Your points spent on tournaments registration : <span class='number'><?php echo -$user->points_spend_registration?></span></p>
                                    <?php
                                    $adminPoints = $user->getAdminPointsTransfer();
                                    if ($adminPoints <> 0) echo "<p>Points transferred by administrator : <span class='number'>".$adminPoints."</span></p>";
                                    ?>
                                    <p>Your points: <span class='number'><?php echo round($user->getPointBalance(), 2)?></span></p>
                                    <br/>
                                    <p>Your chips balance: <span class='number accBalance'><?php echo $user->balance?></span></p>
                                </div>
                                
                                <div>
                                    <?php
                                       
                                        //if(UserMachine::isAbleToAffiliate($user)){
                                            if(!AffiliateRequests::hasRequest($user)){
                                                echo "<p>
                                                    <input type='button' class='button' id='affiliateRequest_button' value='Request to become affiliate' />
                                                </p>
                                                <p class='affiliate status'></p>
                                                ";
                                            }else{
                                                $request = AffiliateRequests::getUserRequest($user);
                                                switch($request['status']){
                                                    case AffiliateRequests::STATUS_WAITING:{
                                                        echo "<p class='affiliateRequestStatus'>Your request is waiting to be proceed.</p>";
                                                        break;
                                                    }
                                                    case AffiliateRequests::STATUS_ACCEPTED:{
                                                        echo "<p class='affiliateRequestStatus'><span class='accepted'>You are an affiliate now (since ".$request['answered'].")!</span></p>";
                                                        break;
                                                    }
                                                    case AffiliateRequests::STATUS_ACCEPTED_ONCE_ENABLE:{
                                                        echo "<p class='affiliateRequestStatus'><span class='accepted'>Your request is accepted : once you have met the criteria you will automatically become an affiliate!</span></p>";
                                                        break;
                                                    }
                                                    case AffiliateRequests::STATUS_DECLINED:{
                                                        echo "<p class='affiliateRequestStatus'><span class='declined'>Sorry, but your request was declined!</span></p>";
                                                        break;
                                                    }
                                                }
                                                
                                        //    }
                                        }
                                    ?>
                                </div>
                            </article>
                        </div>
                    </fieldset>

                    <fieldset class='show-process'>
                        <hgroup>
                            <h2>Show process</h2>
                        </hgroup>
                        
                        <div>     
                            <article>
                                
                                <div>
                                    <p>Total hand rake for referral players: <span class='number'><?php echo $user->getAffilateBalanceDetails('link1level1', 'rake') +$user->getAffilateBalanceDetails('link1level2', 'rake') + $user->getAffilateBalanceDetails('link2level2', 'rake') ?></span></p>

                                    <p>Total tournament fee for referral players: <span class='number'><?php echo $user->getAffilateBalanceDetails('link1level1', 'tournament') + $user->getAffilateBalanceDetails('link1level2', 'tournament') + $user->getAffilateBalanceDetails('link2level2', 'tournament') ?></span></p>
                                    <p>Total freeroll fee for referral players: <span class='number'><?php echo  $user->getAffilateBalanceDetails('link1level1', 'freeroll') + $user->getAffilateBalanceDetails('link1level2', 'freeroll') + $user->getAffilateBalanceDetails('link2level2', 'freeroll') ?></span></p>
                                    <br/>
                                    <b style="font-weight:bold">Link 1 Level 1</b>
                                    <p>Total hand rake : <span class='number'><?php echo  $user->getAffilateBalanceDetails('link1level1', 'rake') ?></span></p>
                                    <p>Total tournament fee : <span class='number'><?php echo  $user->getAffilateBalanceDetails('link1level1', 'tournament') ?></span></p>
                                    <p>Total freeroll fee : <span class='number'><?php echo  $user->getAffilateBalanceDetails('link1level1', 'freeroll') ?></span></p>
                                    <p>Commission : <span class='number'><?php echo  $user->comission ?></span></p>
                                    <p>Balance 1 : <span class='number'><?php echo  $user->getAffilateBalanceDetails('link1level1', 'all') ?></span></p>
                                    <?php if ($user->level2) {?>
                                        <br/>
                                        <b style="font-weight:bold">Link 1 Level 2</b>
                                        <p>Total hand rake : <span class='number'><?php echo  $user->getAffilateBalanceDetails('link1level2', 'rake') ?></span></p>
                                        <p>Total tournament fee : <span class='number'><?php echo  $user->getAffilateBalanceDetails('link1level2', 'tournament') ?></span></p>
                                        <p>Total freeroll fee : <span class='number'><?php echo  $user->getAffilateBalanceDetails('link1level2', 'freeroll') ?></span></p>
                                        <p>Commission : <span class='number'><?php echo  $user->level2_comission ?></span></p>
                                        <p>Balance 1 : <span class='number'><?php echo  $user->getAffilateBalanceDetails('link1level2', 'all') ?></span></p>
                                    <?php } ?>
                                    <br/>
                                    <b style="font-weight:bold">Link 2 Level 2</b>
                                    <p>Total hand rake : <span class='number'><?php echo  $user->getAffilateBalanceDetails('link2level2', 'rake') ?></span></p>
                                    <p>Total tournament fee : <span class='number'><?php echo  $user->getAffilateBalanceDetails('link2level2', 'tournament') ?></span></p>
                                    <p>Total freeroll fee : <span class='number'><?php echo  $user->getAffilateBalanceDetails('link2level2', 'freeroll') ?></span></p>
                                    <p>Commission : <span class='number'><?php echo  $user->link2_commission ?></span></p>
                                    <p>Balance 1 : <span class='number'><?php echo  $user->getAffilateBalanceDetails('link2level2', 'all') ?></span></p>
                                    <br/>
                                    <p>Your commission rate: <span class='number accBalance'><?php echo $user->comission?></span></p>
                                    <p>Your affiliate balance: <span class='number'><?php echo  $user->getAffilateBalance() + $user->getAdminChangeAffiliate()?></span></p>
                                    <a href="#" onClick="openTransferHistory()" style="color: rgb(183, 8,25);" class="view-history-transfer">View transfer history</a>
                                </div>
                            </article>
                        </div>
                        <br/><br/>
                        <hgroup>
                            <h3>Transer Request</h3>
                        </hgroup>
                        <form id="transferAffiliate">
                            <label>
                                <span>Amount:</span>
                                <input type='number' min='1' max='<?php echo  $user->getAffilateBalance() + $user->getAdminChangeAffiliate()?>' id="affiliate_amount" list="players" required="">
                            </label>
                            <input type="submit" class="button" value="Send">
                            <p id='affiliate-transfer-status' class='status'></p>
                        </form>
                    </fieldset>

                </div>
                
                <div class='column'>

                    <fieldset>
                        <hgroup>
                            <h3>Request Private Tournament</h3>
                            <p>Here you can request your own private tournament</p>
                        </hgroup>

                        <input class="button" id="request_tournament_button" value="Request private tournament" type="button">

                        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/profile/request_tournament_form.php'; ?>

                    </fieldset>


                    <fieldset class='referrals'>
                        <hgroup>
                            <h3>Your referrals</h3>
                            <p>Here are displayed all players that have registered by your invite and bring you money while playing. Total rake count is cached for 2 minutes, so if you are not sure about the accuracy of this field, wait for2 minutes and refresh the page.</p>
                        </hgroup>
                        
                        <div>
                            <table>
                                <thead>
                                    <td>Player name</td>
                                    <?php if ($user->show_email) echo '<td>E-mail</td>'; ?>
                                    <?php if ($user->show_realname) echo '<td>Real name</td>'; ?>
                                    <td>Rake</td>
                                    <td>Tournament fees</td>
                                    <td>Freeroll fees</td>
                                    <td></td>
                                    <td></td>
                                </thead>
                                
                                <tbody class='refs'>
                                    <?php
                                        // $refs = $user->getReferrals();
                                        // $sum = 0;
                                        // $sql = new SQLConnection;
                                        // $temp = $sql->getArray("SELECT * FROM poker_player_rake");
                                        // $rakeUser = [];
                                        // if($temp){
                                        //     foreach ($temp as $key => $value) {
                                        //         $rakeUser[$value['player_name']] = $value['total_rake'];
                                        //     }
                                        // }
                                        foreach($refs as $u){
                                            if($u->referral_level != 1){
                                                continue;
                                            }
                                            echo "<tr data-id='{$u->getId()}'>
                                                <td>{$u->playername}
                                                    
                                                </td>";
                                                if ($user->show_email) echo "<td>{$u->email}</td>";
                                                if ($user->show_realname) echo "<td>{$u->realname}</td>";
                                                $uRake = (isset($rakeUser[$u->playername])) ? $rakeUser[$u->playername] : '0';

                                                if(!$user->level2){
                                                    if($u->referral_level == 1){
                                                        echo "<td class='income'><span class='result'>".$uRake."</span></td>";
                                                        echo "<td class='income'><span class='result'>".$u->tournaments_fee."</span></td>";
                                                        echo "<td class='income'><span class='result'>".$u->points_dec."</span>";
                                                        //$sum+=$uRake;
                                                    }
                                                 }else{
                                                    echo "<td class='income'><span class='result'>".$uRake."</span></td>";
                                                    echo "<td class='income'><span class='result'>".$u->tournaments_fee."</span></td>";
                                                    echo "<td class='income'><span class='result'>".$u->points_dec."</span>";
                                                }
                                                echo "<td class='details'><a href='#' data-id='{$u->getId()}'>Details</a></td>";
                                                if ($user->level2) echo "<td class='refs'><a href='#' data-id='{$u->getId()}' class='referralsLink'>Referrals</a></td>";
                                                else echo "<td class='refs'></td>";
                                                echo"
                                            </tr>";
                                        }
                                    ?>
                                    
                                </tbody>
                                <?php
                                 if($user->level2){
                                    echo "
                                        <tr>
                                            <td colspan='5'>
                                                <h3>Link 2 Level 2 affiliates</h3>
                                            </td>
                                        </tr>
                                    "; 
                                     
                                 }?>
                                
                               
                                
                                <tbody class='refs'>
                                    <?php
                                        // $refs = $user->getReferrals();
                                       
                                       // if($user->level2){
                                            foreach($refs as $u){
                                                if($u->referral_level == 1){
                                                    continue;
                                                }
                                                echo "<tr data-id='{$u->getId()}'>
                                                    <td>{$u->playername}
                                                        
                                                    </td>";
                                                    if ($user->show_email) echo "<td>{$u->email}</td>";
                                                    if ($user->show_realname) echo "<td>{$u->realname}</td>";

                                                    echo "<td class='income'></td><td class='income'></td><td class='income'></td>";
                                                    // echo "<pre>";var_dump($u);
                                                    // if(!$user->level2){
                                                   //     if($u->referral_level != 1){
                                                    //        echo " <span class='result'>".$u->countAffiliatesRake()." (Level 2)</span>";
                                                   //         $sum+=$u->countAffiliatesRake();
                                                    //    }

                                                    // }else{
                                                    //    echo "<span class='result'>".($u->rake+$u->countAffiliatesRake())." (Level 1+2)</span>";
                                                    //         $sum+=$u->rake+$u->countAffiliatesRake(); 
                                                    // }
                                                    echo "<td class='details'><a href='#' data-id='{$u->getId()}'>Details</a></td>";
                                                    echo "<td class='refs'><a href='#' data-id='{$u->getId()}' class='referralsLink'>Referrals</a></td>";
                                                    echo"
                                                </tr>";
                                            }
                                        
                                       // }
                                    ?>
                                    <!--tfoot>
                                        <td colspan='3'>Total (rake + fee)</td>
                                        <td id='totalrake'><?php echo $sum." (".$user->chipsToPay()." chips)"?></td>
                                    </tfoot-->
                                </tbody>
                            </table>
                            
                            <div id='details'>
                                <div class='wrap'>
                                    <hgroup>
                                        <h3>Referrals of player <span class='name'></span></h3>
                                        <p>Here you can see referral users of your referrals.</p>
                                    </hgroup>
                                    <div class='container'>
                                        
                                    </div>
                                </div>
                            </div>

                            <div id='tournamentResultView' class='affiliatePopup'>
                                <div class='wrap'>
                                    <div class='container'></div>
                                </div>
                            </div>

                             <div id='referral-level2'>
                                <div class='wrap'>
                                    <hgroup>
                                        <h3>Referrals of player <span class='name'></span></h3>
                                        <p>Here you can see referral users of your referrals.</p>
                                    </hgroup>
                                    <div class='container'>
                                        
                                    </div>
                                </div>
                            </div>

                            <div id='transfer-history'>
                                <div class='wrap'>
                                    <hgroup>
                                        <h3>Your transfer history</h3>
                                        
                                    </hgroup>
                                    <div class='container'>
                                        
                                    </div>
                                </div>
                            </div>

                            <div id='hand-history'>
                                <div class='wrap'>
                                    <hgroup>
                                        <h3>Hand history <span class='name'></span></h3>
                                        <p></p>
                                    </hgroup>
                                    <div class='container'>
                                        
                                    </div>
                                </div>
                            </div>

                            <div id='changePassword'>
                                <div class='wrap'>
                                    <hgroup>
                                        <h3>Change your password</h3>
                                        <p></p>
                                    </hgroup>
                                    <div class='container'>
                                        <form id='change_password_form' method='POST'>
                                            <fieldset>
                                                <label>
                                                    <span>New password</span>
                                                    <input type='password' name='password' id='change_password-password' required/>
                                                </label>
                                                <label>
                                                    <span>Confirm password</span>
                                                    <input type='password' required name='confirmpassword' id='change_password-confirmpassword' required/>
                                                </label>

                                                <input type='submit' id='changePasswordSubmit' class="button" value='Change password' />
                                                <input type='button' id='closeForm' class="button" value='Cancel' style="float: right; margin-right: 50%; background-color: #ccc;color: #000;" />
                                                <p class='status'></p>
                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div id='affiliateRequest'>
                                <div class='wrap'>
                                    <hgroup>
                                        <h3>Become an affiliate</h3>

                                    </hgroup>
                                    <div class="container">
                                        <form id='affiliateRequest_form' method='post'>
                                            <fieldset class='cols'>
                                                <div>

                                                    <label>
                                                        <span>Company</span>
                                                        <input type='text' required name='company'/>
                                                    </label>

                                                    <label>
                                                        <span>Position</span>
                                                        <input type='text' required name='position' />
                                                    </label>

                                                    <label>
                                                        <span>Country</span>
                                                        <select required name="country">
                                                            <option></option>
                                                            <option>Afghanistan</option>
                                                            <option>Albania</option>
                                                            <option>Algeria</option>
                                                            <option>American Samoa</option>
                                                            <option>Andorra</option>
                                                            <option>Angola</option>
                                                            <option>Anguilla</option>
                                                            <option>Antarctica</option>
                                                            <option>Antigua and Barbuda</option>
                                                            <option>Argentina</option>
                                                            <option>Armenia</option>
                                                            <option>Aruba</option>
                                                            <option>Australia</option>
                                                            <option>Austria</option>
                                                            <option>Azerbaijan</option>
                                                            <option>Bahamas</option>
                                                            <option>Bahrain</option>
                                                            <option>Bangladesh</option>
                                                            <option>Barbados</option>
                                                            <option>Belarus</option>
                                                            <option>Belgium</option>
                                                            <option>Belize</option>
                                                            <option>Benin</option>
                                                            <option>Bermuda</option>
                                                            <option>Bhutan</option>
                                                            <option>Bolivia, Plurinational State of</option>
                                                            <option>Bonaire, Sint Eustatius and Saba</option>
                                                            <option>Bosnia and Herzegovina</option>
                                                            <option>Botswana</option>
                                                            <option>Bouvet Island</option>
                                                            <option>Brazil</option>
                                                            <option>British Indian Ocean Territory</option>
                                                            <option>Brunei Darussalam</option>
                                                            <option>Bulgaria</option>
                                                            <option>Burkina Faso</option>
                                                            <option>Burundi</option>
                                                            <option>Cambodia</option>
                                                            <option>Cameroon</option>
                                                            <option>Canada</option>
                                                            <option>Cape Verde</option>
                                                            <option>Cayman Islands</option>
                                                            <option>Central African Republic</option>
                                                            <option>Chad</option>
                                                            <option>Chile</option>
                                                            <option>China</option>
                                                            <option>Christmas Island</option>
                                                            <option>Cocos (Keeling) Islands</option>
                                                            <option>Colombia</option>
                                                            <option>Comoros</option>
                                                            <option>Congo</option>
                                                            <option>Congo, the Democratic Republic of the</option>
                                                            <option>Cook Islands</option>
                                                            <option>Costa Rica</option>
                                                            <option>Côte d'Ivoire</option>
                                                            <option>Croatia</option>
                                                            <option>Cuba</option>
                                                            <option>Curaçao</option>
                                                            <option>Cyprus</option>
                                                            <option>Czech Republic</option>
                                                            <option>Denmark</option>
                                                            <option>Djibouti</option>
                                                            <option>Dominica</option>
                                                            <option>Dominican Republic</option>
                                                            <option>Ecuador</option>
                                                            <option>Egypt</option>
                                                            <option>El Salvador</option>
                                                            <option>Equatorial Guinea</option>
                                                            <option>Eritrea</option>
                                                            <option>Estonia</option>
                                                            <option>Ethiopia</option>
                                                            <option>Falkland Islands (Malvinas)</option>
                                                            <option>Faroe Islands</option>
                                                            <option>Fiji</option>
                                                            <option>Finland</option>
                                                            <option>France</option>
                                                            <option>French Guiana</option>
                                                            <option>French Polynesia</option>
                                                            <option>French Southern Territories</option>
                                                            <option>Gabon</option>
                                                            <option>Gambia</option>
                                                            <option>Georgia</option>
                                                            <option>Germany</option>
                                                            <option>Ghana</option>
                                                            <option>Gibraltar</option>
                                                            <option>Greece</option>
                                                            <option>Greenland</option>
                                                            <option>Grenada</option>
                                                            <option>Guadeloupe</option>
                                                            <option>Guam</option>
                                                            <option>Guatemala</option>
                                                            <option>Guernsey</option>
                                                            <option>Guinea</option>
                                                            <option>Guinea-Bissau</option>
                                                            <option>Guyana</option>
                                                            <option>Haiti</option>
                                                            <option>Heard Island and McDonald Islands</option>
                                                            <option>Holy See (Vatican City State)</option>
                                                            <option>Honduras</option>
                                                            <option>Hong Kong</option>
                                                            <option>Hungary</option>
                                                            <option>Iceland</option>
                                                            <option>India</option>
                                                            <option>Indonesia</option>
                                                            <option>Iran, Islamic Republic of</option>
                                                            <option>Iraq</option>
                                                            <option>Ireland</option>
                                                            <option>Isle of Man</option>
                                                            <option>Israel</option>
                                                            <option>Italy</option>
                                                            <option>Jamaica</option>
                                                            <option>Japan</option>
                                                            <option>Jersey</option>
                                                            <option>Jordan</option>
                                                            <option>Kazakhstan</option>
                                                            <option>Kenya</option>
                                                            <option>Kiribati</option>
                                                            <option>Korea, Democratic People's Republic of</option>
                                                            <option>Korea, Republic of</option>
                                                            <option>Kuwait</option>
                                                            <option>Kyrgyzstan</option>
                                                            <option>Lao People's Democratic Republic</option>
                                                            <option>Latvia</option>
                                                            <option>Lebanon</option>
                                                            <option>Lesotho</option>
                                                            <option>Liberia</option>
                                                            <option>Libya</option>
                                                            <option>Liechtenstein</option>
                                                            <option>Lithuania</option>
                                                            <option>Luxembourg</option>
                                                            <option>Macao</option>
                                                            <option>Macedonia, the former Yugoslav Republic of</option>
                                                            <option>Madagascar</option>
                                                            <option>Malawi</option>
                                                            <option>Malaysia</option>
                                                            <option>Maldives</option>
                                                            <option>Mali</option>
                                                            <option>Malta</option>
                                                            <option>Marshall Islands</option>
                                                            <option>Martinique</option>
                                                            <option>Mauritania</option>
                                                            <option>Mauritius</option>
                                                            <option>Mayotte</option>
                                                            <option>Mexico</option>
                                                            <option>Micronesia, Federated States of</option>
                                                            <option>Moldova, Republic of</option>
                                                            <option>Monaco</option>
                                                            <option>Mongolia</option>
                                                            <option>Montenegro</option>
                                                            <option>Montserrat</option>
                                                            <option>Morocco</option>
                                                            <option>Mozambique</option>
                                                            <option>Myanmar</option>
                                                            <option>Namibia</option>
                                                            <option>Nauru</option>
                                                            <option>Nepal</option>
                                                            <option>Netherlands</option>
                                                            <option>New Caledonia</option>
                                                            <option>New Zealand</option>
                                                            <option>Nicaragua</option>
                                                            <option>Niger</option>
                                                            <option>Nigeria</option>
                                                            <option>Niue</option>
                                                            <option>Norfolk Island</option>
                                                            <option>Northern Mariana Islands</option>
                                                            <option>Norway</option>
                                                            <option>Oman</option>
                                                            <option>Pakistan</option>
                                                            <option>Palau</option>
                                                            <option>Palestinian Territory, Occupied</option>
                                                            <option>Panama</option>
                                                            <option>Papua New Guinea</option>
                                                            <option>Paraguay</option>
                                                            <option>Peru</option>
                                                            <option>Philippines</option>
                                                            <option>Pitcairn</option>
                                                            <option>Poland</option>
                                                            <option>Portugal</option>
                                                            <option>Puerto Rico</option>
                                                            <option>Qatar</option>
                                                            <option>Réunion</option>
                                                            <option>Romania</option>
                                                            <option>Russian Federation</option>
                                                            <option>Rwanda</option>
                                                            <option>Saint Barthélemy</option>
                                                            <option>Saint Helena, Ascension and Tristan da Cunha</option>
                                                            <option>Saint Kitts and Nevis</option>
                                                            <option>Saint Lucia</option>
                                                            <option>Saint Martin (French part)</option>
                                                            <option>Saint Pierre and Miquelon</option>
                                                            <option>Saint Vincent and the Grenadines</option>
                                                            <option>Samoa</option>
                                                            <option>San Marino</option>
                                                            <option>Sao Tome and Principe</option>
                                                            <option>Saudi Arabia</option>
                                                            <option>Senegal</option>
                                                            <option>Serbia</option>
                                                            <option>Seychelles</option>
                                                            <option>Sierra Leone</option>
                                                            <option>Singapore</option>
                                                            <option>Sint Maarten (Dutch part)</option>
                                                            <option>Slovakia</option>
                                                            <option>Slovenia</option>
                                                            <option>Solomon Islands</option>
                                                            <option>Somalia</option>
                                                            <option>South Africa</option>
                                                            <option>South Georgia and the South Sandwich Islands</option>
                                                            <option>South Sudan</option>
                                                            <option>Spain</option>
                                                            <option>Sri Lanka</option>
                                                            <option>Sudan</option>
                                                            <option>Suriname</option>
                                                            <option>Svalbard and Jan Mayen</option>
                                                            <option>Swaziland</option>
                                                            <option>Sweden</option>
                                                            <option>Switzerland</option>
                                                            <option>Syrian Arab Republic</option>
                                                            <option>Taiwan, Province of China</option>
                                                            <option>Tajikistan</option>
                                                            <option>Tanzania, United Republic of</option>
                                                            <option>Thailand</option>
                                                            <option>Timor-Leste</option>
                                                            <option>Togo</option>
                                                            <option>Tokelau</option>
                                                            <option>Tonga</option>
                                                            <option>Trinidad and Tobago</option>
                                                            <option>Tunisia</option>
                                                            <option>Turkey</option>
                                                            <option>Turkmenistan</option>
                                                            <option>Turks and Caicos Islands</option>
                                                            <option>Tuvalu</option>
                                                            <option>Uganda</option>
                                                            <option>Ukraine</option>
                                                            <option>United Arab Emirates</option>
                                                            <option>United Kingdom</option>
                                                            <option>United States</option>
                                                            <option>United States Minor Outlying Islands</option>
                                                            <option>Uruguay</option>
                                                            <option>Uzbekistan</option>
                                                            <option>Vanuatu</option>
                                                            <option>Venezuela, Bolivarian Republic of</option>
                                                            <option>Viet Nam</option>
                                                            <option>Virgin Islands, British</option>
                                                            <option>Virgin Islands, U.S.</option>
                                                            <option>Wallis and Futuna</option>
                                                            <option>Western Sahara</option>
                                                            <option>Yemen</option>
                                                            <option>Zambia</option>
                                                            <option>Zimbabwe</option>
                                                        </select>
                                                    </label>
                                                    <label>
                                                        <span>How did you hear about us?</span>
                                                        <select required name='hear_about_us'>
                                                            <option></option>
                                                            <option>From a friend</option>
                                                            <option>From a Search engine</option>
                                                            <option>From a social network</option>
                                                            <option>From an online ad</option>
                                                            <option>Other</option>
                                                        </select>
                                                    </label>

                                                    <label>
                                                        <span>How you will bring players?</span>
                                                        <textarea required name='how_bring_players' rows="10"></textarea>
                                                    </label>

                                                    <label>
                                                        <span>How many players can you bring?</span>
                                                        <input type='number' min="0" required name='how_many_players' />
                                                    </label>
                                                </div>
                                            </fieldset>

                                                <p class='buttons'>
                                                    <input type='submit' class="button" value='Submit' />
                                                </p>
                                                <p class='status'></p>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </fieldset>
				   <?php
					$sql = new SQLConnection;
					$currentUser = UserMachine::getCurrentUser();
					if ($currentUser == null) {
						echo "<fieldset class='history'>
						<a class='cashin deposit' id='historyLink' href='#history' data-name='$user->playername'>History</a>
						</fieldset>";
					}
					?>
					
                </div>
            </div>
            
        </main>
        <?php require_once $_SERVER['DOCUMENT_ROOT']."/views/footer.php";?>
    </body>
</html>