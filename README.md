<h2>FBform</h2>

Using FBform you can create a contact form and easily implement it on your site. If your site has already had a contact form, you can use FBform for validation fields and sending messages.<br />
You can easily customize email template without changing the source code.<br />
If your server does not support PHP function mail() you can use  FBform to configure  dispatch.<br />

<h2>Features</h2>
Easy to install into any web page<br />
Multi language UTF-8 ready<br />
Object oriented PHP code<br />
Form validation and filtering<br />
Email template<br />
jQuery effects<br />
Captcha<br />
SMTP support<br />
CSRF Protection<br />
Can be easily embedded into a PHP or HTML page<br />

<h2>Server Requirements</h2>
Supports PHP5 .x (PHP5.2 or higher).<br />
Can be tweaked/modified once purchased if you know the PHP language.<br />

<h2>How to use</h2>
Modify an existing form<br />
Suppose you have already had a HTML contact form on your site.<br />
<pre><code>
&lt;html&gt;
&lt;head&gt;
&lt;/head&gt;
&lt;body&gt;
&lt;form action="" method="post" enctype="multipart/form-data"&gt;
&lt;input type="text" name="name value="" /&gt;
&lt;input type="text" name="phone" value="" /&gt;
&lt;input  type="submit" value="Submit"/&gt;                
&lt;/form&gt;
&lt;/body&gt;
&lt;/html&gt;
</code></pre>

To connect FBform to an existing form, follow these steps:<br />
1.	Include JS-files<br />
    fb\js\fb.js<br />
    fb\js\ jquery-1.8.2.min.js (if not included)<br />
    fb\js\ jquery-ui-1.9.1.custom.min.js (if not included)<br />

2.	Add the class «fb-button» to the button «submit».<br />
Specify file name in the attribute «action» to send the form-data when the form is submitted<br />
Your code would look like this:<br />

form.html
<pre><code>
&lt;html&gt;
&lt;head&gt;
&lt;script type="text/javascript" src="/fb/js/jquery-1.8.2.min.js"&gt;&lt;/script&gt;  
&lt;script type="text/javascript" src="/fb/js/jquery-ui-1.9.1.custom.min.js"&gt;&lt;/script&gt;
&lt;script type="text/javascript" src="/fb/js/fb.js"&gt;&lt;/script&gt;
&lt;/head&gt;
&lt;body&gt;
&lt;form action="submit.php" method="post" enctype="multipart/form-data"&gt;
&lt;input type="text" name="name value="" /&gt;
&lt;input type="text" name="phone" value="" /&gt;
&lt;input  type="submit" value="Submit" class="fb-button” /&gt;
&lt;/form&gt;
&lt;/body&gt;
&lt;/html&gt;
</code></pre>

If you need ajax loader icon, add the following code to the form:<br />
<pre><code>
&lt;img src="images/ajax_loader_green.gif" class="fb-ajload" border="0" /&gt;
</code></pre>
You can create your own ajax loader icon on this site http://www.ajaxload.info/<br />

 
3.	Create a file submit.php, to process the forms data.<br />
submit.php
<pre><code>
&lt;?php
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
?&gt;
</code></pre>

If you want to use a filter for the field, add a rule in the file submit.php.<br />
For example, if the field "Phone" must contain only digits, add the following rule:<br />
submit.php
<pre><code>
&lt;?php
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
?&gt; 
</code></pre>

Note: <br />
Detailed description of work with validation rules is in the example files (examples \ PHP \ submit.php).<br />
4.	Edit the text of the letter in the file fb\templ_mail.php.<br />
<pre><code>
&lt;!-- title begin --&gt; 
Hello. My name is {name}
&lt;!-- title end --&gt; 
&lt;!-- body begin --&gt; 
My phone is {phone}
&lt;!-- body end --&gt; 
{name} and {phone} are the attributes «name» and «phone» specified in the elements of your HTML form.
</code></pre>

<h2>Create a new HTML form</h2>

Suppose you need to create a form with fields «Name» and «Phone». Phone field is required. To create new HTML form, follow these steps:<br />
1.	Create or edit the file where you want to display your form.<br />
form.php
<pre><code>
&lt;?php

// Include script file.
require_once '../../fb/fb_index.php';

?&gt; 

&lt;html&gt; 
&lt;head&gt; 
  &lt;meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /&gt; 
  &lt;meta http-equiv="Content-Language" content="en" /&gt; 
  &lt;title&gt; Feedback&lt;/title&gt; 
  &lt;meta name="keywords" content="ketwords" /&gt; 
&lt;/head&gt; 
&lt;body&gt; 

&lt;?php
// Create an object of class.
$fb = new Fb()
// Add the field "Name" to the form 
$fb->addName('name');
// Add the field "Phone" to the form
$fb->addName('phone',’’,’’,1,0,0,’text’);
// Enable captcha 
// Captcha settings fb/app/kcaptcha/kcaptcha_config.php.
$fb->captcha();
// Create HTML form, if necessary. 
// Specify the form processing script as a parameter. 
$fb->displayForm('submit.php'); 
// Process data and display the HTML form
$fb->finish();
?&gt; 
&lt;/body&gt; 
&lt;/html&gt; 
</code></pre>

Note:  <br />
a)	In the file "form.php" method addName() is used to add a new field to the form.<br />
b)	Detailed description of creating form is in the example files (examples\php\ index.php).<br />
 
2.	Create a file submit.php, to process the forms data.<br />
submit.php
<pre><code>
&lt;?php
// Include script file.
require_once '../../fb/fb_index.php';
// Create an object of class.
$fb = new Fb();
// Set validation rule for the field "Phone"
$fb->addName('phone',’numb’,’Phone is Wrong!’,1,10,0);
// The field "Name" does not require any rules.
// Enable captcha 
// Captcha settings fb/app/kcaptcha/kcaptcha_config.php.
$fb->captcha();
// Process data
$fb->finish();
?&gt; 
</code></pre>
Note:<br />
a)	 In the file "form.php" method addName() is used to set validation rules. <br />
b)	Detailed description of work with validation rules is in the example files (examples \ PHP \ submit.php).<br />

3.	Edit the text of the letter in the file fb\templ_mail.php.
<pre><code>
&lt;!-- title begin --&gt; 
Hello. My names {name}
&lt;!-- title end --&gt; 
&lt;!-- body begin --&gt; 
My phone is {phone}
&lt;!-- body end --&gt; 
</code></pre>
That's all.
