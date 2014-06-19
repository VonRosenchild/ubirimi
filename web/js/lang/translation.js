var translation = new Object();

//English
//Creditcard
translation["en"] = new Object();
translation["en"]["form"] = new Object();
translation["en"]["form"]["card-paymentname"] = 'Credit card';
translation["en"]["form"]["card-number"] = 'Card number';
translation["en"]["form"]["card-cvc"] = 'CVC';
translation["en"]["form"]["card-holdername"] = 'Card holder';
translation["en"]["form"]["card-expiry"] = 'Expires';
translation["en"]["form"]["amount"] = 'Amount';
translation["en"]["form"]["currency"] = 'Currency';
translation["en"]["form"]["interval"] = 'Interval';
translation["en"]["form"]["offer-name"] = 'Offer Name';
translation["en"]["form"]["submit-button"] = 'Pay' + ' ' + $('#charge-amount').text();
translation["en"]["form"]["tooltip"] = "What is a CVV/CVC number? Prospective credit cards will have a 3 to 4-digit number, usually on the back of the card. It ascertains that the payment is carried out by the credit card holder and the card account is legitimate. On Visa the CVV (Card Verification Value) appears after and to the right of your card number. Same goes for Mastercard's CVC (Card Verfication Code), which also appears after and to the right of  your card number, and has 3-digits. Diners Club, Discover, and JCB credit and debit cards have a three-digit card security code which also appears after and to the right of your card number. The American Express CID (Card Identification Number) is a 4-digit number printed on the front of your card. It appears above and to the right of your card number. On Maestro the CVV appears after and to the right of your number. If you don’t have a CVV for your Maestro card you can use 000.";

//Elv
translation["en"]["form"]["elv-paymentname"] = 'Direct debit';
translation["en"]["form"]["elv-account"] = 'Account number';
translation["en"]["form"]["elv-holdername"] = 'Account holder';
translation["en"]["form"]["elv-bankcode"] = 'Bank code';
translation["en"]["form"]["elv-iban"] = 'IBAN';
translation["en"]["form"]["elv-bic"] = 'BIC';

//Error
translation["en"]["error"] = new Object();
translation["en"]["error"]["invalid-card-number"] = 'Invalid card number.';
translation["en"]["error"]["invalid-card-cvc"] = 'Invalid CVC.';
translation["en"]["error"]["invalid-card-expiry-date"] = 'Invalid expire date.';
translation["en"]["error"]["invalid-card-holdername"] = 'Please enter the card holder name.';
translation["en"]["error"]["invalid-elv-holdername"] = 'Please enter the account holder name.';
translation["en"]["error"]["invalid-elv-accountnumber"] = 'Please enter a valid account number.';
translation["en"]["error"]["invalid-elv-bankcode"] = 'Please enter a valid bank code.';
translation["en"]["error"]["invalid-elv-iban"] = 'Please enter a valid IBAN.';
translation["en"]["error"]["invalid-elv-bic"] = 'Please enter a valid BIC.';

translation["en"]["error"]["internal_server_error"] = 'Communication with PSP failed';
translation["en"]["error"]["invalid_public_key"] = 'Invalid Public Key';
translation["en"]["error"]["invalid_payment_data"] = 'Not permitted for this method of payment';
translation["en"]["error"]["unknown_error"] = 'Unknown Error';
translation["en"]["error"]["3ds_cancelled"] = 'Password Entry of 3-D Secure password was cancelled by the user';
translation["en"]["error"]["field_invalid_card_number"] = 'Missing or invalid creditcard number';
translation["en"]["error"]["field_invalid_card_exp_year"] = 'Missing or invalid expiry year';
translation["en"]["error"]["field_invalid_card_exp_month"] = 'Missing or invalid expiry month';
translation["en"]["error"]["field_invalid_card_exp"] = 'Card is no longer valid or has expired';
translation["en"]["error"]["field_invalid_card_cvc"] = 'Invalid checking number';
translation["en"]["error"]["field_invalid_card_holder"] = 'Invalid cardholder';
translation["en"]["error"]["field_invalid_currency"] = 'Invalid or missing currency code for 3-D Secure';


