<footer>
    <div class='top'>
        <div class="p-relative" style="display: flex;">
            <div class="footer-about" style="flex: 30%">
                <h3>About Game</h3>
                <p>let introduce our game ,this is a sociable games allow clients to invite their friends and play poker together ,
we have integrate the new video call Feature enable client to
video chat with each others while playing our card game .
please Note this is not a Gambling site,
and you cant request a withdraw .
all players start with free 100 Chips, 
and after that you can buy more chips ,
here you can rent a private  table or tournament 
up to 1000 players, and 
you can drop us an email if you Like to manage your own tournament in your region.
our company develop all Kind of softwares and websites and it is our pleasure to take a new challenging project or allow you to choose from our existing product .
****please Note our system will detect if ther is multiple account made from same pc and ip and if any transfer is made 
before making first deposit you will be banned immediately without prior notice  and passport and other verification can be on request
                    Email: <a href="mailt:">@poker.com</a> </p>
            </div>

            <div class="footer-link" style="flex: 50%;position: relative">
                <div style="position: absolute; bottom: 0; right: 0">
                    <a href="#" class="footer-page" id="footer-term-and-condition" data-target="#footer-term-and-condition-page">Terms and condition</a> |
                    <a href="#" class="footer-page" id="footer-privacy-and-policy" data-target="#footer-privacy-and-policy-page">Privacy and policy</a> |
                    <a href="#" class="footer-page" id="footer-poker-rules" data-target="#footer-poker-rules-page">Poker rules</a> |
                    <a href="#" class="footer-page" id="footer-contact" data-target="#footer-contact-page">Contact</a> |
                    <a href="#" class="footer-page" id="footer-about-us" data-target="#footer-poker-about-us">About us</a> |
                    <a href="#" class="footer-page" id="" data-target="#footer-payment-method">Payment method</a>
                </div>
            </div>


        </div>
    </div>
    <div class='bottom'>
        <p>2018 &copy; Online Poker. All rights reserved.</p>
    </div>
</footer>

<div id='popup'>

    <form id='login_form' method='POST' action='/auth/auth.php'>
        <h2> Log In</h2>
        <fieldset>
            <label>
                <span>Your player name</span>
                <input type='text' name='playername' id='playername' required/>
            </label>

            <label>
                <span>Password</span>
                <input type='password' name='password' id='password' required/>
            </label>
            <label>
                <input type='checkbox' name='remember'/>
                <span>Remember me</span>

                <a href="#" class="forget-password" id="forget_password">Forget password?</a>
            </label>



            <input type='submit' id='login_btn' value='Log In'/>
            <input type='button' id='closeForm' value='Cancel'/>
            <p class='status'></p>
        </fieldset>
    </form>

    <form id='register_form' method='post' action='/auth/register.php'>
        <h2>Register</h2>
        <fieldset class='title'>
            <div>
                <label>
                    <span>Player Name</span>
                    <input type='text' required name='playername'/>
                </label>

                <label>
                    <span>E-mail</span>
                    <input type='email' required name='email'/>
                </label>
            </div>
            <ul class='details'>
                <li>Player name is login name that will appear with your avatar on the poker table. The name must be
                    from 3 to 12 characters and can only include letters, numbers, dashes, and underscores.
                </li>
                <li>Email address (80 characters max) is used for account validation and password recovery. It is not
                    displayed to other players.
                </li>
            </ul>

        </fieldset>

        <fieldset class='cols'>
            <div>

                <label>
                    <span>Password</span>
                    <input type='password' required name='password'/>
                </label>

                <label>
                    <span>Real name</span>
                    <input type='text' name='realname'/>
                </label>

                <label>
                    <span>Affiliate code</span>
                    <input type='text'
                           name='affiliatecode' <?php if ($_COOKIE['ref']) echo 'readonly style="background-color: #cccccc;" ' ?>
                           value='<?php echo $_COOKIE['ref'] ?>'/>
                </label>
				<input type='hidden' value='<?php     
				$location = file_get_contents('http://api.ipstack.com/check?access_key=9446a33b664166b2a0e9fd6a8b656976');
				$ip = json_decode($location)->ip;
				echo $ip;  ?>' name='ip'/>
                <input type='hidden' value='<?php echo ($_COOKIE['rlevel'] * 1 + 1) ?>' name='level'/>
            </div>

            <div>
                <label>
                    <span>Confirm password</span>
                    <input type='password' required name='confirmpassword'/>
                </label>

                <label>
                    <span>Location</span>
                    <input type='text' name='location'/>
                </label>

                <p>
                    <span class='heading'>Gender</span>
                    <label class='inline'>
                        <input type='radio' name='sex' value='m' checked>
                        <span>Male</span>
                    </label>
                    <label class='inline'>
                        <input type='radio' name='sex' value='f'/>
                        <span>Female</span>
                    </label>
                </p>
            </div>

            <div style="margin-left: 25%">
                <label>
                    <span>Enter the code</span>
                    <img id="captcha" src="/libs/securimage/securimage_show.php" alt="CAPTCHA Image"/> <input
                            type="text" name="captcha_code" size="10" maxlength="6" required/>
                    <a href="#"
                       onclick="document.getElementById('captcha').src = '/libs/securimage/securimage_show.php?' + Math.random(); return false">[
                        Different Image ]</a>
                </label>
            </div>

            <p class='buttons'>
                <input type='submit' id='register_btn' value='Register'/>
                <input type='button' id='closeForm' value='Cancel'/>
            </p>
            <p class='status'></p>
        </fieldset>
    </form>

    <form id='forget_password_form' method='POST' action='/auth/forget_password.php'>
        <h2> Forget password</h2>
        <fieldset>
            <p>You will receive an url used to reset your password.</p>
            <label>
                <span>Your email</span>
                <input type='text' name='email' id='email' required/>
            </label>
            <input type='submit' id='forget_password_btn' value='Submit'/>
            <input type='button' id='closeForm' value='Cancel'/>
            <p class='status'></p>
        </fieldset>
    </form>


   <form id="footer-contact-page">
        <h2><a href="/#contact">Contact</a></h2>
        <p class="p-10">Contact me via <br/>
            Email:contactus@poker.com <br/>
            Phone:
        </p>
    </form>

    <form id="footer-term-and-condition-page">
        <h2><a href="/#terms-and-condition">Terms and condition</a></h2>
        <p class="p-10">please Note this is not a gambling product and you cant expect money in return ,
