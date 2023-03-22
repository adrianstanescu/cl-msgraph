# MSGraphTools

## Development 

1. `composer install`
1. Set required env vars
    - `AZURE_CLIENT_ID`
    - `AZURE_CLIENT_SECRET`
    - `AZURE_TENANT_ID`
    - `TEST_USER_ID`
    - `TEST_USER_EMAIL`
 
    a) `cp phpunit.xml.dist phpunit.xml` and fill values

    b) through any other means   

1. `composer run download-product-names`
1. `composer run demo`
1. `x-www-browser http://localhost:8006/`

Try to use https://www.conventionalcommits.org/

## Usage

```
use Adrian\CLMSGraph\Graph;
use Adrian\CLMSGraph\User;

Graph::configure(
    getenv('AZURE_CLIENT_ID'),
    getenv('AZURE_CLIENT_SECRET'),
    getenv('AZURE_TENANT_ID'),
);

foreach (User::all() as $user) {
    foreach ($user->calendars() as $calendar) {
        foreach ($calendar->events() as $event) {
            print $event->getSubject() . "\n";
        }
    }
}
```

## Application Permissions used

- `Directory.ReadWrite.All`
- `Organization.ReadWrite.All`
- `Calendars.ReadWrite`
