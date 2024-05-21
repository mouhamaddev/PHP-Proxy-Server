# PHP Proxy Server

This PHP script serves as a simple proxy server for forwarding HTTP requests to a specified backend URL :)
It supports GET, POST, PUT, and OPTIONS methods and allows customization of allowed headers.

## Usage

1. Clone the repository or copy the `proxy.php` file to your server.
2. Configure the `$backend_url` variable in `proxy.php` to point to your desired backend URL.
3. Access the proxy server by making requests to `proxy.php` with the desired endpoint appended to the URL.
