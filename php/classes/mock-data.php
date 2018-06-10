<?php
use Edu\Cnm\FoodTruck\{
    Category, Favorite, Profile,Truck,TruckCategory,Vote
};


// grab the class under scrutiny
require_once(dirname(__DIR__) . "/classes/autoload.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

require_once("uuid.php");


//create profile objects and insert them into the data base

//profile 1
$password1 = "abc123";
$hash = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);
$profile1 = new Profile(generateUuidV4(), bin2hex(random_bytes(16)),"g@yahoo.com", $hash, 1, G, Cordova, Gcordova);
$profile->insert($pdo);
echo "first profile";
var_dump($profile->getProfileId()->toString());

//profile 2
$password2 = "abc123";
$hash = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);
$profile2 = new Profile(generateUuidV4(), bin2hex(random_bytes(16)), "yvette@yahoo.com", $hash, "cats",1, Johnson);
$profile2->insert($pdo);
echo "second profile";
var_dump($profile2->getProfileId()->toString());

//profile 3
$password3 = "abc123";
$hash = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);
$profile3 = new Profile(generateUuidV4(), bin2hex(random_bytes(16)), "batman@yahoo.com", $hash, "meow", -1, Escobar);
$profile3->insert($pdo);
echo "third profile";
var_dump($profile3->getProfileId()->toString());



//create truck objects

$truck1 = new Truck(generateUuidV4(), generateUuidV4(), "stuff about this truck", 1, 4.4,1.7, "taco tuesday truck", "505-987-6547", "tacotruck.com");

$truck2 = new Truck(generateUuidV4(), generateUuidV4(), "more stuff about this truck", 1, 7.4,7.7, "marios pizza truck", "505-987-5447", "pizzatruck.com");

$truck3 = new Truck(generateUuidV4(), generateUuidV4(), " random stuff about this truck", 1, 6.4,9.7, "dereks pizza truck", "505-987-5227", "derekspizzatruck.com");



// create truck category objects
$truckCategory1 = new TruckCategory(6, generateUuidV4());
$truckCategory1->insert($pdo);
echo "first truck-category";

$truckCategory2 = new TruckCategory(7, generateUuidV4());
$truckCategory2->insert($pdo);
echo "second truck-category";

$truckCategory3 = new TruckCategory(8, generateUuidV4());
$truckCategory3->insert($pdo);
echo "third truck-category";
