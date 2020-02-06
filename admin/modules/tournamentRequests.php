<?php
    ?>
    <div id="tournament-requests" class="common">
        <hgroup>
            <h2 style="margin-top: 10px;">Tournament request</h2>
        </hgroup>

        <form action="" id="filter-tournaments">
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
                    <span>Player</span>
                    <input type="text" id="player-name" name="playername">
                </div>
                <div for="" class="w-150 f-left">
                    <span>Invited user</span>
                    <input type="text" id="invited-user" name="invited_user">
                </div>
                <div for="" class="w-150 f-left">
                    <span>Tournament Name</span>
                    <input type="text" id="tournament-name" name="tournament_name">
                </div>
                <div for="" class="w-150 f-left">
                    <span>Create from date</span>
                    <input type="text" id="create-from-date" name="create_from_date">
                </div>
                <div for="" class="w-150 f-left">
                    <span>Create to date</span>
                    <input type="text" id="create-to-date" name="create_to_date">
                </div>
                <input type="hidden" name="filter" value="filter">
                <div for="" class="f-left">
                    <span>&nbsp;</span>
                    <input type="submit" id="search" value="Search" class="" name="submit">
                </div>
            </div>
        </form>
        <div style="clear: both"></div>

        <div id="tournament-request" class="t-wrapper" style="display: block; width: 100%;">
        </div>

    </div>
