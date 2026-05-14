<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Image Gallery Cards</title>
</head>

<body>
  <?php include 'header.php'; ?>

  <main class="container gallery">
    <?php
    for ($i = 1; $i <= 20; $i++) {
        echo '
        <div class="card">
          <img src="images/card_' . $i . '.jpg" alt="Sentence ' . $i . '">
        </div>';
    }
    ?>
  </main>

  <!-- ✅ Lightbox popup -->
  <div class="lightbox" id="lightbox">
    <span class="close-btn" id="closeBtn">&times;</span>
    <img id="lightboxImg" src="" alt="Preview">
  </div>

  <?php include 'footer.php'; ?>
</body>

</html>
