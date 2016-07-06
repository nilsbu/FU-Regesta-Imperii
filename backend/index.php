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
    <form action="index.php" method="post">
      <div class="row">
        <div class="six columns">
          <h4>Regesta Imperii</h4>
        </div>
      </div>
      <!--<div class="row">
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
      </div>-->
      <div class="row">
        <label>Search</label>
<?php
        echo '<input class="u-full-width" placeholder="<title>" id="TitleInput" name="TitleInput" type="text" value="',$_POST['TitleInput'],'">
        <input class="u-full-width" placeholder="<date>" id="DateInput" name="DateInput" type="text" value="',$_POST['DateInput'],'">
        <input class="u-full-width" placeholder="<place>" id="PlaceInput" name="PlaceInput" type="text" value="',$_POST['PlaceInput'],'">
        <input class="u-full-width" placeholder="<issuer>" id="IssuerInput" name="IssuerInput" type="text" value="',$_POST['IssuerInput'],'">';
?>
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
$xml = simplexml_load_file("index_full.xml");

//echo '<p>You searched for ',$_POST['SearchInput'],' with type ',$_POST['SearchType'],'.','</p>';

$document = $xml->xpath("/list/document");
/*
$title    = $xml->xpath("/list/document/title");
$date     = $xml->xpath("/list/document/date");
$place    = $xml->xpath("/list/document/place");
$issuer   = $xml->xpath("/list/document/issuer");
$abstract = $xml->xpath("/list/document/abstract");
$resource = $xml->xpath("/list/document/resource");
*/
/*
echo '<p>Documents: ',count($document),'</p>';
echo '<p>Titles: ',count($title),
    '<br>Dates: ',count($date),
    '<br>Places: ',count($place),
    '<br>Issuers: ',count($issuer),
    '<br>Abstracts: ',count($abstract),
    '<br>Ressources: ',count($resource),'</p>';
*/
//while(list( , $node) = each($title)) {
//    echo 'teiHeader/fileDesc/titleStmt/title: ',$node,"\n";
//}

$result = -1;
$bin    =  0;
$pos    =  0;
$elemT  = array();
$elemD  = array();
$elemP  = array();
$elemI  = array();
$elemS  = array();

//print_r($title);

if (strlen($_POST['TitleInput']) > 0) {
    $bin += 8;
    $result = 0;
    $pos  = 0;
    foreach($document as $node) {
        if (stripos($node->title, $_POST['TitleInput']) > -1) {
            $result = 1;
            $elemT[] = $pos;
            //echo 'teiHeader/fileDesc/titleStmt/title: ',$node,"\n";
        }
        $pos += 1;
    }
}

if (strlen($_POST['DateInput']) > 0) {
    $bin += 4;
    $result = 0;
    $pos  = 0;
    foreach($document as $node) {
        if (stripos($node->date, $_POST['DateInput']) > -1) {
            $result = 1;
            $elemD[] = $pos;
        }
        $pos += 1;
    }
}

if (strlen($_POST['PlaceInput']) > 0) {
    $bin += 2;
    $result = 0;
    $pos  = 0;
    foreach($document as $node) {
        if (stripos($node->place, $_POST['PlaceInput']) > -1) {
            $result = 1;
            $elemP[] = $pos;
        }
        $pos += 1;
    }
}

if (strlen($_POST['IssuerInput']) > 0) {
    $bin += 1;
    $result = 0;
    $pos  = 0;
    foreach($document as $node) {
        if (stripos($node->issuer, $_POST['IssuerInput']) > -1) {
            $result = 1;
            $elemI[] = $pos;
        }
        $pos += 1;
    }
}

switch ($bin) {
    case 1:
        $elemS = $elemI;
        break;
    case 2:
        $elemS = $elemP;
        break;
    case 3:
        $elemS = array_intersect($elemI, $elemP);
        break;
    case 4:
        $elemS = $elemD;
        break;
    case 5:
        $elemS = array_intersect($elemI, $elemD);
        break;
    case 6:
        $elemS = array_intersect($elemP, $elemD);
        break;
    case 7:
        $elemS = array_intersect($elemI, $elemP, $elemD);
        break;
    case 8:
        $elemS = $elemT;
        break;
    case 9:
        $elemS = array_intersect($elemI, $elemT);
        break;
    case 10:
        $elemS = array_intersect($elemP, $elemT);
        break;
    case 11:
        $elemS = array_intersect($elemI, $elemP, $elemT);
        break;
    case 12:
        $elemS = array_intersect($elemD, $elemT);
        break;
    case 13:
        $elemS = array_intersect($elemI, $elemD, $elemT);
        break;
    case 14:
        $elemS = array_intersect($elemP, $elemD, $elemT);
        break;
    case 15:
        $elemS = array_intersect($elemI, $elemP, $elemD, $elemT);
        break;
}

/*
if (strlen($_POST['TitleInput']) > 0) {
    $elemS = array_intersect($elemS, $elemT);
}

if (strlen($_POST['DateInput']) > 0) {
    $elemS = array_intersect($elemS, $elemD);
}

if (strlen($_POST['PlaceInput']) > 0) {
    $elemS = array_intersect($elemS, $elemP);
}

if (strlen($_POST['IssuerInput']) > 0) {
    $elemS = array_intersect($elemS, $elemI);
}
*/

/*
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
*/

if ($result > 0) {
    $pos = 5;
    echo '<p>',count($elemS),' Document(s) found.</p>';
    // TODO nav
    //     '<p>title: ',$title[0],
    //     '<br>publisher: ',$publisher[0],
    //     '<br>idno: ',$idno[0],
    //     '<br>persName: ',$persName[0],
    //     '</p>';
    //while(list( , $num) = each($elemS)) {
    foreach($elemS as $num) {
        echo '<hr>';
        if ($pos % 5 == 0) {
            echo '<section id="', ($pos / 5), '">';
        }
        echo   '<p><b>title:</b> ',$document[$num]->title,
               '<br><b>date:</b> ',$document[$num]->date,
               '<br><b>place:</b> ',$document[$num]->place,
               '<br><b>issuer:</b> ',$document[$num]->issuer,
               '<br><b>abstract:</b> ',$document[$num]->abstract,
               '<br><b>resource:</b> <a href="',$document[$num]->resource,'" target="_blank">',$document[$num]->resource,'</a>',
               '</p>';
        if ($pos % 5 == 4) {
            echo '</section>';
        }
        $pos += 1;
    }
} elseif ($result == 0) {
    echo '<p>No Documents found.</p>';
}

//while(list( , $node) = each($result)) {
//    echo 'teiHeader/fileDesc/titleStmt/title: ',$node,"\n";
//}
unset($document);
/*
unset($title);
unset($date);
unset($place);
unset($issuer);
unset($abstract);
*/
unset($pos);

?>
  </div>
</body>
</html>
