<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/views/top.php";
error_reporting(0);
?>
<!doctype html>
<html>
<head>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/views/head.php"; ?>

    <title>Online Poker</title>
    <link rel='stylesheet' href='/css/index.css'/>
    <script src='/js/index.js'></script><link rel='manifest' href='/manifest.webmanifest'>


</head>

<body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/views/header.php"; ?>

<main>
    <section class='slider'>

    </section>

    <section class='game_list' style="overflow-x: scroll">
        <h2>Game List</h2>
        <table class="list-tournaments">
            <thead>
            <tr>
                <td></td>
                <td>Game</td>
                <td>Buy in</td>
                <td>Prize bonus</td>
                <td>Start time</td>
                <td>Start full</td>
                <td>Min players</td>
                <td>Max rebuys</td>
                <td>AddOn Chips</td>
                <td>Multiply prize bonus</td>
                <td></td>
            </tr>
            </thead>
            <tbody>

            <?php
            $results = Poker_Cache::getOpenTournaments(); //this sucks xD, need modify
            $count = 1;
            foreach ($results as $i => $result) {
                if (!$result['show']) {
                    continue;
                }
                ?>
                <tr>
                    <td class="text-center"><?= $count++ ?></td>
                    <td>
                        <a href=""><?= $result['name'] ?></a><br>
                        <p><?= $result['game'] ?></p>
                    </td>
                    <td><?= $result['buyin'] ?></td>
                    <td><?= $result['prizebonus'] ?></td>
                    <td><?= $result['starttime'] ?></td>
                    <td><?= $result['startfull'] ?></td>
                    <td><?= $result['minplayers'] ?></td>
                    <td><?= $result['maxrebuys'] ?></td>
                    <td><?= $result['addonchips'] ?></td>
                    <td><?= $result['multiplybonus'] ?></td>
                    <td>
                        <a href="/game"><button class="btn-play">Play now!</button></a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </section>

    <section class='ads'>
        <h2>Advertisment</h2>
        <ul>

        </ul>
    </section>

    <section class='payment'>
        <h2>Payment</h2>
        <figure>
            <img src='/img/payment.png'/>
        </figure>
    </section>
</main>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/views/footer.php"; ?>
</body>
</html>