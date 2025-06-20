<?php
    // My modifications to mailer script from:
    // http://blog.teamtreehouse.com/create-ajax-contact-form
    // Added input sanitizing to prevent injection

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["name"]));
				$name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $cont_subject = trim($_POST["subject"]);
        $message = trim($_POST["message"]);

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($message) OR empty($cont_subject) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Es gab ein Problem mit Ihrer Übermittlung. Bitte füllen Sie das Formular aus und versuchen Sie es erneut.";
            exit;
        }

        // Set the recipient email address.
        $recipient = "sonilarrustemi2006@gmail.com";

        // Set the email subject.
        $subject = "Neue Anfrage von $name";

        // Build the email content.
        $email_content = "Name: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Subject: $cont_subject\n";
        $email_content .= "Message:\n$message\n";

        // Build the email headers.
        $email_headers = "Von: $name <$email>";

        // Send the email.
        if (mail($recipient, $subject, $email_content, $email_headers)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "Danke! Ihre Nachricht wurde gesendet.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "Etwas ist schiefgelaufen, und wir konnten Ihre Nachricht nicht senden.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "Es gab ein Problem mit Ihrer Übermittlung, bitte versuchen Sie es erneut.";
    }

?>
