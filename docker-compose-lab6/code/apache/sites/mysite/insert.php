<?php
session_start();
require_once 'config/dbConnect.php';
require_once 'model/Person.php';
require_once 'modelRepo/personRepo.php';

$showMarketing = getenv('marketing');

$errors = [];
if(isset($_POST['submit'])){

    $isValid = false;
    $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    if(empty($firstName)){
        $errors['first_name'] = 'Please enter a first name';
    }

    $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    if(empty($lastName)){
        $errors['last_name'] = 'Please enter a last name';
    }

    $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);
    if(empty($lastName)){
        $errors['age'] = 'Please enter age';
    }

    $description = filter_input(INPUT_POST, 'description');

    if(count($errors) < 1){
        $isValid = true;
    }

    if($isValid){
        // Make a connection
        $db = dbConnect(DB_USERNAME, DB_PASSWORD, DB_NAME, DB_HOST, DB_PORT);
        $person = new Person();
        $person->setAge($age)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setDescription($description)
            ;

        $person = dbPersonInsert($db, $person);
        if($person instanceof Person){
            $_SESSION['alert_message'] = 'Message saved';
            $_SESSION['alert_status'] = 'success';
            header("Location: /index.php");
            exit;
        }
    }

}

?>
<html>
<head>
    <title>Docker Deep Dive</title>
    <?php require_once 'templates/meta/meta.php'; ?>
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
<?php
if ($showMarketing):
    require_once 'templates/marketing/header.php';
endif;
?>
<div id="container">
    <div class="row" id="container-content">
        <section id="main-content" class="col _55">
            <div class="row">
                <h1 class="col _55">Insert a person</h1>
                <a href="/" class="col btn btn-right">View List</a>
            </div>


            <form name="person-insert" action="/insert.php" method="post">
                <fieldset>
                    <?php
                    if(count($errors) > 0): ?>
                        <div class="alert-box alert-error">
                            <h3>Errors found</h3>
                            <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="row input-row">
                        <label for="first_name" class="col">First Name</label>
                        <input type="text" name="first_name" class="col _25 <?php echo (isset($errors['first_name'])) ? 'error' : ''; ?> " />
                    </div>

                    <div class="row input-row">
                        <label for="last_name" class="col">Last Name</label>
                        <input type="text" name="last_name"  class="col _25 <?php echo (isset($errors['last_name'])) ? 'error': ''; ?> "/>
                    </div>

                    <div class="row input-row">
                        <label for="age" class="col">Age</label>
                        <input type="number" name="age" class="col _25 <?php echo (isset($errors['age'])) ? 'error': ''; ?> " />
                    </div>
                    <div class="row input-row">
                        <label for="age" class="col">Description</label>
                        <textarea name="description" class="col _25"></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn btn-right">Save</button>

                </fieldset>
            </form>



        </section>
    </div>
    <hr />
</div>
</body>
</html>
