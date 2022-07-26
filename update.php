<?php

require_once "logic/db.php";

// if parameter is passed in url
if (isset($_GET["id"]) && !empty($_GET["id"])) {
   $id = $_GET["id"];
   $conn = getConnection();
   $sql = "SELECT * FROM `habit` WHERE `id` = {$id}";

   if ($result = mysqli_query($conn, $sql)) {
      if (mysqli_num_rows($result) == 1) {
         $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
         $habit = $row["habit_name"];
         $completed = $row["completed"];
      } else {
         header("location: error.php");
         exit();
      }
   } else {
      echo "Database error! <br>" . mysqli_error($conn) . "<br>" . $sql;
   }
   mysqli_close($conn);
} else {
   // NO parameter was passed in url
   header("location: error.php");
   exit();
}

// save updated data
if (isset($_POST["id"]) && !empty($_POST["id"])) {
   $id = $_POST["id"];
   $updated_habit = trim($_POST["habitName"]);
   $updated_completed = (int)trim($_POST["completed"]);

   // if no change was made
   if ($updated_habit == $habit && $updated_completed == $completed) {
      header("location: home.php");
      exit();
   }

   $conn = getConnection();
   $sql = "UPDATE `habit` SET `habit_name`='{$updated_habit}', `completed`={$updated_completed} WHERE `id`={$id};";

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
   <title>Update habit</title>
   <link rel="stylesheet" href="css/main.css">
</head>

<body>

   <?php include_once "include/header.php" ?>

   <div class="container">

      <h3 class="page-title">Update habit</h3>

      <form method="POST" action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])) ?>" class="form">
         <input type="hidden" name="id" value="<?php echo $id ?>">
         <input type="hidden" name="completed" id="completed" value="<?php echo $completed ?>">
         <label for="habitName">
            Habit
         </label>
         <input type="text" name="habitName" value="<?php echo $habit ?>" id="habitName" class="form-control" required>
         <label for="completedCheck">
            Done today:
         </label>
         <input type="checkbox" name="completedCheck" id="completedCheck" <?php echo ($completed == 1) ? "checked" : "" ?>>
         <button type="submit" class="btn-good">
            Save
         </button>
      </form>
   </div>

   <?php include_once "include/footer.php" ?>

   <script>
      let completed = document.getElementById("completed");
      let completedCheck = document.getElementById("completedCheck");
      completedCheck.addEventListener('change', () => {
         if (completedCheck.checked) completed.value = 1;
         else completed.value = 0;
      });
   </script>

</body>

</html>