<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// We need to use sessions, so you should always start sessions using the below code.
session_start();
// Include functions and connect to the database using PDO MySQL
include "functions.php";
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
    <title>jacklouispt - Privacy Policy</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/styles-global.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php setFavicon(); ?>

    <style>

    .section {
      height: auto;
    }


    </style>

</head>




<body>

    <!-- Top bar -->
    <?php displayTopBar(); ?>




  <div class="story">
  <div class="section">
  <div class="box">

      <h1>Privacy Policy</h1>
      <div class="caption">
        <p>Last Updated: 2023-08-21</p>
      </div>

    <br>

    <p>JACK LOUIS PT LIMITED ("we," "our," or "us") trading as jacklouispt is committed to protecting your privacy. This Privacy Policy outlines
        how we collect, use, and protect the personal information you provide on our website <a
            href="[jacklouispt.com]">jacklouispt.com</a>. By using our website and services, you consent to the practices
        described in this policy.</p>

    <h2>Company Information</h2>

    <p>JACK LOUIS PT LIMITED<br>
        4/5 Newbattle Terrace<br>
        Edinburgh<br>
        EH10 4RT<br>
        United Kingdom</p>

    <h2>Website Information</h2>

    <p><em>Website URL:</em> <a href="https://jacklouispt.com">jacklouispt.com</a><br>
        <em>Description of Services:</em> We offer online fitness programmes categorised as fat loss or muscle gain. Our
        services include exercise programmes, exercise logging, meal plans, and demonstration videos.</p>

    <h2>Personal Information Collected</h2>

    <p>We collect the following personal information:</p>
    <ul>
        <li>Email Address: We collect your email address for password resets and communication purposes.</li>
        <li>Username and Password: A unique username and hashed password are required for secure access to your
            account.</li>
        <li>Fitness Level: We ask whether you are a beginner, intermediate, or advanced user to tailor programme
            recommendations.</li>
        <li>Payment Information: When processing payments, we use Stripe or Apple Pay. Stripe may collect full name,
            address, and card details for payment processing.</li>
        <li>Calorie Calculator Data: Our calorie calculator feature provided by MealPro.net may request weight, activity
            level, gender, and height for meal planning, but this data is not collected by us.</li>
    </ul>

    <h2>Purpose of Data Collection</h2>

    <p>We collect and use your personal information for the following purposes:</p>
    <ul>
        <li>Providing and customising fitness programmes and services.</li>
        <li>Communication regarding your account, programmes, and services.</li>
        <li>Processing payments for purchased programmes.</li>
        <li>Ensuring security and authorised access to your account.</li>
    </ul>

    <h2>Legal Basis for Processing</h2>

<p>We process your personal data based on the legal basis of:</p>
<ul>
    <li><strong>Consent:</strong> When you voluntarily provide your personal information, such as during account
        registration, you provide consent for us to process and use that information for the purposes described in this
        policy.</li>
    <li><strong>Contractual Necessity:</strong> We process your data as necessary to fulfil our contractual obligations to
        you. For instance, processing your payment information to provide access to purchased programmes.</li>
</ul>

<h2>Data Usage</h2>

<p>We use the collected data solely for the purposes stated in this policy. Your personal information is used to
    customise your fitness programmes, facilitate communication, process payments, and ensure the security of your
    account.</p>

<h2>Data Sharing</h2>

<p>We may share your data with select third-party service providers:</p>
<ul>
    <li>Payment Processors: We use Stripe and Apple Pay to process payments. Stripe may collect full name, address, and
        card details for payment processing. We do not store your complete payment information.</li>
    <li>Calorie Calculator Provider: Our calorie calculator feature, powered by MealPro.net, may request certain data,
        but this data is not collected or stored by us.</li>
</ul>
<p>We do not sell, rent, or trade your personal information to any third parties.</p>

<h2>Cookies and Tracking</h2>

<p>We may use cookies and similar tracking technologies to enhance user experience and improve our services:</p>
<ul>
    <li><strong>Cookies:</strong> Cookies are small text files that are placed on your device when you visit our website.
        We use cookies to remember your preferences, authenticate your session, and analyse how you interact with our
        site.</li>
    <li><strong>Analytics:</strong> We may use third-party analytics tools to gather information about how you use our
        website. This helps us understand user behaviour and improve our content and services.</li>
</ul>
<p>You can manage your cookie preferences through your browser settings. Note that disabling cookies may affect the
    functionality of certain parts of our website.</p>

<h2>Data Security</h2>

<p>We take appropriate security measures to protect your personal information from unauthorised access, alteration, or
    disclosure. However, no data transmission over the internet is entirely secure, and we cannot guarantee the
    security of your information.</p>

<h2>Data Retention</h2>

<p>We retain your personal data only for as long as necessary to provide our services and fulfil the purposes outlined
    in this policy. When your data is no longer needed, it will be securely deleted or anonymised.</p>

<h2>User Rights</h2>

<p>You have certain rights regarding your personal data:</p>
<ul>
    <li><strong>Access:</strong> You can request access to the personal information we hold about you.</li>
    <li><strong>Correction:</strong> You can request corrections to any inaccuracies in your personal information.</li>
    <li><strong>Deletion:</strong> You can request the deletion of your personal information from our records.</li>
    <li><strong>Restriction:</strong> You can request the restriction of processing your personal information under certain
        circumstances.</li>
</ul>
<p>To exercise these rights, please contact us at <a href="mailto:enquiries@jacklouispt.com">enquiries@jacklouispt.com</a>.
    We will respond to your request in accordance with applicable data protection laws.</p>

<h2>Marketing Communications</h2>

<p>You can choose to receive marketing communications from us by opting in during account registration. You can also
    opt-out of these communications at any time by following the instructions provided in the emails.</p>

<h2>Children's Privacy</h2>

<p>Our services are not intended for individuals under the age of 16. We do not knowingly collect personal information
    from individuals in this age group.</p>

<h2>International Transfers</h2>

<p>Your personal data may be transferred and processed outside your country of residence. When your data is transferred,
    we ensure appropriate safeguards to protect your privacy and data rights.</p>

<h2>Changes to Privacy Policy</h2>

<p>We may update this Privacy Policy to reflect changes in our practices or legal requirements. We will notify you of any
    material changes through our website or other communication methods.</p>

<h2>Contact Information</h2>

<p>If you have any questions, concerns, or inquiries regarding this Privacy Policy, please contact us at <a
        href="mailto:enquiries@jacklouispt.com">enquiries@jacklouispt.com</a>.</p>




  </div>
  </div>
  </div>





  <?php displayFooter(); ?>



</body>


</html>
