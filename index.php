<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>GPortal Info</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body data-bs-theme="dark">
    <?php
    include "includes/classes/gportal.class.php";

    $gportal = new GPORTAL_AUTH();

    try {
        if (!$gportal->checkLoginStatus()) {
            if (!$gportal->login('your@email.com', 'yourPassword')) {
                throw new Exception("Login Failed");
            }
        } elseif (!$gportal->isTokenValid()) {
            if (!$gportal->refreshToken()) {
                throw new Exception("Token Refresh Failed");
            }
        }
        echo '<div class="container mt-4">';
        echo '<div class="alert alert-success"><h4 class="mb-0">Successfully Logged In</h4></div>';
        $euUser = $gportal->getUser("EU");
        $usUser = $gportal->getUser("US");

        echo '<h5 class="mt-4">EU Account</h5>';
        echo '<table class="table table-bordered table-striped">';
        if ($euUser && isset($euUser['username'], $euUser['email'])) {
            echo '<tr><th>Username</th><td>' . $euUser['username'] . '</td></tr>';
            echo '<tr><th>Email</th><td>' . $euUser['email'] . '</td></tr>';
        } else {
            echo '<tr><td colspan="2">Failed To Retrieve EU User Information</td></tr>';
        }
        echo "</table>";

        echo '<h5 class="mt-4">US Account</h5>';
        echo '<table class="table table-bordered table-striped">';
        if ($usUser && isset($usUser['username'], $usUser['email'])) {
            echo "<tr><th>Username</th><td>" . $usUser['username'] . "</td></tr>";
            echo "<tr><th>Email</th><td>" . $usUser['email'] . "</td></tr>";
        } else {
            echo "<tr><td colspan='2'>Failed To Retrieve US User Information</td></tr>";
        }
        echo "</table>";


        $servers = $gportal->fetchServers();
        if ($servers) {
            echo '<h5 class="mt-4">Servers</h5>';
            echo '<table class="table table-bordered table-hover table-striped">';
            echo '<thead class="table-dark"><tr>
                <th>Region</th>
                <th>Server Name</th>
                <th>Server ID</th>
                <th>Service ID</th>
              </tr></thead><tbody>';

            foreach ($servers as $server) {
                $status = $gportal->fetchStatus($server['serviceId'], $server['region']);

                $hostname = 'Unknown';
                if (
                    isset($status['data']['cfgContext']['ns']['profile']['publicConfigs']) &&
                    is_string($status['data']['cfgContext']['ns']['profile']['publicConfigs'])
                ) {
                    $config = json_decode($status['data']['cfgContext']['ns']['profile']['publicConfigs'], true);
                    if (isset($config['server']['server.hostname'])) {
                        $hostname = $gportal->formatHostname($config['server']['server.hostname']);
                    }
                }
                echo "<tr>
                    <td>{$server['region']}</td>
                    <td>{$hostname}</td>
                    <td>{$server['serverId']}</td>
                    <td>{$server['serviceId']}</td>
                  </tr>";
            }

            echo '</tbody></table>';
            echo '</div>';
        }
    } catch (Exception $e) {
        $gportal->log("GPORTAL Error: " . $e->getMessage());
        echo "Authentication Failed: " . $e->getMessage();
    }
    ?>

</body>

</html>
