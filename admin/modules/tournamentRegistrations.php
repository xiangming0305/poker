<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Classes.php";
    ?>
    <div id="tournament-registrations" class="common">
        <hgroup>
            <h2 style="margin-top: 10px;">Tournament Registrations</h2>
        </hgroup>
        <form action="" id="chip-transaction">
            <div class="filter">
                <div for="" class="w-150 f-left">
                    <span>From date</span>
                    <input type="text" id="from-date" name="from_date">
                </div>
                <div for="" class="w-150 f-left">
                    <span>To date</span>
                    <input type="text" id="to-date" name="to_date">
                </div>
                <div for="" class="w-150 f-left">
                    <span>Name Tournament</span>
                    <input type="text" id="name" name="name">
                </div>
                <div for="" class="w-150 f-left">
                    <span>Diffcreator</span>
                    <input type="text" id="player" name="player">
                </div>
				 <div for="" class="w-150 f-left">
                    <span>Creator</span>
                    <input type="text" id="creator" name="creator">
                </div>
				 <div for="" class="w-150 f-left">
                    <span>Invited</span>
                    <input type="text" id="inviteduser" name="inviteduser">
                </div>
                <div for="" class="f-left">
                    <span>&nbsp;</span>
                    <input type="submit" id="search" value="Search" class="" name="submit">
                </div>
            </div>
        </form>
        <div style="clear: both"></div>

        <div id="tournamentRegistrations" class="t-wrapper" style="display: block; width: 100%;">
        </div>

    </div>
