# Pre-requisites
- A dynamically assigned IP address
- [Composer](https://getcomposer.org/)
- PHP 5.5.9 (Haven't tested any others)
- A fancy domain
- A Digial Ocean account

# Dependencies
- [guzzle/guzzle](https://github.com/guzzle/guzzle)
- [toin0u/digitalocean-v2](https://github.com/toin0u/DigitalOceanV2)

# Installation
1. Clone this repository: ```git clone https://github.com/tjbenator/do-dns.git```
2. Change directories: ```cd do-dns```
3. Install dependencies: ```composer install```
4. Copy ```config.example.php``` to ```config.php``` and update it's values.
5. Setup crontab. ```crontab -e``` Example: ```0 * * * * /usr/bin/php /home/travis/do-dns/do-dns.php 1>/dev/null 2>/home/travis/do-dns/do-dns.log```
