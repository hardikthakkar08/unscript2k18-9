<?php
use OAuth\OAuth2\Service\Instagram;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;
use OAuth\ServiceFactory;

/**
 * Bootstrap the example
 */
require_once 'OAuth/bootstrap.php';
// Session storage
$storage = new Session();
// Setup the credentials for the requests
$credentials = new Credentials(
    '7fd049cb0cdc43c1bf22e52480d313e5',
    '0d1bf7aaf9ce43199751abfaa4087145',
    'http://localhost/social/dashboard.php'
);
$scopes = array('basic', 'comments', 'relationships', 'likes');
// Instantiate the Instagram service using the credentials, http client and storage mechanism for the token
/** @var $instagramService Instagram */
$serviceFactory = new ServiceFactory();

$instagramService = $serviceFactory->createService('instagram', $credentials, $storage, $scopes);
if (!empty($_GET['code'])) {
    // retrieve the CSRF state parameter
    $state = isset($_GET['state']) ? $_GET['state'] : null;
    // This was a callback request from Instagram, get the token
    $instagramService->requestAccessToken($_GET['code'], $state);
    // Send a request with it
    $result = json_decode($instagramService->request('users/self'), true);
    // Show some of the resultant data
    echo 'Your unique instagram user id is: ' . $result['data']['id'] . ' and your name is ' . $result['data']['full_name'];
    $result = json_decode($instagramService->request('users/self/media/recent'), true);
    print_r($result);
    print_r($result['data'][0]);
} elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
    $url = $instagramService->getAuthorizationUri();
    header('Location: ' . $url);
} else {
    $url =  'http://localhost/social/dashboard.php'. '?go=go';
    echo "<a href='$url'>Login with Instagram!</a>";
}
?>