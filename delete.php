<?php

require_once "logic/db.php";

if (isset($_GET["id"]) && !empty($_GET["id"])) {
   $id = $_GET["id"];
   $conn = getConnection();
   $sql = "SELECT * FROM `habit` WHERE `id` = {$id}";

   if ($result = mysqli_query($conn, $sql)) {
      if (mysqli_num_rows($result) == 1) {
         $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
         $habit = $row["habit_name"];
         $created_on = $row["created_on"];
      } else {
         header("location: error.php");
         exit();
      }
   } else {
      echo "Database error! <br>" . mysqli_error($conn) . "<br>" . $sql;
   }
   mysqli_close($conn);
} else {
   header("location: error.php");
   exit();
}

if (isset($_POST["id"]) && !empty($_POST["id"])) {
   $id = $_POST["id"];
   $conn = getConnection();
   $sql = "DELETE FROM `habit` WHERE `id`={$id};";
   if (mysqli_query($conn, $sql)) {
      header("location: home.php");
      exit();
   } else {
      echo "Database error! <br>" . mysqli_error($conn) . "<br>" . $sql;
   }
   mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Delete habit</title>
   <link rel="stylesheet" href="css/main.css">
</head>

<body>

   <?php include_once "include/header.php" ?>

   <div class="container">
      <h3 class="page-title">Are you sure you want to delete this record?</h3>

      <table class="my-2">
         <tbody>
            <tr>
               <td><b>Habit:</b></td>
               <td><?php echo $habit ?></td>
            </tr>
            <tr>
               <td><b>Created on:</b></td>
               <td><?php echo $created_on ?></td>
            </tr>
         </tbody>
      </table>

      <form method="POST" action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])) ?>">
         <input type="hidden" name="id" value="<?php echo $id ?>">
         <a href="home.php" class="btn btn-bad">No</a>
         <button type="submit" class="btn btn-good">Yes</button>
      </form>
   </div>

   <?php include_once "include/footer.php" ?>

</body>

</html>