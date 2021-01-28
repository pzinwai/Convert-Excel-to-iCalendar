<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>Convert excel to calendar</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900" rel="stylesheet">
    <style>
      .center_div{
          margin: 0 auto;
          width:70% /* value of your choice which suits your alignment */
      }
    </style>
  </head>
  <body>
    <div class="container center_div">
      <br><br>
      <h3>Convert Excel to iCalendar</h3>
      <form action="generate_icalendars.php" method="post" enctype="multipart/form-data">
        <fieldset>
          <br><br>
          <div class="custom-file">
            <input type="file" class="custom-file-input" name="fileToUpload" id="fileToUpload" accept=".xls,.xlsx" required>
            <label class="custom-file-label" for="fileToUpload">Choose file...</label>
          </div><br><br>
          <button type="submit" class="btn btn-primary" name="submit">Convert</button>
        </fieldset>
      </form>
      <br>
      <?php if(isset($_GET["success"])) { ?>
        <div class="alert alert-success">
          <strong>Success!</strong> <?=$_GET["success"]?> iCalendars have been generated.
        </div>
      <?php } ?>
    </div>
  </body>
</html>
