<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Classes.php";

$paymentMethods = Poker_PaymentMethod::all('all');

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'add':
            $name = trim($_POST['name']);
            $desc = trim($_POST['description']);
			$payment_name = trim($_POST['payment_name']);
			$country = trim($_POST['country']);
			$payment_address = trim($_POST['payment_address']);
			$type = trim($_POST['type']);
            Poker_PaymentMethod::add($name, $desc, $payment_name, $country, $payment_address, $type);
            die(json_encode(['status' => 'OK', 'data' => 'Done']));
            break;

        case 'delete':
            $id = $_POST['id'];
            Poker_PaymentMethod::delete($id);
            die(json_encode(['status' => 'OK', 'data' => 'Done']));
            break;
    }
}

?>

<div id="" class="common">
    <hgroup>
        <h2>Tournament request</h2>
        <p>Here you can accept private tournament request from user</p>
    </hgroup>

    <button class="button" id="btn-add-payment-method">Add payment method</button>

    <table>
        <thead>
        <tr>
            <td>Name</td>
			<td>Type</td>
            <td>Description</td>
			<td>Payment name</td>
			<td>Country</td>
			<td>Payment address</td>
            <td></td>
        </tr>
        </thead>

        <tbody>
        <?php
        foreach ($paymentMethods as $paymentMethod) { ?>
            <tr>
                <td><?= $paymentMethod['name'] ?></td>
				<td><?= $paymentMethod['type'] ?></td>
                <td><?= $paymentMethod['description'] ?></td>
				<td><?= $paymentMethod['payment_name'] ?></td>
				<td><?= $paymentMethod['country'] ?></td>
				<td><?= $paymentMethod['payment_address'] ?></td>
                <td><input class="decline btn-delete-payment-method" data-id="<?= $paymentMethod['id'] ?>" type="button">
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <form id='frmAddPaymentMethod' class='popup' method="post">
        <div class='wrap'>
            <hgroup>
                <h3>Add payment method</h3>
            </hgroup>
            <div>
				<div style="display:inline-block;margin-left:10px;">
					<label>
						<input type="radio" name="type-payment" value="withdraw">
						Withdraw 
					</label>
					<label>
						<input type="radio" name="type-payment" value="deposit">
						Deposit
					</label>
				</div>
                <label>
                    <input type="hidden" id="payment-name" value="<?= $depositConfig['deposit_rate']['value'] ?>">
                    <span>Name</span>
                    <input type='text' id='name' name="name" value=''/>
                </label>

                <label>
                    <span>Description</span>
                    <input type='text' id='payment_description' name="payment_description" value=''/>
                </label>

				 <label>
                    <span>Payment name</span>
                    <input type='text' id='payment_name' name="payment_name" value=''/>
                </label>
				 <label>
                    <span>Country</span>
                    <input type='text' id='country' name="country" value=''/>
                </label>
				 <label>
                    <span>Payment address</span>
                    <input type='text' id='payment_address' name="payment_address" value=''/>
                </label>

                <button type='submit' class='button' value='' id='submit'>Save</button>
                <button type='button' class='button' value='' id='btn-cancel'>Cancel</button>
                <p class='status'></p>
            </div>
        </div>
    </form>
</div>


