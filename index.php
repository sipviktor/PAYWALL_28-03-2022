<?php

$url = "http://payway.bubileg.cz/api/echo";

$signature = "";
$date = date("YmdHis");
$bpb = "-----BEGIN PUBLIC KEY-----
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAK9i4eHStEr9M/Iix2WbQvB+i71H/eb6
da9M+/HvIBXywE+Q+bpTq2IGNK+EMWvVsQ0wNfLiBVez+vzA4r6JdC8CAwEAAQ==
-----END PUBLIC KEY-----";
$pk = "-----BEGIN PRIVATE KEY-----
MIIBVgIBADANBgkqhkiG9w0BAQEFAASCAUAwggE8AgEAAkEAw0ymVf8ZxsnkUOiw
PV1jyhrVEKRNDnRto3f7jZQu9xGK54Cb9HondXz+wdWO1tWo1Zpj6bjnQ6DBTLGY
JBRqoQIDAQABAkBOcfdOC416/5Upuo0v2NdAUs7KRHR/Hdz8EDGGiMLkkf8XtcjX
p2SWQqnDjGWfPWp7+wrQM70GRgz75Wr/jGHBAiEA8lYXRWg97dAvtEVPTwwNK81d
kRjIKLHHswOkgRq++k0CIQDOT5+PIhNC40bVVmGsA9vODAjxXCNhTqNNrFbQ4/7z
pQIhAN7wKnp0urxEEQ7T0HFVEezhAhoQ5VriqmfaiwUmmNjlAiEAnY+buRF6ClRO
7XEKKPfNb6ArWEFY1+DZwOFEcEYfmO0CIQDaKfFas5jaERWsirI9m+7g60p5jeDy
259d6rWw8F41qA==
-----END PRIVATE KEY-----";
$data = array(
    'merchantId' => "b9af2028",
    'dttm' => $date
);
openssl_sign($data["merchantId"] . "|" . $data["dttm"], $signature, $pk);
$data["signature"] = base64_encode($signature);

$content = curl_init($url);
curl_setopt($content, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($content, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($content, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($content);
curl_close($content);

echo $result;

$result = json_decode($result, true);
$pb = openssl_get_publickey($bpb);
$ok = openssl_verify($result["resultCode"] . "|" . $result["resultMessage"] . "|" . $result["dttm"], base64_decode($result["signature"]), $pb);

echo $ok;
var_dump($ok);