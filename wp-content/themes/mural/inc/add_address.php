<?php
function syncMailchimp(){
	// Validation
	if(!$_POST['email']){ 
		if($_POST["lang"]=="fr"){
			echo json_encode(array( 'error' => 'Aucune adresse courriel fournie.'));
			exit;
			
		}else{
			echo json_encode(array( 'error' => 'No email address provided.'));
			exit;
		}
	 }

	if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $_POST['email'])) {
		if($_POST["lang"]=="fr"){
			echo json_encode(array( 'error' => 'Adresse courriel invalide.'));
			exit;
		}else{
			echo json_encode(array( 'error' => 'Email address not valid.'));
			exit;
		}	
	}

	$apiKey = '82cc092c5b8c8b3bc1495a7bcd067879-us10';
    $listId = '0b79c70b1c';
    
    
    if($apiKey == '' || $listId == ''){
		if($_POST["lang"]=="fr"){
			echo json_encode(array( 'error' => 'En mode test, aucune adresse courriel ajouté.'));
			exit;
		}else{
			echo json_encode(array( 'error' => 'In test mode, no email address is added.'));
			exit;
		}
	}
	
	
    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members';

    $json = json_encode([
        'email_address' => $_POST['email'],
        'status'        => 'pending', // "subscribed","unsubscribed","cleaned","pending"
    ]);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $res = json_decode($result);
	
	if(isset($res->title)){
		if(strpos($res->title, 'Exists') !== 0){
			if($_POST["lang"]=="fr"){
				echo json_encode(array( 'error' => 'Vous êtes déjà  inscrit, merci.'));
				exit;
			}else{
				echo json_encode(array( 'error' => 'You are already registered, thank you.'));
				exit;
			}
		}else{
			if($_POST["lang"]=="fr"){
				echo json_encode(array( 'error' => 'Une erreur est survenue, veuillez réessayer.'));
				exit;
			}else{
				echo json_encode(array( 'error' => 'An error has occurred. Please try again.'));
				exit;
			}
		}
	}else{
    	echo json_encode(array( 'ok' => '1'));
		exit;
	}
	
}

// If being called via ajax, autorun the function
if($_POST['ajax']){ syncMailchimp(); }
?>