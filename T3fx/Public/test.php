<pre><?php


$content = explode(PHP_EOL, file_get_contents('header.txt'));
foreach ($content as $header) {
    echo $header . PHP_EOL;
    getIp($header);
}


function getIp($header)
{

    if (preg_match('/\([^[:space:]]+ ([a-z0-9\-\.]+)\)/i', $header, $hits)) {
        $ipv4 = gethostbyname($hits[1]);
    } elseif (preg_match('/by\s+([a-z0-9\-\.]+\.[a-z0-9\-]+)/i', $header, $hits)) {
        $ipv4 = gethostbyname($hits[1]);

    } elseif (preg_match('/[0-9]{1,3}(\.[0-9]{1,3}){3}/i', $header, $hits)) {
        $ipv4 = $hits[0];

    } else {
        echo "Could not fine the IP in '" . $header . "'";
        return false;
    }

    var_dump($ipv4);
}
