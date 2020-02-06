<?php
$tournamentConfigs = PrivateTournament::adminConfigFields();

$listKeys  = array_map(function($item) {
    return PrivateTournament::genKey($item['name']);
}, $tournamentConfigs);
$tournamentConfigsData = Poker_Variables::getList($listKeys);

//$query = 'INSERT INTO poker_variables (name) values ';
//foreach ($tournamentConfigs as $config)  {
//    $query .= "('tournament_" . strtolower($config['name']) . "'),";
//}
//print_r($query); die;
?>

<fieldset class="create-tournament-config">
    <hgroup>
        <h3>Request chips per seat</h3>
        <p>Config variables when user request private tournament</p>
    </hgroup>

    <div class="formula">
        <span>Default chips</span>
        <input type="text" name="tournament_request_chips_per_seat" id="tournament_request_chips_per_seat" value="<?=Poker_Variables::get('tournament_request_chips_per_seat')?>">
    </div>
    <div class="formula">
        <span>Fee</span>
        <input type="text" name="tournament_fee" id="tournament_fee" value="<?=Poker_Variables::get('tournament_fee')?>">
    </div>
    <?php
    foreach ($tournamentConfigs as $item) {
        $key = "tournament_" . strtolower($item['name']);
        ?>
        <div class="formula">
            <span><?= $item['name'] ?></span>
            <?php
            switch ($item['type']) {
                case 'text': ?>
                    <input type="text" name="<?= $key ?>" value="<?=$tournamentConfigsData[$key]['value']?>" id="<?=$key?>">
                    <?php break;

                case 'number': ?>
                    <input type="number"
                           id="<?=$key?>"
                           value="<?=$tournamentConfigsData[$key]['value']?>"
                           name="<?= $key ?>"
                        <?= isset($item['min']) ? "min=" . $item['min'] : '' ?>
                        <?= isset($item['max']) ? "max=" . $item['min'] : '' ?>
                    />
                    <?php
                    if (isset($item['min'])) { ?>
                        (<?= isset($item['min']) ? $item['min'] : '' ?> - <?= isset($item['max']) ? $item['max'] : '' ?>)
                    <?php } ?>
                    <?php break;

                case 'select': ?>
                    <select name="<?= $key ?>" id="<?=$key?>">
                        <option value=""></option>
                        <?php
                        foreach ($item['data'] as $option) { ?>
                            <option value="<?= $option ?>" <?=($option == $tournamentConfigsData[$key]['value'] ? 'selected' : '')?>><?= $option ?></option>
                        <?php }
                        ?>
                    </select>
                    <?php break;

                default:
                    break;
            } ?>
        </div>
    <?php } ?>

</fieldset>