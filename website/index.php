<script>
function loadBio(dbpedia) {
    var xhttp;
    if (window.XMLHttpRequest) {
        xhttp = new XMLHttpRequest();
        } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xhttp.open("GET", "sparql.php?url=" + encodeURIComponent(dbpedia), false);
    //xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
    
    parser = new DOMParser();
    xmlDoc = parser.parseFromString(xhttp.responseText, "text/xml");
}
</script>

<?php
$xml = simplexml_load_file("index_crop.xml");

//    echo '<p>You searched for ',$_POST['SearchInput'],' with type ',$_POST['SearchType'],'.','</p>';

$document = $xml->xpath("/list/document");

$result = -1;
$bin    =  0;
$pos    =  0;
$elemT  = array();
$elemD  = array();
$elemP  = array();
$elemI  = array();
$elemS  = array();

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

if ($result > 0) {

    echo require_once 'base.html',
    '<section class="search-site">'
    ,require_once 'header.html',
    '<div class="container">
        <div class="row">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-header">SUCHERGEBNIS</div>
                            <div class="panel-body">
                                <table class="table table-striped">
                                <tbody>
                                     <tr>
                                        <td><i class="fa fa-search"></i> </td>
                                        <td><strong>Sie haben gesucht nach </strong></td>
                                        <td> Title: ',$_POST["TitleInput"],', Datum: ', $_POST["DateInput"],', Ort: ' ,$_POST["PlaceInput"], ', Herausgeber: ',$_POST["IssuerInput"],  '  </td>
                                    <tr>
                                        <td><i class="fa fa-sort-numeric-asc"></i></td>
                                        <td><strong>Gefundene Dokumente</strong></td>
                                        <td>',count($elemS),'</td>
                                    </tr>
                                </tbody>
                                </table>
                            <div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    ';


    foreach($elemS as $num) {
        echo
        '<hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-header">Titel: ' ,$document[$num]->title, '</div>
                        <div class="panel-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th></td>
                                    <th>Suche</td>
                                    <th>Ergebnis</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><i class="fa fa-calendar-check-o"></i></td>
                                    <td>Datum</td>
                                    <td>' ,$document[$num]->date,'</td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-map-marker"></i></td>
                                    <td>Ort</td>
                                    <td>' ,$document[$num]->place, '</td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-user"></i></i></td>
                                    <td>Autor</td>
                                    <td>';
                                    if(isset($document[$num]->issuer['resource'])) {
                                        echo '<a onclick="', "loadBio('", $document[$num]->issuer['resource'], "')", '">', $document[$num]->issuer, '</a>';
                                    } else {
                                        echo $document[$num]->issuer;
                                    }
                                    echo '</td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-file-text"></i></td>
                                    <td>Abstract</td>
                                    <td>' ,$document[$num]->abstract, '</td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-external-link"></i></td>
                                    <td>Ressource</td>';
                                    foreach($document[$num]->resource as $res) {
                                        echo '<td> <a href="',$document[$num]->resource, '" target="_blank">',$document[$num]->resource,'</td>' ;
                                    }
                                echo
                                '</tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>';
    }
    echo
    '</div>
    </section>';
} elseif ($result == 0) {
    echo include_once 'base.html',
    '<section class="search-site">'
    ,include_once 'header.html',
    '<div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-header">
                                SUCHERGEBNIS
                            </div>
                            <div class="panel-body" style="text-align: center;padding-bottom: 50px;padding-top: 50px;">
                                <span class="ndf">ES WURDEN KEINE DOKUMENTE GEFUNDEN</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>';
}

unset($document);
unset($pos);

?>
