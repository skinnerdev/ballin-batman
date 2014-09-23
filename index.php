<?php
include 'core/init.php';
$page = 'home';
include 'includes/overall/overall_header.php';
$status = (SITE_STATUS == BETA) ? 'Alpha' : 'Beta';
?>
<div itemscope itemtype="http://schema.org/WebApplication">
	<h1>Welcome to the <span itemprop="name">Factionizer</span>.</h1>
	<br>
	<h2>The Factionizer is tool for creating Live Action Role Playing games, or <span itemprop="about">LARPs</span>.  It allows you to SEE all of the interactions between characters and factions within a LARP,  and then automatically creates character sheets.</h2>
	<br>
	<br>
</div>
<?php
if (SITE_STATUS != RELEASE) {
	if ( ! is_logged_in()) {
		echo '<h2>To sign up for the Beta test, please sign in or <a href=register.php>register!</a></h2>';
	} else {
		if ( ! user_has_beta()) {
			echo '<h2><p>You can sign up for the beta <a href=beta.php>HERE</a>.</p></h2>';
		} else {
			echo "<h2>The Factionizer is currently in $status testing. You are already signed up for the Beta test!</h2>";
		}
	}
}
?>
<br>
<br>
<p>
<a href="http://www.dexposure.com" target="_blank">Double Exposure, Inc</a> is dedicated and passionate about LARP and independently run games.  We write and host LARPs and may other games at our conventions, DEXCON and Dreamation, and our Game Design Festival, Metatopia, is THE place for board game publishing.  We have been featured in <a target="_blank" href="http://www.amazon.com/gp/product/B0087HWD44/ref%3das_li_qf_sp_asin_il_tl?ie%3dUTF8%26camp%3d1789%26creative%3d9325%26creativeASIN%3dB0087HWD44%26linkCode%3das2%26tag%3dfactionizerco-20">a book on LARP</a> and try our best to contribute to the Nordic LARP community from afar.  We have been hired to run tracks at other conventions, including GenCon and BronyCon.</p>
<?php include 'includes/overall/overall_footer.php'; ?>