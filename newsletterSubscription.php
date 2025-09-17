<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="newsletter.css">
        <title>  Εγγραφείτε στο Newsletter μας! </title>
    </head>
    <body>
        <div id="div1">
            Παρακαλούμε συμπληρώστε την παρακάτω φόρμα με τα στοιχεία σας.
        </div>

        <?php

        $errors = [];

        // Συνάρτηση Sanitize Input για "καθαρή" είσοδο
            function sanitize_input($anInput){
            $anInput=trim($anInput);
            $anInput=stripslashes($anInput);
            $anInput=htmlspecialchars($anInput);
            return $anInput;
            }

            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "newsletterRecord";

            //Σύνδεση στον server...
            $conn = new mysqli($servername, $username, $password);

            //Δημιουργία Βάσης με ονομα newsletterRecord...
            $sql= "CREATE DATABASE IF NOT EXISTS $database ";
            if ($conn->query($sql)!==TRUE){
                $errors['db']="Τεχνικό Σφάλμα. Προσπαθήστε ξανά";
            } 
            $conn->close();

            // Σύνδεση με τη βάση που δημιουργήσαμε...
             $conn= new mysqli($servername, $username, $password, $database);

             //Δημιουργία Πίνακα 
             $sql="CREATE TABLE IF NOT EXISTS record1 (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                fname varchar(20) NOT NULL,
                lname varchar(20) NOT NULL,
                email varchar (30) NOT NULL,
                region varchar (20))";

            //Έλεγχος αν δημιουργήθηκε ο πίνακας
            if ($conn->query($sql) !== TRUE) {
                error_log("DB error creating table: " . $conn->error);
                 $errors['db']="Τεχνικό Σφάλμα. Προσπαθήστε ξανά";
            }


            //Άντληση εισαχθέντων στοιχείων από τη φόρμα και καταχώρησή τους στη newsletterRecord...  
            $fname = "";
            $lname="";
            $email="";
            $region="";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (empty($_POST["firstname"])) {
                    $errors['firstname'] = "First name is required";
                } else {
                    $fname = sanitize_input($_POST["firstname"]);
                }

                if (empty($_POST["lastname"])) {
                    $errors['lastname'] = "Last name is required";
                } else {
                    $lname = sanitize_input($_POST["lastname"]);
                }

                if (empty($_POST["email"])) {
                    $errors['email'] = "Email is required";
                } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = "Invalid email format";
                } else {
                    $email = sanitize_input($_POST["email"]);
                }

                $region = sanitize_input($_POST["region"]);

                // Εισαγωγή εγγραφής στη φόρμα με πρετοιμασία για αποφυγή SQL injection
                if(empty($errors))
                {
                    $stmt = $conn->prepare("INSERT INTO record1 (fname, lname, email, region) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $fname, $lname, $email, $region);

                    if ($stmt->execute()) {
                        header("Location: successfulOnboarding.php?name=" . urlencode($fname));
                        exit();
                    } else {
                        $errors['db'] = "Αποτυχία εισαγωγής στοιχείων.";
                    }
                    $stmt->close();
                }
            }
            $conn->close();
        ?>
        <!-- Η φόρμα με εμφάνιση ενδεχομένων σφαλμάτων μέσα από htmlspecialchars για διαφύλαξη του layout--> 
        <form method="post" action="">

            Όνομα/Name: <input type="text" name="firstname" value="<?= htmlspecialchars($fname, ENT_QUOTES, 'UTF-8') ?>" required>
            <?php if (!empty($errors['firstname'])): ?>
                <span class="error"><?= htmlspecialchars($errors['firstname']); ?></span>
            <?php endif; ?>
            <br>

            Επίθετο/ Lastname: <input type="text" name="lastname" value="<?= htmlspecialchars($lname,ENT_QUOTES,'UTF-8')?>" required>
            <?php if (!empty($errors['lastname'])): ?>
                <span class="error"><?=htmlspecialchars($errors['lastname']); ?> </span>
            <?php endif;?>
            <br>

            Διέυθυνση ηλ. ταχυδρομείου/email: <input type="email" name="email" value="<?= htmlspecialchars($email,ENT_QUOTES,'UTF-8')?>" required>
            <?php if(!empty($errors['email'])): ?>
                <span class="error"> <?=htmlspecialchars($errors['email']);?> </span>
            <?php endif;?>
            <br>

            Περιοχή/Region: <input type="text" name="region" value="<?= htmlspecialchars($region,ENT_QUOTES,'UTF-8')?>">
            <?php if(!empty($errors['region'])):?>
                <span class="error">  <?=htmlspecialchars($errors['region']);?> </span>
            <?php endif?>
            <br>
            <br>
            
            <div id="accept">
                <label>
                    <input type="checkbox" id="consent" name="consent" required>
                    *Συμφωνώ με την επεξεργασία των στοιχείων μου και τους <a href="termsofUse.html"> όρους χρήσης</a>
                </label>
            </div> <br>
            <input type="submit" value="Υποβολή">
        </form>
        <footer>
            Follow Us!
        </footer>
    </body>
</html>