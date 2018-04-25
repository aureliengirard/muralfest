<?php
include_once('api-classes/programs-routes.php');
include_once('api-classes/events-api.php');
include_once('api-classes/artists-api.php');
include_once('api-classes/venues-api.php');
include_once('api-classes/shows-api.php');

/**
 * Crée les routes lorsque l'API est initialisé.
 */
add_action('rest_api_init', function(){
    $event_controller = new Events_API();
    $event_controller->register_routes();

    $artist_controller = new Artists_API();
    $artist_controller->register_routes();

    $venue_controller = new Venues_API();
    $venue_controller->register_routes();

    $show_controller = new Shows_API();
    $show_controller->register_routes();
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
            header( 'Content-Type: application/xml; charset=utf-8' );

            $xml_data = $result->get_data();
            reset($xml_data);
            $first_key = (!is_numeric(key($xml_data)) ? key($xml_data) : $xml_data[0]['key']);
            
            $xml_user_info = new SimpleXMLExtended('<?xml version="1.0" encoding="utf-8"?><'.$first_key.'></'.$first_key.'>');
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
            if(is_string($value)){
                $elem = $xml_user_info->addChildWithCDATA($key, html_entity_decode($value));

            }else{
                $elem = $xml_user_info->addChild($key, $value);
            }
            

            foreach ($attr as $attr_name => $attr_val) {
                $elem->addAttribute($attr_name, $attr_val);
            }
        }
    }
}


/**
 * SimpleXMLExtended Class
 *
 * Extends the default PHP SimpleXMLElement class by 
 * allowing the addition of cdata
 *
 * @since 1.0
 *
 * @param string $cdata_text
 */
class SimpleXMLExtended extends SimpleXMLElement
{
    public function addCData($cdata_text)
    {
        $node = dom_import_simplexml($this);
        $no   = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($cdata_text));
    }
    /**
     * Adds a child with $value inside CDATA
     * @param unknown $name
     * @param unknown $value
     */
    public function addChildWithCDATA($name, $value = NULL)
    {
        $new_child = $this->addChild($name);
        if ($new_child !== NULL) {
            $node = dom_import_simplexml($new_child);
            $no   = $node->ownerDocument;
            $node->appendChild($no->createCDATASection($value));
        }
        return $new_child;
    }
}