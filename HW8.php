<?php

$query = "Prashant";
$stype = "User";
$dis = "";
$lat = "";
$long = "";
$id = "";
$offset = 10;

if(isset($_POST['searchQuery'])) {
    $query = $_POST['searchQuery'];
}

if(isset($_POST['stype']))
{
    $stype = $_POST['stype'];
}

if(isset($_POST['distance']))
{
    $dis = $_POST['distance'];
}

if(isset($_POST['long']))
{
    $long = $_POST['long'];
}

if(isset($_POST['lat']))
{
    $lat = $_POST['lat'];
}

if(isset($_POST['id']))
{
    $id = $_POST['id'];
}

if(isset($_POST['offset'])){
    $offset = $_POST['offset'];
}

//print_r($_REQUEST);
date_default_timezone_set("America/Los_Angeles");
require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
$fb = new Facebook\Facebook([
    'app_id' => '509778816077645',
    'app_secret' => 'd4c30bf9fb90d0e270537ea15a166c9a',
    'default_graph_version' => 'v2.5'
]);

$fb->setDefaultAccessToken('EAAHPpCEZCD00BABeWSZAPPhNAjg1ZCnUlIj1UKd3dZAZCYZCqaivdb9NvhwiOZCI9mUa6nUe57UxUpH5YDGIt2Dhq5Pziop6QdlWneW3hh8aXk9lZCB9MTFnZB2AAugnZA9iuc0LatynUiz1RYeLZBKdlLZC');

try{
    $user = $fb->get('/me');
    $user = $user->getGraphNode()->asArray();
}
catch (Facebook\Exceptions\FacebookResponseException $e) {
//    echo 'Graph return an error: ' . $e->getMessage();
}
catch (Facebook\Exceptions\FacebookSDKException $e) {
//    echo 'Facebook SDK returned an error: ' . $e->getMessage();
}

try {
    if(!$id) {
        if ($stype != "Places") {
            try {
                $response = $fb->get('/search?offset=' . $offset . '&limit=25&q=' . $query . '&type=' . $stype . '&fields=id,name,picture.width(700).height(700)');
            } catch (Facebook\Exceptions\FacebookResponseException $e) {
//                echo 'Graph return an error: ' . $e->getMessage();
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
//                echo 'Facebook SDK returned an error: ' . $e->getMessage();
            }

            $search = $response->getDecodedBody();
            echo json_encode($search);
        } else if ($stype == "Place") {
            try {
                if ($lat != "" && $long != "" && $dis != "") {
                    $response = $fb->get('/search?offset=' . $offset . '&limit=25&q=' . $query . '&type=' . $stype . '&center=' . $lat . ',' . $long . '&distance=' . $dis . '&fields=id,name,picture.width(700).height(700)');
                } else if ($lat != "" && $long != "") {
                    $response = $fb->get('/search?offset=' . $offset . '&limit=25&q=' . $query . '&type=' . $stype . '&center=' . $lat . ',' . $long . '&fields=id,name,picture.width(700).height(700)');
                } else {
                    $response = $fb->get('/search?offset=' . $offset . '&limit=25&q=' . $query . '&type=' . $stype . '&fields=id,name,picture.width(700).height(700)');
                }
            } catch (Facebook\Exceptions\FacebookResponseException $e) {
//                echo 'Graph return an error: ' . $e->getMessage();
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
//                echo 'Facebook SDK returned an error: ' . $e->getMessage();
            }

            $search = $response->getDecodedBody();
            echo json_encode($search);
        }
    }
    else {
        
        try {
//            $response = $fb->get('/' . $id . '? fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name, picture}},posts.limit(5)');
            $response = $fb->get('/' . $id . '? fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name, picture}},posts.limit(5)');
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
//            echo 'Graph return an error: ' . $e->getMessage();
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
//            echo 'Facebook SDK returned an error: ' . $e->getMessage();
        }
        $search = $response->getDecodedBody();
        echo json_encode($search);
    }
}
catch (Facebook\Exceptions\FacebookAuthenticationException $e) {
//        echo 'Request returns an error: ' . $e->getMessage();
    }
?>