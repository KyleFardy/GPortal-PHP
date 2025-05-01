# GPortal-PHP

A Lightweight PHP Wrapper To Authenticate And Retrieve Server Information From GPortal (EU/US Regions).

## 🖼️ Screenshot (Click To Reveal)

<details>
  <summary>📷 View Preview</summary>
  <img src="https://cdn.void-hosting.cloud/raw/brave_BgKUnK6L6N.png" alt="GPortal-PHP Preview">
</details>

## ✨ Features

- Login Using GPortal Account Credentials  
- Token Management (Validation & Refresh)  
- Fetch Server List From GPortal (EU & US)  
- Session-Based Access Token Storage  
- Simple Logging For Debugging

## 🚀 Installation

Just Include The Class File In Your Project:

```php
include "gportal.class.php";
$gportal = new GPORTAL_AUTH();

try {
    // Check If Already Logged In
    if (!$gportal->checkLoginStatus()) {
        if (!$gportal->login('your@email.com', 'yourPassword')) {
            throw new Exception("Login Failed");
        }
    } elseif (!$gportal->isTokenValid()) {
        // Attempt To Refresh Expired Token
        $gportal->refreshToken();
    }

    echo "Logged In Successfully";

    // Example: Fetch Servers
    $servers = $gportal->fetchServers();
    print_r($servers);

} catch (Exception $e) {
    $gportal->log("GPORTAL Error: " . $e->getMessage());
    echo "Authentication Failed: " . $e->getMessage();
}
```
