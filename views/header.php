<?php
$enableCashout = Poker_Variables::get('enable_cashout');
$depositConfig = Poker_Variables::getList(['deposit_currency', 'deposit_rate']);
?>
<header>
    <a href='/' class="logo">
        <img src='/img/logo.png'/>
    </a>
    <div class="dd-menu">
        <nav class='menu'>
            <ul>
                <li><a class='game' href='/game'>Game</a></li>
                <?php
                $sql = new SQLConnection;
                $unread_messages = 0;
                $currentUser = UserMachine::getCurrentUser();
                if ($currentUser != null) {
                    $unread_messages = count(Message::getInbox($currentUser->playername, true));
                }
                ?>
                <li>
                    <a class='contacts' href='/profile/contact.php'>
                        Contacts <span class='message-count'><?php echo $unread_messages ? '(' . $unread_messages . ')' : '' ?></span>
                    </a>
                </li>


            </ul>
        </nav>
        <nav class='login'>
            <ul>
                <li><a class='freeroll game' href='#freeroll'>Freeroll</a></li>

                <?php
                /*
                    $temp = $sql->getArray("SELECT * FROM cata_settings_vars WHERE `key`='show_entry_fee'");
                    $check = 0;
                    if($temp){
                        $check = $temp[0]['value'];
                    }*/
                $check = 1;

                if ($currentUser != null) {
                    $user = UserMachine::refreshBalance($currentUser);
                    ?>

                    <li><a href='#partnership' class='partner'>Become partner</a></li>
                    <li><a href='/profile' id='profile'><?= $user->playername ?></a></li>
                    <li class='b'><span class='balance'><?= $user->balance ?></span>
                        <i class='link'><?php echo $enableCashout ? 'Cashier' : 'Chips request' ?></i>
                        <div>
                            <?php
                            if ($enableCashout) { ?>
                                <a class='cashin' id='cashinLink' href='#cashin'>Cash out</a>
                            <?php } ?>
                            <a class='cashin deposit' id='depositLink' href='#deposit'>Deposit</a>
                            <a class='cashin deposit' id='historyLink' href='#history'
                               data-name='<?= $user->playername ?>'>History</a>
                        </div>
                    </li>

                    <li><a href='/auth/logout.php' class='logout'>Exit</a></li>
                <?php } else { ?>
                    <li><a href='#' id='login'>Login</a></li>
                    <li><a href='#' id='register'>Register</a></li>
                <?php } ?>
            </ul>

            <div id='requestDeposit' class='popup'>
                <div class='wrap'>
                    <hgroup>
                        <h3>Your balance: <span class='number' id='balanceAmountDep'><?= $user->balance ?></span> chips
                        </h3>
                        <p>You can request a chips deposit from administrator.
                            1 <?= $depositConfig['deposit_currency']['value'] ?>
                            = <?= $depositConfig['deposit_rate']['value'] ?> chips</p>
                    </hgroup>
                    <div>
                        <label>
                            <input type="hidden" id="depositRate"
                                   value="<?= $depositConfig['deposit_rate']['value'] ?>">
                            <span>Amount of money</span>
                            <input type='number' min='1' max='1000000' id='depositAmount' value=''/>
                        </label>
                        <label>
                            <span>Amount of chips will be received</span>
                            <input type='number' min='1' max='1000000' id='depositChip' value='' readonly/>
                        </label>

                        <label>
                            <span>Payment method</span>
                            <select name="" id="depositPaymentMethod">
                                <option value=""></option>
                                <?php
                                foreach (Poker_PaymentMethod::all('deposit', false) as $paymentMethod) { ?>
                                    <option value="<?= $paymentMethod['id'] ?>"
                                            data-desc="<?= 'Payment method: '. $paymentMethod['name'] . '<br>Description: ' . $paymentMethod['description']
											. '<br>Receiver name: ' . $paymentMethod['payment_name']. '<br>Receiver country: ' . $paymentMethod['country']
											. '<br>Payment address: ' . $paymentMethod['payment_address']
											?>"><?= $paymentMethod['name'] ?></option>
                                <?php } ?>
                            </select>
                            <span class="payment-desc"></span>
                        </label>

                        <label>
                            <span>Payment made from name</span>
                            <input type='text' id='depositPaidFrom' value=''/>
                        </label>

                        <label>
                            <span>Payment made from account</span>
                            <input type='text' id='depositPaidFromAccount' value=''/>
                        </label>

                        <label>
                            <span>Payment made from country</span>
                            <input type='text' id='depositPaidFromCountry' value=''/>
                        </label>

                        <label>
                            <span>Payment transaction ID</span>
                            <input type='text' id='depositTransactionId' value=''/>
                        </label>

                        <input type='button' class='button' value='Request deposit' id='deposit'/>
                        <p id='depositStatus' class='status'></p>
                    </div>
                </div>
            </div>

            <div id='balance' class='popup'>
                <div class='wrap'>
                    <hgroup>
                        <h3>Your balance: <span class='number' id='balanceAmount'><?= $user->balance ?></span> chips
                        </h3>
                        <p>At any time you can request cash out your chips balance to real money.</p>
                    </hgroup>
                    <div>
                        <label>
                            <span>Chips to transfer</span>
                            <input type='number' min='1' max='<?= $user->balance ?>' id='chips'
                                   value='<?= $user->balance ?>'/>
                        </label>
						<label>
                            <span>Payment method</span>
                            <select name="" id="cashOutPaymentMethod">
                                <option value=""></option>
                                <?php
                                foreach (Poker_PaymentMethod::all('withdraw', false) as $paymentMethod) { ?>
								<?php $a= $paymentMethod['description'] . "-" . $paymentMethod['name']; ?>
                                    <option value="<?= $paymentMethod['id'] ?>"
											data-desc="<?= 'Payment method: '. $paymentMethod['name'] . '<br>Description: ' . $paymentMethod['description']
											. '<br>Sender name: ' . $paymentMethod['payment_name']. '<br>Sender country: ' . $paymentMethod['country']
											. '<br>Payment address: ' . $paymentMethod['payment_address']
											?>"
											><?= $paymentMethod['name'] ?></option>
                                <?php } ?>
                            </select>
                            <span class="payment-desc-cash"></span>
                        </label>
						<label>
                            <span>Receiver name</span>
                            <input type='text' id='cashOutBy' value=''/>
                        </label>

                        <label>
                            <span>Receiver account</span>
                            <input type='text' id='cashOutByAccount' value=''/>
                        </label>

                        <label>
                            <span>Receiver country</span>
                            <input type='text' id='cashOutByCountry' value=''/>
                        </label>
                        <input type='button' class='button' value='Request cash out' id='cashin'/>
                        <p id='balanceStatus' class='status'></p>
                    </div>
                </div>
            </div>

            <div class='popup' id='transfersHistory'>
                <div class='wrap'>
                    <hgroup>
                        <h2>Transfers history</h2>

                    </hgroup>
                    <nav>
                        <ul>
                            <li><a href='#' id='incomesTab' class='active'>Incomes</a></li>
                            <li><a href='#' id='outcomesTab'>Outcomes</a></li>
                        </ul>
                    </nav>
                    <div class='spinner_big'></div>
                    <table id='historyIncome'>
                        <thead>
                        <td>Date</td>
                        <td>Amount</td>
                        <td>From</td>
                        </thead>

                        <tbody>
                        <template id='transferTemplate'>
                            <tr>
                                <td>{{date}}</td>
                                <td><span class='number'>{{amount}}</span></td>
                                <td>{{subject}}</td>
                            </tr>
                        </template>
                        </tbody>
                    </table>

                    <table id='historyOutcome'>
                        <thead>
                        <td>Date</td>
                        <td>Amount</td>
                        <td>To</td>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>


            </div>


            <div id='freeroll' class='popup1'>
                <div id='wrap'>
                    <hgroup>
                        <h2>Available tournaments</h2>
                        <p>Here are displayed available tournaments for you to take part in.</p>
                    </hgroup>

                    <pre></pre>


                    <table>
                        <thead>
                        <td>Tournament</td>
                        <td>Free seats</td>
                        <td>Total seats</td>
                        <?php if ($check): ?>
                            <td>Entry fee</td>
                        <?php endif ?>
                        <td>Actions</td>
                        </thead>

                        <tbody id='tournamentsPart'>
                        <template id='tournamentsTemplate'>
                            <tr data-name='{{name}}'>
                                <td>{{name}}</td>
                                <td>{{freeseats}}</td>
                                <td>{{seats}}</td>
                                <?php if ($check): ?>
                                    <td><span class='number'>{{point_fee}}</span></td>
                                <?php endif ?>
                                <td data-accepted='{{accepted}}'>
                                    <p class='register'>
                                        <input type='button' class='button register' value='Register'/>
                                    </p>
                                    <p class='unregister'>
                                        <input type='button' class='button unregister' value='Unregister'/>
                                    </p>
                                    <p class='unauthorized'>
                                        <span class='err'>Log in to take part!</span>
                                    </p>
                                    <p class='nenough'>
                                        <span class='err'>Not enough points to take part!</span>
                                    </p>
                                </td>
                            </tr>
                        </template>

                        </tbody>
                    </table>
                </div>
            </div>
        </nav>
    </div>
