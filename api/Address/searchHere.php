<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.php");

    // HERE API
    function searchHereRestAPI($q) {
        //HERE maps

        // APP ID
        global $_HERE_REST_APP_ID;

        // APP CODE
        global $_HERE_REST_APP_CODE;

        $url = file_get_contents('https://geocoder.api.here.com/6.2/geocode.json?searchtext=' . urlencode($q) . '&app_id=' . $_HERE_REST_APP_ID . '&app_code=' . $_HERE_REST_APP_CODE . '&gen=8');

        $json = json_decode($url, true);
        return $json;
    }

    try {
        // No fields found
        requireFields(array('q'));

        $results = searchHereRestAPI($_PHP_INPUT['q']);

        if( $results["Response"]["View"][0]["Result"][0]["MatchQuality"]["City"] &&
            $results["Response"]["View"][0]["Result"][0]["MatchQuality"]["Street"][0] &&
            $results["Response"]["View"][0]["Result"][0]["MatchQuality"]["HouseNumber"]) 
        {
            $stmt = $mysqli->prepare('
                UPDATE `Address`
                SET `longitude` = ?, `latitude` = ?
                WHERE CONCAT(`Street1`, ", ", `City`) = ?
            ');
            $longitude = $results["Response"]["View"][0]["Result"][0]["Location"]["DisplayPosition"]["Longitude"];
            $latitude = $results["Response"]["View"][0]["Result"][0]["Location"]["DisplayPosition"]["Latitude"];
            $stmt->bind_param('dds', $longitude, $latitude, $_PHP_INPUT['q']);

            $stmt->execute();
        }


        // FINISHING
        echo json_encode(array(
            'error' => false, 
            'results' => $results
        ), JSON_NUMERIC_CHECK); 

    } catch (Exception $e) {
        echo json_encode(array('error' => true, 'message' => $e->getMessage()), JSON_PRETTY_PRINT); 
    }

    // FINISHING
    $mysqli->close();
?>