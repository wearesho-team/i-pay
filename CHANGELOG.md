# IPay Integration Change Log

## 3.5.0
- Move status constants from [Payment](./src/Payment.php) into [PaymentStatus](./src/PaymentStatus.php) interface
- Separate constants from [ConfigInterface](./src/ConfigInterface.php) into
 [Url](./src/Url.php) and [Language](./src/Language.php) interfaces 
- Sign and salt added to [PaymentInterface](./src/PaymentInterface.php) and [Payment](./src/Payment.php) class constructor
- Add optional merchant id to [PaymentInterface](./src/PaymentInterface.php)
- Implement notification [Server](./src/Notification) to handle notifications about payments
