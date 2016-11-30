<?PHP session_start(); ?>

<!DOCTYPE html>
   <html class="no-js" lang="">
    <head>
      <meta charset="utf-8">
          <link rel="stylesheet" href="css/main.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/responsive.css" type="text/css" media="screen" />
     <link rel="stylesheet" href="fonts/font-awesome/css/font-awesome.min.css">
    </head>
    
   <body>

         <?php
        

if(!function_exists("kontaktformular")){
function kontaktformular($empf,$formname,$formid="cforma310",$pfad=""){
global $_POST,$_SESSION;

$return = "";

if(isset($formid) && !empty($formid)){
	$data['name'] = $_POST['name_'.$formid];
	$data['plzort'] = $_POST['plzort_'.$formid];
	$data['tel'] = $_POST['tel_'.$formid];
	$data['email'] = $_POST['email_'.$formid];
	$data['nachricht'] = $_POST['nachricht_'.$formid];
	$data['captcha'] = $_POST['captcha_'.$formid];

	// Für Versand aus Empfänger-Adresse auf jeden Fall einen Array machen:
	if(!is_array($empf))
		$empf = array($empf);

	if(isset($_POST['name_'.$formid]) && !empty($_POST['name_'.$formid]) &&
	   isset($_POST['email_'.$formid]) && !empty($_POST['email_'.$formid]) && check_mail($_POST['email_'.$formid]) &&
	   isset($_POST['nachricht_'.$formid]) && !empty($_POST['nachricht_'.$formid]) &&
	   isset($_POST['captcha_'.$formid]) && !empty($_POST['captcha_'.$formid]) && md5($_POST['captcha_'.$formid]) == $_SESSION['antispam_'.$formid]){
	    $absender = preg_replace( "/[^a-z0-9 !?:;,.\/_\-=+@#$&\*\(\)]/im", "",$_POST['email_'.$formid]);
	    $absender = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "",$absender);
	    $header = "From:".$absender."<".$absender.">";

	    $name = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "",$_POST['name_'.$formid]);
		$plz = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "",$_POST['plzort_'.$formid]);
	    $tel = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "",$_POST['tel_'.$formid]);
	    $txt = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "",$_POST['nachricht_'.$formid]);

    	foreach($empf as $empf_mail){
			mail($empf_mail,$formname,"Name: ".$name."
PLZ Ort: ".$plz."
Telefon: ".$tel."
E-Mail: ".$absender."

Nachricht:
".$txt."",$header);
		}

	    $return .= "<h3 class=\"send\">Danke f&uuml;r Ihre Nachricht!</h3>";
	    }
	elseif(isset($_POST['submit_'.$formid]) && !empty($_POST['submit_'.$formid])){
	    $return .= "<h3 class=\"send error\">Etwas ist schiefgelaufen - bitte Eingabefelder checken</h3>";
	    }
	}
else $formid = rand_String(12);

$return .= "
<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">
<table cellpadding=\"0\" cellspacing=\"0\" class=\"formulartabelle\">
   <tr>
      <td><input class=\"tx\" type=\"text\" placeholder=\"Name\" name=\"name_".$formid."\" value=\"".$data['name']."\" /></td>
      <td><input class=\"tx\" type=\"text\" placeholder=\"Webseite\" name=\"plzort_".$formid."\" value=\"".$data['plzort']."\" /> </td>
   </tr>
   <tr>
      <td><input class=\"tx\" type=\"text\" placeholder=\"Telefonnummer\" name=\"tel_".$formid."\" value=\"".$data['tel']."\" /></td>
      <td><input class=\"tx\" type=\"text\" placeholder=\"E-Mail\" name=\"email_".$formid."\" value=\"".$data['email']."\" /></td>
   </tr>
  <tr>
      
      <td colspan=\"2\">
      <textarea class=\"tx writemefield\" placeholder=\"NACHRICHT\" rows=\"5\" name=\"nachricht_".$formid."\">".$data['nachricht']."</textarea>
      </td>
      
   </tr>
   <!--captcha-->
   <tr>
   
      <td><img src=\"".$pfad."secimg.php?formid=".$formid."\" alt=\"Sicherheitscode (Spamschutz)\" title=\"Sicherheitscode: Anti-Spam-System\" width=\"90\" height=\"30\" id=\"captachafeld\" />
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
   
</table>
</form>
";

return $return;

}
}

