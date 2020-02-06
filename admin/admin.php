<nav class='menu'>
    <ul>
        <li> <a href='#affiliates'> Manage affiliates </a></li>
        
        <li> <a href='#variables'> Affiliate variables</a></li>
        <li> <a href='#affiliateRequests'> Affiliate requests </a></li>
        <li> <a href='#affiliateBalanceRequests'> Affiliate Balance Requests </a></li>
        <li> <a href='#adminWidthdrawRequests'> Admin Widthdraw Requests </a></li>

        <li> <a href='#frameRequests'> iFrame requests </a></li>
        <li> <a href='#cashout'> Cash Out requests </a></li>
        <li> <a href='#deposits'> Deposit requests </a></li>
        
        <li> <a href='#rakeHistory'> Rake History </a></li>
        <li> <a href='#tournamentFees'> Tournament Fees </a></li>
        <li> <a href='#currenttournaments'> Current tournaments</a></li>
        <li> <a href='#ticket'> Ticket tournaments</a></li>
        <li> <a href='#inbox'> Inbox</a></li>

        <li> <a href='#tournamentRequests'> Tournament requests</a></li>
        <li><a href="#smtpSettings"> SMTP Settings</a></li>
        <li><a href="#paymentMethods"> Payment methods</a></li>
		<li><a href="#chipTransactions"> Players transfer</a></li>
		<li><a href="#tournamentRegistrations">Tournament Registrations</a></li>
    </ul>
</nav>

<div class='content' id='content'>
<!-- Latest compiled and minified CSS -->
<script data-require="jquery@2.2.4" data-semver="2.2.4" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
 <link data-require="bootstrap@3.3.7" data-semver="3.3.7" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
 <script data-require="bootstrap@3.3.7" data-semver="3.3.7" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css" />
    <h2>Admin panel</h2>
    <p>Here you can manage everything that is connected to your users and system.</p>
	<?php require $_SERVER['DOCUMENT_ROOT']."/admin/modules/history.php"; ?>
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
	<select id="payment_method" class="selectpicker" multiple data-actions-box="true">
	        <?php
			$tr = Poker_PaymentMethod::all('all',true);
			foreach($tr as $t){
				echo "<option class='{$t['type']} hidden' value='{$t['id']}'>{$t['name']}</option>";
			}
		?>
	</select>
	<select id="payment_method_hidden" hidden>
	        <?php
			$tr = Poker_PaymentMethod::all('all',true);
			foreach($tr as $t){
				echo "<option class='{$t['type']}' value='{$t['id']}'>{$t['name']}</option>";
			}
		?>
	</select>

	<div>
		<label>
			Total amount
		</label>
		<label id="payment_method_amount">	
			0
		</label>
	</div>
	<div id="withdraw_amount">
		<label>
			Total price
		</label>
		<label id="withdraw_price">	
			0
		</label>
	</div>
</div>
<script>
(function($) {
    $(function() {
		$("input[name='type-payment']").click(function () {
			var radioValue = $("input[name='type-payment']:checked").val();
			$("#payment_method").empty();
			$("#payment_method").append($("#payment_method_hidden ." + radioValue).clone());
			$('.selectpicker').selectpicker('refresh');
			$('.selectpicker').selectpicker('deselectAll');
			$("#payment_method_amount").html(0);
			$("#withdraw_amount").hide();
		});
		$('.selectpicker').on('change', function () {
			var selected = []
			selected = $('.selectpicker').val()
			var radioValue = $("input[name='type-payment']:checked").val();
			if(selected == null){
				$("#payment_method_amount").html(0);
				$("#withdraw_amount").hide();
				return;
			}

			var ids = selected.toString();
			$.post( "/admin/modules/getTotalPayment.php", { ids: ids, type: radioValue })
			.success(function( data ) {
			var data = JSON.parse(data);
				if(data.status == "OK"){
					var total = 0;
					for(var i=0;i<data.data.length;i++){
						total += isNaN(parseInt(data.data[i].total)) ? 0 : parseInt(data.data[i].total);
					}
					if(radioValue == "withdraw")
					{
						$("#payment_method_amount").html(total) ;
						$("#withdraw_price").html(total / <?=Poker_Variables::get('deposit_rate')?>) ;
						$("#withdraw_amount").show()
					}
					else{
						$("#payment_method_amount").html(total);
						$("#withdraw_amount").hide()
					}
					
				}
			});
		});
    });
})(jQuery);
</script>