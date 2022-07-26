<?php

session_start();
include_once "logic/db.php";

// retrieve saved habits
$data = [];
$sql = "SELECT * FROM `habit`;";
$conn = getConnection();

if ($result = mysqli_query($conn, $sql)) {
   if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_array($result)) {
         $data[] = $row;
      }
   }
   mysqli_close($conn);
}

// save habit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $habit = trim($_POST["habitName"]);
   $conn = getConnection();
   $sql = "INSERT INTO `habit` (`habit_name`) VALUES('{$habit}');";

   // execute
   if (mysqli_query($conn, $sql)) {
      header("location: " . $_SERVER['REQUEST_URI']);
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
   <title>Habit Tracker</title>
   <link rel="stylesheet" href="css/main.css">
</head>

<body class="home-page">

   <header class="header transparent">
      <div class="container">
         <div class="header-content">
            <a href="home.php" class="logo">Habit tracker</a>
            <p class="date"><b><?php echo date("D, M d, Y") ?></b></p>
         </div>
      </div>
   </header>

   <div class="container">

      <form method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" class="form">
         <label for="habitName">
            New habit
         </label>
         <input type="text" name="habitName" id="habitName" class="form-control" required>
         <button type="submit" class="btn-good">
            Save
         </button>
      </form>

      <?php if (count($data) > 0) { ?>

         <table class="table table-dark">
            <thead>
               <th>#</th>
               <th>Habit</th>
               <th>Done today</th>
               <th>Actions</th>
            </thead>

            <tbody>

               <?php
               $count = 0;
               foreach ($data as $row) {
                  $count++;
               ?>

                  <tr>
                     <td><?php echo $count ?></td>
                     <td><?php echo $row["habit_name"] ?></td>
                     <td class="text-center">
                        <?php if ($row["completed"] == 0) {
                           echo "<i class='fa-solid fa-circle-xmark icon-cross'></i>";
                        } else {
                           echo "<i class='fa-solid fa-circle-check icon-tick'></i>";
                        }
                        ?>
                     </td>
                     <td>
                        <a href="update.php?id=<?php echo $row["id"] ?>">Edit</a>
                        <a href="delete.php?id=<?php echo $row["id"] ?>">Delete</a>
                     </td>
                  </tr>

               <?php } ?>

            </tbody>
         </table>

      <?php } else { ?>

         <div>No habits added yet!</div>

      <?php } ?>

      <?php include_once "include/footer.php" ?>

   </div>


   <script src="https://kit.fontawesome.com/e307a20384.js" crossorigin="anonymous"></script>

</body>

</html>