</header>

<?php
if ($user) {
    echo "<article class='partnership' id='partnership'>
            <fieldset class='frameRequest'>
                <hgroup>
                    <h3>Game code for your website</h3>
                    <p>Get HTML code to insert on your website to load our prescious game on your website under yur domain. Involve more players and get more money!</p>
                </hgroup>
                
                <div class='text'>";


    $fq = FrameRequests::getUserRequest($user);
    if ($fq == null) {
        echo "<input type='button' id='frameRequest' class='button' value='Request code'>";
    } else {

        switch ($fq['status']) {
            case FrameRequests::STATUS_WAITING: {
                echo "<p>Your request is waiting to be proceed.</p>";
                break;
            }
            case FrameRequests::STATUS_DECLINED: {
                echo "<p> <span class='declined'>Declined.</span>  Sorry, but your request was declined by the administrator.</p>";
                break;
            }
            case FrameRequests::STATUS_ACCEPTED: {
                echo "<textarea class='code' readonly='readonly'><iframe src='" . Poker_Variables::get("domain_referrals") . "/game/?ref=" . UserMachine::getAffiliateCode($user->getId()) . "' class='online-poker-game' width='100%' height='100%'></iframe></textarea>
                    <div class='label textarea'><input type='button' class='button copy' value='  Copy  '  /></div>";
                break;
            }
        }
    }


    echo "           <p class='status'></p>
                </div>
            </fieldset>
            
            <fieldset class='reflink'>
                <hgroup>
                    <h3>Your affiliate link</h3>
                    <p>Take that link and share it in socials, to your friends and fellows. Everyone who will register by your link, will bring you additional money during his game!</p>
                </hgroup>
                
                <div class='label text'>
                    <input type='text' id='reflink' value='http://" . $_SERVER['HTTP_HOST'] . "?ref=" . UserMachine::getAffiliateCode($user->getId()) . "'/>
                </div>
                <div class='label'>
                    <input type='button' class='button copy' value='  Copy  ' />
                    <div class='share42init' data-title='" . Poker_Variables::get("social_link_description") . "' data-url='http://" . $_SERVER['HTTP_HOST'] . "?ref=" . UserMachine::getAffiliateCode($user->getId()) . "'></div>
                    <script type='text/javascript' src='/views/share/share42.js'></script>
                </div>
            </fieldset>
            
            
        ";
    // if($user->level2==1){
    echo "
            <fieldset class='reflink'>
                <hgroup>
                    <h3>Your 2-level affiliate link</h3>
                    <p>Share that link in socials or just send it to your friend. You won't get money from player who registers by that link, but will earn money from every user they'll bring into system</p>
                </hgroup>
                
                <div class='label text'>
                    <input type='text' id='reflink' value='http://" . $_SERVER['HTTP_HOST'] . "?ref=" . UserMachine::getAffiliateCode($user->getId(),
            2) . "'/>
                </div>
                <div class='label '>
                    <input type='button' class='button copy' value='  Copy  ' />
                    <div class='share42init' data-title='" . Poker_Variables::get("social_link_description") . "' data-url='http://" . $_SERVER['HTTP_HOST'] . "?ref=" . UserMachine::getAffiliateCode($user->getId(),
            2) . "'></div>
                    <script type='text/javascript' src='/views/share/share42.js'></script>
                </div>
            </fieldset>
        
        ";
    //  }
    echo "</article>";
}
?>
    