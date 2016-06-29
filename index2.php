<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Regesta Imperii</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/skeleton.css">
</head>
<body>
  <div class="container">
    <form action="index2.php" method="post">
      <div class="row">
        <div class="six columns">
          <h4>Regesta Imperii</h4>
        </div>
      </div>
      <div class="row">
        <div class="eight columns">
          <label for="SearchInput">Search</label>
          <input class="u-full-width" placeholder="Try with 'ri' as <title>" id="SearchInput" name="SearchInput" type="text">
        </div>
        <div class="four columns">
          <label for="SearchType">Type</label>
            <select class="u-full-width" id="SearchType" name="SearchType">
              <option value="OptTitle">&lt;title&gt;</option>
              <option value="OptDate">&lt;date&gt;</option>
              <option value="OptPlace">&lt;place&gt;</option>
              <option value="OptIssuer">&lt;issuer&gt;</option>
            </select>
        </div>
      </div>
      <!--<label for="exampleMessage">Message</label>
      <textarea class="u-full-width" placeholder="Some Text" id="exampleMessage"></textarea>
      <label class="example-send-yourself-copy">
        <input type="checkbox">
        <span class="label-body">Just a Checkbox</span>
      </label>-->
      <input class="button-primary" value="Submit" type="submit">
    </form>
<?php
$xml = simplexml_load_file("index_crop.xml");

echo '<p>You searched for ',$_POST['SearchInput'],' with type ',$_POST['SearchType'],'.','</p>';

$title    = $xml->xpath("/list/document/title");
$date     = $xml->xpath("/list/document/date");
$place    = $xml->xpath("/list/document/place");
$issuer   = $xml->xpath("/list/document/issuer");
$abstract = $xml->xpath("/list/document/abstract");
$resource = $xml->xpath("/list/document/resource");

//while(list( , $node) = each($title)) {
//    echo 'teiHeader/fileDesc/titleStmt/title: ',$node,"\n";
//}

$result = 0;
$pos    = 0;
$elem   = array();

//print_r($title);

if (strcmp($_POST['SearchType'], "OptTitle") == 0) {
    while(list( , $node) = each($title)) {
        if (stripos($node, $_POST['SearchInput']) > -1) {
            $result = 1;
            $elem[] = $pos;
            //echo 'teiHeader/fileDesc/titleStmt/title: ',$node,"\n";
        }
        $pos += 1;
    }
}

if (strcmp($_POST['SearchType'], "OptDate") == 0) {
    while(list( , $node) = each($date)) {
        if (stripos($node, $_POST['SearchInput']) > -1) {
            $result = 1;
            $elem[] = $pos;
        }
        $pos += 1;
    }
}

if (strcmp($_POST['SearchType'], "OptPlace") == 0) {
    while(list( , $node) = each($place)) {
        if (stripos($node, $_POST['SearchInput']) > -1) {
            $result = 1;
            $elem[] = $pos;
        }
        $pos += 1;
    }
}

if (strcmp($_POST['SearchType'], "OptIssuer") == 0) {
    while(list( , $node) = each($issuer)) {
        if (stripos($node, $_POST['SearchInput']) > -1) {
            $result = 1;
            $elem[] = $pos;
        }
        $pos += 1;
    }
}

if ($result > 0) {
    echo '<p>',count($elem),' Document(s) found.</p>';
    //     '<p>title: ',$title[0],
    //     '<br>publisher: ',$publisher[0],
    //     '<br>idno: ',$idno[0],
    //     '<br>persName: ',$persName[0],
    //     '</p>';
    while(list( , $num) = each($elem)) {
        echo '<hr>',
               '<p><b>title:</b> ',$title[$num],
               '<br><b>date:</b> ',$date[$num],
               '<br><b>place:</b> ',$place[$num],
               '<br><b>issuer:</b> ',$issuer[$num],
               '<br><b>abstract:</b> ',$abstract[$num],
               '<br><b>resource:</b> <a href="',$resource[$num],'" target="_blank">',$resource[$num],'</a>',
               '</p>';
    }         
} else {
    echo '<p>No Document found.</p>';
}

//while(list( , $node) = each($result)) {
//    echo 'teiHeader/fileDesc/titleStmt/title: ',$node,"\n";
//}
unset($title);
unset($date);
unset($place);
unset($issuer);
unset($abstract);
unset($pos);

?>
  </div>
</body>
</html>
