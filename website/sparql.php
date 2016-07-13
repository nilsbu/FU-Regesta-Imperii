<?php
require_once("sparqllib.php");

$db = sparql_connect("http://dbpedia.org/sparql/");
if (!$db) {
    print sparql_errno() . ": " . sparql_error() . "\n";
    exit;
}

sparql_ns("foaf", "http://xmlns.com/foaf/0.1/");

$person = "<" . $_GET["url"] . ">";

$sparql = "SELECT ?birthDate ?birthYear   ?deathDate ?deathYear  ?abstract
where {

OPTIONAL {" . $person . " dbo:birthDate ?birthDate.}
OPTIONAL {" . $person . " dbp:birthDate ?birthDate.}
OPTIONAL {" . $person . " dbo:birthYear ?birthYear.}

OPTIONAL {" . $person . " dbo:deathDate ?deathDate.}
OPTIONAL {" . $person . " dbp:deathDate ?deathDate.}
OPTIONAL {" . $person . " dbo:deathYear ?deathYear.}

OPTIONAL {" . $person . " dbo:abstract ?abstract.}


FILTER (lang(?abstract) = 'en')

} LIMIT 1";
$result = sparql_query($sparql);
if (!$result) {
    echo sparql_errno() . ": " . sparql_error() . "\n";
    exit;
}

$fields = sparql_field_array($result);

echo "<bio>";
while ($row = sparql_fetch_array($result)) {
    foreach ($fields as $field) {
        echo "<$field>";
        if (strpos($row[$field], 'resource') !== false && strpos($row[$field], 'http://') !== false) {
            $sparql_label = "SELECT ?label
where { <" . $row[$field] . "> <http://www.w3.org/2000/01/rdf-schema#label> ?label FILTER (lang(?label) = 'de') }";
            $result_label = sparql_query($sparql_label);
            if (!$result_label) {
                //print sparql_errno() . ": " . sparql_error() . "\n";
                exit;
            }

            $fields_label = sparql_field_array($result_label);
            while ($row_label = sparql_fetch_array($result_label)) {
                foreach ($fields_label as $field_label) {
                    echo "</$field>$row_label[$field_label]<$field>";
                }
            }
        } else {
            echo $row[$field];
        }
        echo "</$field>";
    }
}
echo "</bio>";