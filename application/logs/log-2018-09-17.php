<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-09-17 23:51:12 --> Severity: Notice --> Undefined property: stdClass::$unitPrice /opt/lampp/htdocs/e-inventory/application/controllers/Transactions.php 346
ERROR - 2018-09-17 23:51:12 --> Query error: Column 'unitPrice' cannot be null - Invalid query: INSERT INTO `transactions` (transDate, `itemName`, `itemCode`, `description`, `quantity`, `cust_name`, `ref`, `pengguna`, `unitPrice`, `totalPrice`) VALUES (NOW(), 'laptop', '12345678', '', 10, 'Subbag Tata Usaha', '9176142', 'Admin Bmn', NULL, 0)
ERROR - 2018-09-17 23:52:16 --> Severity: Notice --> Undefined property: stdClass::$unitPrice /opt/lampp/htdocs/e-inventory/application/controllers/Transactions.php 346
ERROR - 2018-09-17 23:52:16 --> Query error: Column 'unitPrice' cannot be null - Invalid query: INSERT INTO `transactions` (transDate, `itemName`, `itemCode`, `description`, `quantity`, `cust_name`, `ref`, `pengguna`, `unitPrice`, `totalPrice`) VALUES (NOW(), 'laptop', '12345678', '', 10, 'Subbag Tata Usaha', '21953210', 'Admin Bmn', NULL, 0)
