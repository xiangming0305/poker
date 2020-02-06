<div id='tournamentRequest' class="d-none common-popup" >
    <div class='wrap'>
        <hgroup>
            <h3>Request private tournament</h3>

        </hgroup>
        <div class="container">
            <form id='tournamentRequest_form' method='post'>
                <fieldset class='cols'>
                    <div>

                        <label>
                            <span>Game</span>
                            <select name="game" id="game">
                                <option value="Limit Hold'em">Limit Hold'em</option>
                                <option value="Pot Limit Hold'em">Pot Limit Hold'em</option>
                                <option value="No Limit Hold'em">No Limit Hold'em</option>
                                <option value="Limit Omaha">Limit Omaha</option>
                                <option value="Pot Limit Omaha">Pot Limit Omaha</option>
                                <option value="No Limit Omaha">No Limit Omaha</option>
                                <option value="Limit Omaha Hi-Lo">Limit Omaha Hi-Lo</option>
                                <option value="Pot Limit Omaha Hi-Lo">Pot Limit Omaha Hi-Lo</option>
                                <option value="No Limit Omaha Hi-Lo">Limit No Limit Omaha Hi-Lo</option>
                            </select>
                        </label>

                        <label>
                            <span>Tables</span>
                            <input type="number" name="tables" min="1" max="100" >
                        </label>

                        <label>
                            <span>Seats</span>
                            <input type="number" name="seats" min="2" max="10" >
                        </label>

                        <label>
                            <span>Buyin</span>
                            <input type="text" name="buyin" >
                        </label>

                        <label>
                            <span>Max Rebuys</span>
                            <input type="text" name="maxrebuys" >
                        </label>

                        <label>
                            <span>Start time</span>
                            <input type="text" name="starttime" id="starttime">
                        </label>

                        <label>
                            <span>Users to invite (separate by commas)</span>
                            <input type="text" name="invite_users" id="invite_users" value="">
                            <datalist id='players2'>
                                <template id='player-option2'>
                                    <option value='{{name}}'>{{name}}</option>
                                </template>
                            </datalist>
                        </label>

                        <label>
                            <span>Chips to pay</span>
                            <input type="text" id="chips_to_pay" value="0" disabled>
                        </label>

                    </div>
                </fieldset>

                <p class='status'></p>
                <p class='buttons'>
                    <input type="hidden" name="tournament_request_chips_per_seat"
                           id="tournament_request_chips_per_seat"
                           value="<?=Poker_Variables::get('tournament_request_chips_per_seat')?>" >
                    <input type='submit' class="button" value='Pay and submit' />
                </p>
            </form>
        </div>
    </div>
</div>