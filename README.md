<h1>Why FBform?</h1>

Using FBform you can create a contact form and easily implement it on your site. If your site has already had a contact form, you can use FBform for validation fields and sending messages.
You can easily customize email template without changing the source code.
If your server does not support PHP function mail() you can use  FBform to configure  dispatch.

Features
Easy to install into any web page
Multi language UTF-8 ready
Object oriented PHP code
Form validation and filtering
Email template
jQuery effects
Captcha
SMTP support
CSRF Protection
Can be easily embedded into a PHP or HTML page

Server Requirements
Supports PHP5 .x (PHP5.2 or higher).
Can be tweaked/modified once purchased if you know the PHP language.

How to use
Modify an existing form
Suppose you have already had a HTML contact form on your site.
<html>
<head>
</head>
<body>
 <form action="" method="post" enctype="multipart/form-data">
<input type="text" name="name value="" />
<input type="text" name="phone" value="" />
<input  type="submit" value="Submit"/>                
</form>
</body>
</html>
To connect FBform to an existing form, follow these steps:
1.	Include JS-files
 fb\js\fb.js
fb\js\ jquery-1.8.2.min.js (if not included)
fb\js\ jquery-ui-1.9.1.custom.min.js (if not included)

2.	Add the class �fb-button� to the button �submit�.
Specify file name in the attribute �action� to send the form-data when the form is submitted
Your code would look like this:

form.html

<html>
<head>
<script type="text/javascript" src="/fb/js/jquery-1.8.2.min.js"></script>  
<script type="text/javascript" src="/fb/js/jquery-ui-1.9.1.custom.min.js"></script>  
<script type="text/javascript" src="/fb/js/fb.js"></script>  
</head>
<body>
 <form action="submit.php" method="post" enctype="multipart/form-data">
<input type="text" name="name value="" />
<input type="text" name="phone" value="" />
<input  type="submit" value="Submit" class="fb-button� />                
</form>
</body>
</html>
If you need ajax loader icon, add the following code to the form:
<img src="images/ajax_loader_green.gif" class="fb-ajload" border="0" />
You can create your own ajax loader icon on this site http://www.ajaxload.info/

 
3.	Create a file submit.php, to process the forms data.
submit.php
<?php
// Include script file.
require_once '../../fb/fb_index.php'
// Create an object of class.
$fb = new Fb()
// Set the recipient address.
$fb->mail->_to('fbexample47@gmail.com');
// Set the sender address (the second parameter is optional).
$fb->mail->_from('fbexample47@gmail.com', 'FeedBack from My Site!');
// Process data.
$fb->finish()
?>
If you want to use a filter for the field, add a rule in the file submit.php.
For example, if the field "Phone" must contain only digits, add the following rule:
submit.php
<?php
// Include script file.
require_once '../../fb/fb_index.php'
// Create an object of class.
$fb = new Fb()
// Set validation rule for the field "Phone"
$fb->addName('phone','numb');
// Set the recipient address.
$fb->mail->_to('fbexample47@gmail.com');
// Set the sender address (the second parameter is optional).
$fb->mail->_from('fbexample47@gmail.com', 'FeedBack from My Site!'); 
// Process data.
$fb->finish()
?> 

Note: 
Detailed description of work with validation rules is in the example files (examples \ PHP \ submit.php).
4.	Edit the text of the letter in the file fb\templ_mail.php.
<!-- title begin -->
Hello. My name is {name}
<!-- title end -->
<!-- body begin -->
My phone is {phone}
<!-- body end -->
{name} and {phone} are the attributes �name� and �phone� specified in the elements of your HTML form.
Create a new HTML form 
Suppose you need to create a form with fields �Name� and �Phone�. Phone field is required. To create new HTML form, follow these steps:
1.	Create or edit the file where you want to display your form.
form.php
<?php

// Include script file.
require_once '../../fb/fb_index.php';

?>

<html>
<head>  
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="Content-Language" content="en" />
  <title>Feedback</title>
  <meta name="keywords" content="ketwords" />
</head>
<body>

<?php
// Create an object of class.
$fb = new Fb()
// Add the field "Name" to the form 
$fb->addName('name');
// Add the field "Phone" to the form
$fb->addName('phone',��,��,1,0,0,�text�);
// Enable captcha 
// Captcha settings fb/app/kcaptcha/kcaptcha_config.php.
$fb->captcha();
// Create HTML form, if necessary. 
// Specify the form processing script as a parameter. 
$fb->displayForm('submit.php'); 
// Process data and display the HTML form
$fb->finish();
?>
</body>
</html>

Note:  
a)	In the file "form.php" method addName() is used to add a new field to the form.
b)	Detailed description of creating form is in the example files (examples\php\ index.php).
 
2.	Create a file submit.php, to process the forms data.
submit.php
<?php
// Include script file.
require_once '../../fb/fb_index.php';
// Create an object of class.
$fb = new Fb();
// Set validation rule for the field "Phone"
$fb->addName('phone',�numb�,�Phone is Wrong!�,1,10,0);
// The field "Name" does not require any rules.
// Enable captcha 
// Captcha settings fb/app/kcaptcha/kcaptcha_config.php.
$fb->captcha();
// Process data
$fb->finish();
?>

Note:
a)	 In the file "form.php" method addName() is used to set validation rules. 
b)	Detailed description of work with validation rules is in the example files (examples \ PHP \ submit.php).
3.	Edit the text of the letter in the file fb\templ_mail.php.
<!-- title begin -->
Hello. My names {name}
<!-- title end -->
<!-- body begin -->
My phone is {phone}
<!-- body end -->

That's all.