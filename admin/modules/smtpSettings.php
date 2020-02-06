<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    $smtpConfig = Poker_Variables::getList(['smtp_host', 'smtp_secure', 'smtp_port', 'smtp_username', 'smtp_password']);

?>
<article id="smtpSettings" class="common">
    <div class="cols">
        <div class="col w-50-p f-left">
            <form action="">
                <fieldset class="urls">
                    <label>
                        <span>SMTP host</span>
                        <input id="smtp_host" value="<?php echo $smtpConfig['smtp_host']['value'] ;?>" type="text">
                    </label>

                    <label>
                        <span>SMTP secure (tls or ssl)</span>
                        <input id="smtp_secure" value="<?php echo $smtpConfig['smtp_secure']['value'] ;?>" type="text">
                    </label>

                    <label>
                        <span>SMTP port</span>
                        <input id="smtp_port" value="<?php echo $smtpConfig['smtp_port']['value'] ;?>" type="text">
                    </label>

                    <label>
                        <span>SMTP username</span>
                        <input id="smtp_username" value="<?php echo $smtpConfig['smtp_username']['value'] ;?>" type="text">
                    </label>

                    <label>
                        <span>SMTP password</span>
                        <input id="smtp_password" value="<?php echo $smtpConfig['smtp_password']['value'] ;?>" type="password">
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</article>