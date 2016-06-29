<?php
require_once("sparqllib.php");

$db = sparql_connect("http://dbpedia.org/sparql/");
if (!$db) {
    print sparql_errno() . ": " . sparql_error() . "\n";
    exit;
}
sparql_ns("foaf", "http://xmlns.com/foaf/0.1/");

$person = "<http://dbpedia.org/resource/Louis_the_German>";

$sparql = "SELECT ?birthDate ?birthYear ?birthPlace ?house ?deathDate ?deathYear ?deathPlace ?abstract  
where { 
OPTIONAL {" . $person . " dbp:house ?house.}
OPTIONAL {" . $person . " dbo:birthDate ?birthDate.}
OPTIONAL {" . $person . " dbp:birthDate ?birthDate.}
OPTIONAL {" . $person . " dbo:birthYear ?birthYear.}
OPTIONAL {" . $person . " dbp:birthPlace ?birthPlace.}
OPTIONAL {" . $person . " dbo:deathDate ?deathDate.}
OPTIONAL {" . $person . " dbp:deathDate ?deathDate.}
OPTIONAL {" . $person . " dbo:deathYear ?deathYear.}
OPTIONAL {" . $person . " dbo:deathPlace ?deathPlace.}
OPTIONAL {" . $person . " dbo:abstract ?abstract.}


FILTER (lang(?abstract) = 'en')

} LIMIT 1";
$result = sparql_query($sparql);
if (!$result) {
    print sparql_errno() . ": " . sparql_error() . "\n";
    exit;
}

$fields = sparql_field_array($result);

print "<p>Number of rows: " . sparql_num_rows($result) . " results.</p>";
print "<div>";
while ($row = sparql_fetch_array($result)) {
    foreach ($fields as $field) {
        print "<h3>$field</h3>";
        if (strpos($row[$field], 'resource') !== false && strpos($row[$field], 'http://') !== false) {
            $sparql_label = "SELECT ?label
where { <" . $row[$field] . "> <http://www.w3.org/2000/01/rdf-schema#label> ?label FILTER (lang(?label) = 'de') }";
            $result_label = sparql_query($sparql_label);
            if (!$result_label) {
                print sparql_errno() . ": " . sparql_error() . "\n";
                exit;
            }
            
            $fields_label = sparql_field_array($result_label);
            while ($row_label = sparql_fetch_array($result_label)) {
                foreach ($fields_label as $field_label) {
                    print "<p>$row_label[$field_label]</p>";
                }
            }
        } else {
            print "<p>$row[$field]</p>";
        }
        
    }
}
print "</div>";