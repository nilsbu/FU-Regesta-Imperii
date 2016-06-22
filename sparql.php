<?php
require_once( "sparqllib.php" );
 
$db = sparql_connect( "http://dbpedia.org/sparql/" );
if( !$db ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }
sparql_ns( "foaf","http://xmlns.com/foaf/0.1/" );

$person = "<http://dbpedia.org/resource/Charlemagne>";
 
$sparql = "SELECT ?house ?birthDate ?birthPlace ?deathDate ?deathPlace ?abstract
where { ".$person." dbp:house ?house.
".$person." dbo:birthDate ?birthDate.
".$person." dbo:deathDate ?deathDate.
".$person." dbo:deathPlace ?deathPlace.
".$person." dbo:abstract ?abstract.

".$person." dbp:birthPlace ?birthPlace

FILTER (lang(?abstract) = 'de')

} LIMIT 1";
$result = sparql_query( $sparql ); 
if( !$result ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }
 
$fields = sparql_field_array( $result );
 
print "<p>Number of rows: ".sparql_num_rows( $result )." results.</p>";
print "<table class='example_table'>";
print "<tr>";
foreach( $fields as $field )
{
	print "<th>$field</th>";
}
print "</tr>";
while( $row = sparql_fetch_array( $result ) )
{
	print "<tr>";
	foreach( $fields as $field )
	{
		print "<td>$row[$field]</td>";
	}
	print "</tr>";
}
print "</table>";