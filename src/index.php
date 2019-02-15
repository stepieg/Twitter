<?php

include('./header.php');

$testUser = new User();

$testUser->setEmail('test@o2.pl')->setHashPass('haslo')->setUserName('Nowak')->saveToDB($conn);

var_dump($testUser);

$testUser->setUserName('Kowalski')->setHashPass('noweHaslo')->setEmail('aaa@onet.pl')->saveToDB($conn);

var_dump($testUser);

include('./footer.php');