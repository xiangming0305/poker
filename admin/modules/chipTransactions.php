<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Classes.php";
    ?>
    <div id="tournament-requests" class="common">
        <hgroup>
            <h2 style="margin-top: 10px;">Tournament request</h2>
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
                    <span>User</span>
                    <input type="text" id="user" name="user">
                </div>
                <div for="" class="w-150 f-left">
                    <span>Target</span>
                    <input type="text" id="taget" name="taget">
                </div>
                <div for="" class="f-left">
                    <span>&nbsp;</span>
                    <input type="submit" id="search" value="Search" class="" name="submit">
                </div>
            </div>
        </form>
        <div style="clear: both"></div>

        <div id="chipTransactions" class="t-wrapper" style="display: block; width: 100%;">
        </div>

    </div>
