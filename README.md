
## Zoho Access Token Manager

This project provides a simple way to generate and manage **Zoho OAuth access tokens** using a refresh token.  
It ensures that the access token is always valid by caching it in a JSON file and refreshing it before expiration.

---

### Features
- Generate a new Zoho **Access Token** from a given **Refresh Token**
- Store token details in `access-token.json`
- Automatically reuse valid tokens
- Refresh token **5 minutes before expiry**
- Lightweight and dependency-free (uses only PHP + cURL)

---

### File Structure
```

zoho-access-token-manager/
│
├── AccessTokenManager.php   # Main class to handle token generation & refresh
├── access-token.php         # Example script to get access token
├── access-token.json        # Cached token storage (auto-generated)
└── README.md                # Project documentation

````

---

### Configuration

Before using the project, you **must update your Zoho credentials** in `access-token.php`:

```php
$zoho = new ZohoAccessTokenGenerator(
    client_id: 'YOUR_CLIENT_ID',          // Replace with your Zoho Client ID
    client_secret: 'YOUR_CLIENT_SECRET',  // Replace with your Zoho Client Secret
    refresh_token: '                     ',  // Replace with your Zoho Refresh Token
    token_url: 'https://accounts.zoho.com/oauth/v2/token'
);
````

### ⚠️ Required Changes:

* `client_id` → Get this from **Zoho Developer Console**
* `client_secret` → Provided when you register your client in Zoho
* `refresh_token` → Generate once via Zoho OAuth (long-lived)
* `token_url` → Use `https://accounts.zoho.com/oauth/v2/token` (for global domain)

  * If your account is in **EU**, use: `https://accounts.zoho.eu/oauth/v2/token`
  * For **India**, use: `https://accounts.zoho.in/oauth/v2/token`
  * For **Australia**, use: `https://accounts.zoho.com.au/oauth/v2/token`

---

## Usage

### 1. Run the script

```bash
php access-token.php
```

### 2. Output

If successful, it will print:

```
Access Token: <your_access_token>
```

At the same time, a file `access-token.json` will be created/updated to cache the token.

---

## Example JSON (`access-token.json`)

```json
{
    "access_token": "1000.xxxxx.yyyyy",
    "scope": "ZohoCRM.modules.ALL ZohoBooks.fullaccess.all",
    "api_domain": "https://www.zohoapis.com",
    "token_type": "Bearer",
    "expires_in": 3600,
    "created_at": 1755657124
}
```
