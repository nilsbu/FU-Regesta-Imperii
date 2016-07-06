<?php 

function getDbpediaInfo($term)
{
    $query = 
    "PREFIX dbp: <http://dbpedia.org/resource/>
    PREFIX dbp2: <http://dbpedia.org/ontology/>

    SELECT ?birthDate ?birthPlace ?deathDate ?deathPlace ?abstract 
    WHERE { 
        dbp:".$term." dbp2:birthDate ?birthDate . 
        dbp:".$term." dbp2:birthPlace ?birthPlace . 
        dbp:".$term." dbp2:deathDate ?deathDate . 
        dbp:".$term." dbp2:deathPlace ?deathPlace . 
        dbp:".$term." dbp2:abstract ?abstract .

       FILTER langMatches(lang(?abstract), 'de')
    }";

    $searchUrl = 'http://dbpedia.org/sparql?'
       .'query='.urlencode($query)
       .'&format=json';

    return $searchUrl;
}
function request($url){
 
   if (!function_exists('curl_init')){ 
      die('CURL is not installed!');
   }

   $ch= curl_init();
 
   curl_setopt($ch, 
      CURLOPT_URL, 
      $url);
   curl_setopt($ch, 
      CURLOPT_RETURNTRANSFER, 
      true);	
   $response = curl_exec($ch);
 
   curl_close($ch);
 
   return $response;
}

$term = "Charlemagne";
$requestURL = getDbpediaInfo($term);
$response = json_decode(request($requestURL), true);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<title>DBPedia Info</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

</head>

<body>
    <h1>DBPedia Info: <?php echo $term ?></h1>

    <h4>Birth Date: </h4>
    <?php echo $response["results"]
       ["bindings"][0]
       ["birthDate"]["value"] ?>
    <br/>

    <h4>Birth Place: </h4>
    <?php echo $response["results"]
       ["bindings"][1]
       ["birthPlace"]["value"] ?>
    <br/>
   
    <h4>Death Date: </h4>
    <?php echo $response["results"]
       ["bindings"][2]
       ["deathDate"]["value"] ?>
    <br/>
   
    <h4>Death Place: </h4>
    <?php echo $response["results"]
       ["bindings"][3]
       ["deathPlace"]["value"] ?>
    <br/>

    <h4>Abstract: </h4>
    <?php echo $response["results"]
       ["bindings"][4]
       ["abstract"]["value"] ?>
    <br/>

</body>
</html>
