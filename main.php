<?php

use Amazon\AmazonApi;
use Amazon\Entity\Item;
use Amazon\Entity\Package;
use Amazon\Entity\PackageDimension;
use App\ShippingService;
use FBA\Entity\Buyer;
use FBA\Entity\Order;
use GuzzleHttp\Client;

include "./vendor/autoload.php";


$buyerData = json_decode(file_get_contents('Mock/buyer.29664.json'), true);
$orderData = json_decode(file_get_contents('Mock/order.16400.json'), true);


$buyer = new Buyer();
$buyer->name = $buyerData['shop_username'];
$buyer->email = $buyerData['email'];

$customer = new Buyer();


$order = new Order(1);

$items = [];
foreach ($orderData['products'] as $product) {
    $item = new Item();
    $items[] = $item;
}

$packageDimension = new PackageDimension();

$package = new Package();
$package->dimension = $packageDimension;

$order->setProducts($items);
$order->setPackage($package);
$order->setDateCreate(new DateTime());
$order->setShipFrom($customer);



$c = new Client();
$api = new AmazonApi($c, 'https://sellingpartnerapi-eu.amazon.com/shipping/v2/shipments', [
    AmazonApi::BUSINESS_ID => 'test'
]);

$s = new ShippingService($api);

$trackingNumber = '';

try {
    $trackingNumber = $s->ship($order, $buyer);
} catch (Exception $exception) {
    echo "Amazon api service is unavailable now. Error: " . $exception->getMessage();
}

echo $trackingNumber . PHP_EOL;

