<?php
use OAuth\OAuth1\Service\Twitter;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;
use OAuth\ServiceFactory;

/**
 * Bootstrap the example
 */
require_once 'OAuth/bootstrap.php';
// We need to use a persistent storage to save the token, because oauth1 requires the token secret received before'
// the redirect (request token request) in the access token request.
$storage = new Session();
// Setup the credentials for the requests
$credentials = new Credentials(
    '9rVewCYLqJjDv23kBU1UEZToS',
    'YQJUcUOX01zop04dvTs6LMdgyvcpijSSxHrJIyokMoYhAbPyHb',
    'http://127.0.0.1/social/callback.php'
);
$serviceFactory = new ServiceFactory();

// Instantiate the twitter service using the credentials, http client and storage mechanism for the token
/** @var $twitterService Twitter */
$twitterService = $serviceFactory->createService('twitter', $credentials, $storage);
if (!empty($_GET['oauth_token'])) {
    $token = $storage->retrieveAccessToken('Twitter');
    // This was a callback request from twitter, get the token
    $twitterService->requestAccessToken(
        $_GET['oauth_token'],
        $_GET['oauth_verifier'],
        $token->getRequestTokenSecret()
    );
    // Send a request now that we have access token
    $result = json_decode($twitterService->request('account/verify_credentials.json'));
    echo 'result: <pre>' . print_r($result, true) . '</pre>';
} elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
    // extra request needed for oauth1 to request a request token :-)
    $token = $twitterService->requestRequestToken();
    $url = $twitterService->getAuthorizationUri(array('oauth_token' => $token->getRequestToken()));
    header('Location: ' . $url);
} else {
    $url = $currentUri->getRelativeUri() . '?go=go';
    echo "<a href='$url'>Login with Twitter!</a>";
}
?>