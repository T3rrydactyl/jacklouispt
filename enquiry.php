<?php

$errors = [];

if (!empty($_POST)) {
   $email = $_POST['email'];
   $name = $_POST['name'];
   $subject = $_POST['subject'];
   $message = $_POST['message'];

   if (empty($subject)) {
       $errors[] = 'Subject is empty';
   }

   if (empty($name)) {
       $errors[] = 'Name is empty';
   }

   if (empty($email)) {
       $errors[] = 'Email is empty';
   } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
       $errors[] = 'Email is invalid';
   }

   if (empty($message)) {
       $errors[] = 'Message is empty';
   }
}