and when you submit a payment it is for the service we provide ,
and for chips you get and it is when you request your own tournament .
all price are shown when making a request ,
and no refund is allowed ,
but we keep for you all right and it is our responsability to 
provide the service you paid for .
all players start with free 100 Chips ,
and every 100 Chips cost 1 USD ,
and when requesting your own tournament you pay 5 USD for each seat .
we are following the Texas holdem stabdard rules ,
and this apply on our games .
****Note that our system will detect if there is multiple account made from same pc and ip and if any transfer is made 
before making first deposit you will be banned immediately without prior notice  and passport and other verification can be on request
when you use our service you agree our terms ad conditions and you accept our cookies which used to authorize your access to our services. </p>
    </form>

    <form id="footer-privacy-and-policy-page">
        <h2><a href="/#privacy-and-policy">Privacy and policy</a></h2>
        <p class="p-10">
<div>Please Note This is not a Gambling site and no withdraw option is available , </div>
we understand how much your personal data and information are important for you ,
and this why your data will not shared for any purpose and it is encrypted in our server .
when you submit a payment you doing that for the service we provide ,
and no refund is acceptable ,
but you have all rights and it is our responsibility to provide  the service you 
paid for .
we are usuing the texas holdem standard rules and this which apply
on our game .
</p>
    </form>

    <form id="footer-poker-rules-page">
        <h2><a href="/#poker-rules">Poker rules</a></h2>
        <p class="p-10"><div>* Play of the Hand</div>
<div>Each player is dealt two down (or hole) cards that only they can see. A round of betting occurs. Three community cards (known as the "flop") are dealt face up in the middle of the table. Another round of betting occurs. A fourth community card (known as the "turn") is dealt face up on the table. Another round of betting occurs. A fifth and final community card (known as the "river") is dealt face up on the table. A final round of better occurs. The player's hole cards are revealed and the player with the best five-card poker hand wins the pot. Your five card hand can consist of none, one, or both of your hole cards along with five, four, or three of the community cards. If two or more players share the same best hand, the pot is divided equally among the winners.</div>

 <div>*Rank of Hands</div>
<div>Poker hands are ranked in the order specified below, lowest to highest. Note that only card rank (deuce through ace) matter in poker when comparing individual cards. The suits of clubs, diamonds, hearts, and spades are all considered equal.</div>

<div>High Card: Cards are ranked deuce (2) as the lowest to ace as the highest. If two or more players have the same high card, then the second highest card (and so on, to the fifth card if necessary) determine the winner.</div>


<div>Pair: A pair (two cards of the same rank) beats high card. The highest pair is a pair of aces. If two or more playershave the same pair, then the highest of the three remaining cards (known as kickers) determine the winner.</div>


<div>Two Pair: Two pair beats a pair. If two or more players have two pair, then the highest pair determines the winner. For example, a pair of aces and sevens beats a pair of kings and queens. If two or more players have the same two pair then the fifth card kicker determines the winner.</div>


<div>Three of a Kind: Three of a kind (three cards of the same rank) beats two pair. Three aces is the best of these. If two or more players share the same three of a kind hand, the two remaining kickers determine the winner.</div>


<div>Straight: A straight beats three of a kind. A straight is five consecutive card ranks. Aces can be high or low so the lowest straight is ace through five while the highest is ten through ace. There are no kickers with straights since all five cards are needed to make the hand.</div>


<div>Flush: A flush beats a straight. A flush is any five cards all of the same suit (i.e., all diamonds or all spades, etc.). If two of more players share a flush then the player with the highest card (all the way to the fifth card if necessary) in the flush wins.</div>


<div>Full House: A full house beats a flush. A full house is the combination of three of a kind and a pair. If two or more players have a full house then the player with the best three of a kind wins. If those are the same then the player with the best pair wins.</div>


<div>Four of a Kind: Four of a kind (four cards of the same rank) beats a full house. If two or more players share the same four of a kind, then the fifth card kicker determines the winner.</div>


<div>Straight Flush: A straight flush (five consecutive cards all of the same suit) beats four of a kind. Aces can be high or low. An ace-high straight flush is called a royal flush, the best possible hand in poker.</div>

</p>
    </form>

    <form id="footer-poker-about-us" >
        <h2><a href="/#about-us">About us</a></h2>
        <p class="p-10">our domain name is Registered in Costa Rica,
we are expert regarding website and softwares development ,
with the best technology available ,
and this site show one of our product ,
we hope that you enjoy our games,
and it is our pleasure to take a new challeging project ,
drop us an email when needed </p>
    </form>

    <form id="footer-payment-method">
        <h2><a href="/#payment-method">Payment method</a></h2>
        <p class="p-10">payment method 
currently we accep bitcoin as payment ,
and we are adding new payment continuously ,
here you can download bitcoin ewallet ,
and start using Bitcoin ,
https://www.bitcoin.com/</p>
    </form>
</div>

<script src="/libs/datetimepicker/jquery.js"></script>
<script src="/libs/datetimepicker/build/jquery.datetimepicker.full.min.js"></script>