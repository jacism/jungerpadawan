<?PHP
	session_start();

	function rand_String( $laenge ) {
		mt_srand( (double)microtime() * 1000000 );
		$zahl = mt_rand( 1000, 9999 );

		$passzahl = md5( $zahl );
		$newpass = substr( $passzahl, 0, $laenge );

		return $newpass;
	};


	$secCode = rand_String( 6 );
	$_SESSION[ 'antispam_'.$_GET[ 'formid' ]] = md5( $secCode );

	// get from: http://www.01-scripts.de/01scripts/01pics/sec.jpg
	$im = imagecreatefromjpeg( "captcha.jpg" );

	// get from: http://www.01-scripts.de/01scripts/01pics/verdanab.ttf
	$font = "verdanab.ttf";
	$fontSize = 13;
	$fontColor = imagecolorallocate( $im, 20, 20, 20 );

	imagettftext( $im, $fontSize, 10, 5, 25, $fontColor, $font, $secCode );
	header( "Content-Type: image/jpeg" );
	imagejpeg( $im, NULL, 100 );
?>
