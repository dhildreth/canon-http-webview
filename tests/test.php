<?php

// Autoload files using the Composer autoloader.
require_once __DIR__ . '/../vendor/autoload.php';

use WVHttp\Client;

$client = new Client([
    'base_uri' => 'http://www.foo.com/1.0/',
    'auth' => [
        'auto',
        'otua',
    ],
    'timeout' => 20
]);

// Open new session, setting video resolution initially.
// Note, poorly documented in that you would expect image.cgi
// or video.cgi to handle setting video resolution.
$client->open(['v' => 'jpg:640x480:5', 'p' => '50']);
echo "Session ID: " . $client->getSessionId() . "\n";

// Request camera control.
if(claimControl($client)) {

    // Move the camera to the specified pan, tilt, and zoom positions.
    $client->control([
        'pan' => 15.75 * 100,
        'tilt' => -4.25 * 100,
        'zoom' => 55.8 * 100,
    ]);

    // Give the camera motors a little time to get to the position.
    // Would be nice to use an event instead.
    sleep(3);

    // Then, save the image.
    $client->image('wvhttp_'.date('Y-m-d-H-s').'.jpg');
}

// Yield the camera controls back to other users
// May not be necessary if closing anyways.
$client->yield();

// Close and delete the session.
$client->close();


/**
 * Make an attempt at claiming control over the camera.
 *
 * @param WVClient $client WebView Client object
 * @param int $attempts Number of attempts to make before giving up
 *
 * @return boolean
 */
function claimControl($client, &$attempts = 5) {
    $attempts--;

    if($attempts >= 0) {
        echo "Attempting to claim control (Attempts left: " . ($attempts + 1) . ")\n";

        $claimRes = $client->claim();
        $status = \WVHttp\getValue('s.control', $claimRes);

        if (strpos($status, "enabled") === 0) {
            echo "Control granted.\n";
            return true;
        }
        else {
            echo "Sleeping 1 second.\n";
            sleep(5); // Would be nice to use events from info.cgi
            claimControl($client, $attempts);
        }

    }
    else {
        echo "Unable to obtain control.\n";
        return false;
    }

}