if(!function_exists("kontaktformular_fileupload")){
function kontaktformular_fileupload($empf,$formname,$formid="cformup320",$pfad=""){
global $_POST,$_SESSION;

$allowed_endings  = array('jpg','pdf','zip');		// Array mit allen erlaubten Dateiendungen:
$allowed_filesize = 500000;							// Max. Dateigröße in Byte

$return = "";
$error = FALSE;

if(isset($formid) && !empty($formid)){
	$data['name'] = $_POST['name_'.$formid];
	$data['plzort'] = $_POST['plzort_'.$formid];
	$data['tel'] = $_POST['tel_'.$formid];
	$data['email'] = $_POST['email_'.$formid];
	$data['nachricht'] = $_POST['nachricht_'.$formid];
	$data['captcha'] = $_POST['captcha_'.$formid];

	$error = TRUE;

	// Für Versand aus Empfänger-Adresse auf jeden Fall einen Array machen:
	if(!is_array($empf))
		$empf = array($empf);

	if(isset($_POST['name_'.$formid]) && !empty($_POST['name_'.$formid]) &&
	isset($_POST['email_'.$formid]) && !empty($_POST['email_'.$formid]) && check_mail($_POST['email_'.$formid]) &&
	isset($_POST['nachricht_'.$formid]) && !empty($_POST['nachricht_'.$formid]) &&
	isset($_POST['captcha_'.$formid]) && !empty($_POST['captcha_'.$formid]) && md5($_POST['captcha_'.$formid]) == $_SESSION['antispam_'.$formid]){
		$error = FALSE;

	    $absender = preg_replace( "/[^a-z0-9 !?:;,.\/_\-=+@#$&\*\(\)]/im", "",$_POST['email_'.$formid]);
	    $absender = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "",$absender);
	    $mail_header = "From:".$absender."<".$absender.">";

	    $name = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "",$_POST['name_'.$formid]);
		$plz = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "",$_POST['plzort_'.$formid]);
	    $tel = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "",$_POST['tel_'.$formid]);
	    $txt = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "",$_POST['nachricht_'.$formid]);

	    $mail_content = "Name: ".$name."
PLZ Ort: ".$plz."
Telefon: ".$tel."
E-Mail: ".$absender."

Nachricht:
".$txt."";

	    // Datei-Anhang verarbeiten
	    if(isset($_FILES['file_'.$formid]['name']) && $_FILES['file_'.$formid]['name'] != ""){
			$split = pathinfo($_FILES['file_'.$formid]['name']);
			$filename = $split['filename'];
			$fileType = $split['extension'];

	    	// Erlaubte Endungen / Dateigröße überprüfen
	    	if(in_array($fileType,$allowed_endings) && $_FILES['file_'.$formid]['size'] <= $allowed_filesize){
	    		$boundary = strtoupper(md5(uniqid(time())));

				$mail_header .= "\nMIME-Version: 1.0"."";
				$mail_header .= "\nContent-Type: multipart/mixed;  boundary=\"".$boundary."\"";
		
				$mail_body  = "\nMIME-Version: 1.0"."";
				$mail_body .= "\nContent-Type: multipart/mixed;  boundary=\"".$boundary."\"";
				$mail_body .= "\n\nThis is a multi-part message in MIME format  --  Dies ist eine mehrteilige Nachricht im MIME-Format";

				// "Normalen" Text-Inhalt einfügen:
				$mail_body .= "\n--".$boundary."";
				$mail_body .= "\nContent-Type: text/plain";
				$mail_body .= "\nContent-Transfer-Encoding: 8bit\n";
				$mail_body .= "\n".$mail_content;
				$mail_body .= "\n--".$boundary."";

			    $file_content = fread(fopen($_FILES['file_'.$formid]['tmp_name'],"r"),$_FILES['file_'.$formid]['size']);
			    $file_content = chunk_split(base64_encode($file_content));

			    $mail_body .= "\nContent-Type: ".mime_content_type($_FILES['file_'.$formid]['tmp_name'])."; name=\"".stripslashes($_FILES['file_'.$formid]['name'])."\"";
			    $mail_body .= "\nContent-Transfer-Encoding: base64";
			    $mail_body .= "\nContent-Disposition: attachment; filename=\"".stripslashes($_FILES['file_'.$formid]['name'])."\"";
			    $mail_body .= "\n\n".$file_content."";
			    $mail_body .= "\n--".$boundary."";
	    	}
	    	else
	    		$error = TRUE;
	    }
	    // Wenn kein Datei-Anhang ausgewählt wurde
	    else{
			$mail_body = $mail_content;
	    }

    	foreach($empf as $empf_mail){
    		//echo $mail_header."<br /><hr><br />".$mail_body;
			mail($empf_mail,$formname,$mail_body,$mail_header);
		}

	    $return .= "<br /><p style=\"border: 1px solid green; padding: 5px;\">
		<b>Ihre Nachricht wurde erfolgreich verschickt und wird so schnell wie m&ouml;glich bearbeitet.</b></p>";
	    }
	
	if(isset($_POST['submit_'.$formid]) && !empty($_POST['submit_'.$formid]) && $error){
	    $return .= "<br /><p style=\"border: 1px solid red; padding: 5px;\">
	    Sie haben nicht alle ben&ouml;tigen Felder (*) ausgef&uuml;llt oder den Spamschutzcode nicht richtig eingegeben.</p>";
	    }
	}
