<?PHP session_start(); ?>

<!DOCTYPE html>
<html class="no-js" lang="">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/main.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="css/vendor/responsive.css" type="text/css" media="screen" />
	</head>

	<body>
		<?php
			if ( !function_exists( "rand_String" ) ) {
				function rand_String( $laenge ) {
					mt_srand( (double)microtime() * 1000000 );
					$zahl = mt_rand( 1000, 9999 );

					$passzahl = md5( $zahl );
					$newpass = substr( $passzahl, 0, $laenge );

					return $newpass;
				}
			}

			if ( !function_exists( "check_mail" ) ) {
				function check_mail( $email ) {
					if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
						return TRUE;
					} else {
						return FALSE;
					}
				}
			}

			if ( !function_exists( "kontaktformular" ) ) {
				// ZZZ Kommentar zur Funktion bzw den einzelnen Argumenten hinzufügen
				/**
				 * [kontaktformular description]
				 * @param  [type] $empf     [description]
				 * @param  [type] $formname [description]
				 * @param  string $formid   [description]
				 * @param  string $pfad     [description]
				 * @return [type]           [description]
				 */
				function kontaktformular( $empf, $formname, $formid="cforma310", $pfad="" ) {
					global $_POST, $_SESSION;
					$return = "";

					if ( isset( $formid ) && !empty( $formid ) ) {
						$data[ 'name' ] = $_POST[ 'name_'.$formid ];
						$data[ 'plzort' ] = $_POST[ 'plzort_'.$formid ];
						$data[ 'tel' ] = $_POST[ 'tel_'.$formid ];
						$data[ 'email' ] = $_POST[ 'email_'.$formid ];
						$data[ 'nachricht' ] = $_POST[ 'nachricht_'.$formid ];
						$data[ 'captcha' ] = $_POST[ 'captcha_'.$formid ];

						// Für Versand aus Empfänger-Adresse auf jeden Fall einen Array machen
						if ( !is_array( $empf ) ) {
							$empf = array( $empf );
						}

						if (
							isset( $_POST[ 'name_'.$formid ] ) &&
							!empty( $_POST[ 'name_'.$formid ] ) &&
							isset( $_POST[ 'email_'.$formid ] ) &&
							!empty( $_POST[ 'email_'.$formid ] ) &&
							check_mail( $_POST[ 'email_'.$formid ] ) &&
							isset( $_POST[ 'nachricht_'.$formid ] ) &&
							!empty( $_POST[ 'nachricht_'.$formid ] ) &&
							isset( $_POST[ 'captcha_'.$formid ] ) &&
							!empty( $_POST[ 'captcha_'.$formid ] ) &&
							( md5( $_POST[ 'captcha_'.$formid ] ) == $_SESSION[ 'antispam_'.$formid ] )
						) {
							$absender = preg_replace( "/[^a-z0-9 !?:;,.\/_\-=+@#$&\*\(\)]/im", "", $_POST[ 'email_'.$formid ] );
							$absender = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "", $absender );
							$header = "From:".$absender."<".$absender.">";
							$name = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "", $_POST[ 'name_'.$formid ] );
							$plz = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "", $_POST[ 'plzort_'.$formid ] );
							$tel = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "", $_POST[ 'tel_'.$formid ] );
							$txt = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "", $_POST[ 'nachricht_'.$formid ] );

							foreach ( $empf as $empf_mail ) {
								mail (
									$empf_mail,
									$formname,
// ZZZ besser explizit linebreak für Zeielnumbruch verwenden
"Name: ".$name."
PLZ Ort: ".$plz."
Telefon: ".$tel."
E-Mail: ".$absender."

Nachricht:
".$txt."",
									$header
								);
							}

							$return .= "<h3 class=\"send\">Danke f&uuml;r Ihre Nachricht!</h3>";

						} elseif ( isset( $_POST[ 'submit_'.$formid ] ) && !empty( $_POST[ 'submit_'.$formid ]) ) {
							$return .= "<h3 class=\"send error\">Etwas ist schiefgelaufen - bitte Eingabefelder checken</h3>";
						}

					} else {
						$formid = rand_String( 12 );
					}

					$return .= "
						<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">
							<table cellpadding=\"0\" cellspacing=\"0\" class=\"formulartabelle\">
								<tbody>
									<tr>
										<td>
											<input class=\"tx\" type=\"text\" placeholder=\"Name\" name=\"name_".$formid."\" value=\"".$data['name']."\" />
										</td>
										<td>
											<input class=\"tx\" type=\"text\" placeholder=\"Webseite\" name=\"plzort_".$formid."\" value=\"".$data['plzort']."\" />
										</td>
									</tr>
									<tr>
										<td>
											<input class=\"tx\" type=\"text\" placeholder=\"Telefonnummer\" name=\"tel_".$formid."\" value=\"".$data['tel']."\" />
										</td>
										<td>
											<input class=\"tx\" type=\"text\" placeholder=\"E-Mail\" name=\"email_".$formid."\" value=\"".$data['email']."\" />
										</td>
									</tr>
									<tr>
										<td colspan=\"2\">
											<textarea class=\"tx writemefield\" placeholder=\"NACHRICHT\" rows=\"5\" name=\"nachricht_".$formid."\">".$data['nachricht']."</textarea>
										</td>
									</tr>
									<!--captcha-->
									<tr>
										<td>
											<img src=\"".$pfad."captcha.php?formid=".$formid."\" alt=\"Sicherheitscode (Spamschutz)\" title=\"Sicherheitscode: Anti-Spam-System\" width=\"90\" height=\"30\" id=\"captachafeld\" />
										</td>
										<td>
											<input type=\"text\" class=\"tx captchafield\" placeholder=\"CAPTCHA\" name=\"captcha_".$formid."\" maxlength=\"6\" style=\"width: 7em; \" />
										</td>
									</tr>
									<tr>
										<td colspan=\"2\">
											<input type=\"submit\" name=\"submit_".$formid."\" value=\"Absenden\" class=\"thebutton contactbtn refbtn\" id=\"thebutton\"/>
										</td>
									</tr>
									<input type=\"hidden\" name=\"formid\" value=\"".$formid."\" />
								</tbody>
							</table>
						</form>
					";

					return $return;
				}
			}

			echo kontaktformular(array("helloiamjac@gmail.com","jacek@kuclo.com"),"Kontaktformular","form1");
		?>
		<div class="buttonwrapper">
			<hr>
			<p class="cntr">
				Jacek Kuclo <i class="fa fa-circle" aria-hidden="true"></i>
				Nikolaistr. 1 <i class="fa fa-circle" aria-hidden="true"></i>
				30169 Hannover <i class="fa fa-circle" aria-hidden="true"></i>
				0171 223 7677 <i class="fa fa-circle" aria-hidden="true"></i>
				jacek@kuclo.com
			</p>
		</div>
	</body>
</html>
