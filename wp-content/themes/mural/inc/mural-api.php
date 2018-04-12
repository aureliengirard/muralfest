<?php
include_once('api-classes/programs-api.php');

/**
 * Crée les routes lorsque l'API est initialisé.
 */
add_action('rest_api_init', function(){
    $controller = new Program_Routes();
    $controller->register_routes();
});


/**
 * Change le nom de base de L'API de "wp-json" à "api".
 */
add_filter( 'rest_url_prefix', function( $prefix ){
    return 'api';
});

/**
 * Permet de retourner différent format avec l'API de WordPress.
 * @link https://github.com/WP-API/WP-API/blob/develop/lib/infrastructure/class-wp-rest-server.php
 * @hooked rest_pre_serve_request
 * @param Bool $served Si la réponse est déjà envoyé.
 * @param WP_HTTP_Response $result Résultat à envoyer.
 * @param WP_REST_Request $request Object de la requête.
 * @param WP_REST_Server $server Instance du serveur.
 * @return Bool
 */
function multiformat_rest_pre_serve_request( $served, $result, $request, $server ) {
    $format = $request->get_attributes();
    $format = (isset($format['format']) ? $format['format'] : '');
    
	// Le format JSON va être traité par WordPress par défaut.
	switch ( $format ) {
		case 'text':
            header( 'Content-Type: text/plain; charset=' . get_option( 'blog_charset' ) );
            
			echo $result->get_data();
			$served = true;
            break;
            
        case 'xml':
            //header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' )  );
            header( 'Content-Type: application/xml; charset=' . get_option( 'blog_charset' )  );

            $xml_data = $result->get_data();
            reset($xml_data);
            $first_key = (!is_numeric(key($xml_data)) ? key($xml_data) : $xml_data[0]['key']);
            
            $xml_user_info = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><'.$first_key.'></'.$first_key.'>');
            eventArray_to_xml($xml_data[$first_key], $xml_user_info);
            echo $xml_user_info->asXML();

			$served = true;
			break;
    }
    
	return $served;
}
add_filter( 'rest_pre_serve_request', 'multiformat_rest_pre_serve_request', 10, 4 );


/**
 * Converti un array en xml.
 * @param Array $array Array a convertir.
 * @param String XML à ajouter les valeurs.
 */
function eventArray_to_xml($array, &$xml_user_info){
    foreach($array as $key => $data){
        $key = (isset($data['key']) ? $data['key'] : $key);
        $value = (isset($data['value']) ? $data['value'] : $data);
        $attr = (isset($data['attr']) ? $data['attr'] : array());
        
        if(is_array($value)){
            if(!is_numeric($key)){
                $subnode = $xml_user_info->addChild($key);
                eventArray_to_xml($value, $subnode);

            }else{
                $subnode = $xml_user_info->addChild($key);
                eventArray_to_xml($value, $subnode);
            }

        }else{
            $elem = $xml_user_info->addChild($key, htmlspecialchars($value));

            foreach ($attr as $attr_name => $attr_val) {
                $elem->addAttribute($attr_name, $attr_val);
            }
        }
    }
}