else $formid = rand_String(12);

$return .= "
<form enctype=\"multipart/form-data\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">
<table cellpadding=\"0\" cellspacing=\"5\" class=\"formtab pluginwidth\">

	<tr>
		<td></td>
		<td><input class=\"tx\" type=\"text\" name=\"name_".$formid."\" value=\"".$data['name']."\" /></td>
	</tr>

	<tr>
		<td></td>
		<td><input class=\"tx\" type=\"text\" name=\"plzort_".$formid."\" value=\"".$data['plzort']."\" /></td>
	</tr>

	<tr>
		<td>Telefon</td>
		<td><input class=\"tx\" type=\"text\" name=\"tel_".$formid."\" value=\"".$data['tel']."\" /></td>
	</tr>

	<tr>
		<td>E-Mail *</td>
		<td><input class=\"tx\" type=\"text\" name=\"email_".$formid."\" value=\"".$data['email']."\" /></td>
	</tr>

	<tr>
		<td>Datei</td>
		<td><input type=\"file\" name=\"file_".$formid."\" /></td>
	</tr>

	<tr>
		<td valign=\"top\">Nachricht *</td>
		<td><textarea class=\"tx\" name=\"nachricht_".$formid."\">".$data['nachricht']."</textarea></td>
	</tr>

	<!--captcha-->
	<tr>
		<td>Sicherheitscode</td>
		<td><img src=\"".$pfad."secimg.php?formid=".$formid."\" alt=\"Sicherheitscode (Spamschutz)\" title=\"Sicherheitscode: Anti-Spam-System\" width=\"90\" height=\"30\" /></td>
	</tr>
	<tr>
		<td>Sicherheitscode eingeben *</td>
		<td>
			<input type=\"text\" class=\"tx\" name=\"captcha_".$formid."\" maxlength=\"6\" style=\"width: 80px; \" />
		</td>
	</tr>

	<input type=\"hidden\" name=\"formid\" value=\"".$formid."\" />
	<tr>
		<td colspan=\"2\" align=\"right\"><input type=\"submit\" id=\"thebutton\" name=\"submit_".$formid."\" value=\"Absenden\" /></td>
	</tr>
</table>
</form>";

return $return;

}
}

if(!function_exists("rand_String")){
function rand_String($laenge){
	mt_srand((double)microtime()*1000000);
	$zahl = mt_rand(1000, 9999);

	$passzahl = md5($zahl);
	$newpass = substr($passzahl,0,$laenge);

	return $newpass;
	}
}

if(!function_exists("check_mail")){
function check_mail($email){
	if(filter_var($email, FILTER_VALIDATE_EMAIL)) return TRUE;
	else return FALSE;
}
}

// Mime-Typen von Dateien bestimmen
/*$filename		Dateiname zu dem der Dateityp bestimmt werden soll
  
RETURN: Mime-Typ
  */
if(!function_exists('mime_content_type')) {
function mime_content_type($filename) {

    $mime_types = array(
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',

        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',

        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',

        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );

    $ext = strtolower(array_pop(explode('.',$filename)));
    if (array_key_exists($ext, $mime_types)) {
        return $mime_types[$ext];
    }
    elseif (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME);
        $mimetype = finfo_file($finfo, $filename);
        finfo_close($finfo);
        return $mimetype;
    }
    else
        return 'application/octet-stream';

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
