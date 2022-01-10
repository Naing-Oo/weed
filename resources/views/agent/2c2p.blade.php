@extends('layouts.admin_master')

@section('content')
<span>V4 Payment Page on iFrame</span> <br/>  
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<span>V4 URL:</span> <input type="text" name="url" style="width:600px;" value="https://sandbox-pgw-ui.2c2p.com/payment/4.1/#/token/kSAops9Zwhos8hSTSeLTUU0WFFpBjc19mS5csybD4Fx7tSNfeh1ydc1sQYQM7ikDf6QM%2fZ5Puc2LvVt2heZCKvm30bGvJBDvRWUWJnccJV8%3d">
	<input type="submit">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
	$url = $_POST['url'];
	if (!empty($url)) {  
		echo '<iframe name="output_frame" id="output_frame" src="'.$url.'"  width="800" height="600"></iframe>';
	}
}
?>
<br/>
<button type="button" onclick="triggerSubmitPayment();"  style="width:200px; height:80px; margin-bottom:100px;" >PAY NOW</button>
@endsection




@section('script')
<script> 
const handlePaymentPostMessages = ({ data }) => {
	const { paymentResult } = data;
	if (paymentResult) {
		const { respCode, respDesc, respData } = paymentResult;

		//CODE HERE 
		alert(respCode+': '+respDesc+': '+respData);
		if(respCode == '2000'){
			alert("payment completed");
			window.location.replace("./thankyou.html"); 
		}
		if(respCode == '1001'){
			alert("redirect to continue payment");
			window.location.replace(respData); 
		}
	}
};
// Subscribe on post messages
window.addEventListener('message', handlePaymentPostMessages);

function triggerSubmitPayment() {
    var iFrame = document.querySelector('#output_frame');
    if (iFrame) {
		// possible types to trigger:
		//submit_gcard 		- Global Credit Card
		//submit_lcard 		- Local Credit Card
		//submit_dpay 		- Digital Payment
		//submit_qr 		- QR Payment
		//submit_counter 	- Counter Payment
		//submit_ssm 		- Self Service Machine
		//submit_webpay 	- Web Payment / Direct Debit
		//submit_imbank 	- iBanking / mBanking
		//submit_gbnpl 		- Global Buy Now Pay Later
        iFrame.contentWindow.postMessage(
            'submit_gcard',
            '*'
        );
    }
}
</script>

@endsection




