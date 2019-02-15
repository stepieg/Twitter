<?php

include('./header.php');

$testUser = new User();

$testUser->setEmail('test@o22.pl')->setHashPass('haslo')->setUserName('Nowak')->saveToDB($conn);

var_dump($testUser);

$testUser->setUserName('Kowalski')->setHashPass('noweHaslo')->setEmail('aaaa@onet.pl')->saveToDB($conn);

var_dump($testUser);

sleep(60);

$testUser->delete($conn);

include('./footer.php');