<!DOCTYPE html>
<html>
    <head>
        <title>Επιτυχής Καταχώρηση Στοιχείων!        </title>
    </head>
    <body>
        <?php
            if (isset($_GET['name'])) {
                $name = htmlspecialchars($_GET['name']);
                echo "Καλώς ήρθες, " . $name;
            }
        ?>
    </body>
</html>