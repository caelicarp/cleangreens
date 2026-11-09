<?php
include_once 'php/db.php';

$mensajeExito = '';
$mensajeError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_resena'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    $comentario = trim($_POST['comentario'] ?? '');
    $calificacion = intval($_POST['calificacion'] ?? 0);
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    $secretKey = '6Let_hcuAAAAAM9FtKUpMehGYn4OAYeh70jqn-xx';
    $verifyUrl = "https://www.google.com/recaptcha/api/siteverify";
    
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query([
                'secret'   => $secretKey,
                'response' => $recaptchaResponse
            ])
        ]
    ];
    $context  = stream_context_create($options);
    $verifyResponse = @file_get_contents($verifyUrl, false, $context);
    $responseKeys = $verifyResponse ? json_decode($verifyResponse, true) : null;

    $success = $responseKeys["success"] ?? false;
    $score   = $responseKeys["score"] ?? 0;

    if ($success && $score >= 0.5) {
        if (!empty($nombre) && !empty($comentario) && $calificacion >= 1 && $calificacion <= 5) {
            try {
                $stmt = $pdo->prepare("INSERT INTO reviews (nombre, comentario, calificacion) VALUES (?, ?, ?)");
                $stmt->execute([$nombre, $comentario, $calificacion]);
                $mensajeExito = "¡Thank you for your review! It has been published successfully.";
            } catch (PDOException $e) {
                $mensajeError = "Error saving review: " . $e->getMessage();
            }
        } else {
            $mensajeError = "Please fill in all fields and select a star rating from 1 to 5.";
        }
    } else {
        $mensajeError = "Security check failed. Please try again later.";
    }
}

try {
    $stmtQuery = $pdo->query("SELECT * FROM reviews ORDER BY fecha DESC");
    $reviews = $stmtQuery->fetchAll(PDO::FETCH_ASSOC);

    $totalResenas = count($reviews);
    $promedio = 0;
    if ($totalResenas > 0) {
        $sumaCalificaciones = array_sum(array_column($reviews, 'calificacion'));
        $promedio = round($sumaCalificaciones / $totalResenas, 1);
    }
} catch (PDOException $e) {
    $reviews = [];
    $totalResenas = 0;
    $promedio = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/svg/favicon.svg">
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/navbar.css">
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <title>Home | Clean Greens</title>
</head>
<body>

<header>
  <section id="navSection">
    <a href="https://cleangreensllc.com/"><img src="assets/logos/clean_greens.svg" alt="Clean Greens Logo" class="cg-logo-img"></a>
    <nav>
      <ul>
        <li><a href="pages/services.html">Services</a></li>
        <li><a href="pages/how_it_works.html">How it Works</a></li>
        <li><a href="pages/about_us.html">About Us</a></li>
        <li><a href="pages/contact.html">Contact</a></li>
      </ul>
    </nav>

    <img src="assets/svg/bars.svg" alt="navlinks toggle" class="nav-toggle-bars">
  </section>

  <section class="title-section">
    <h2 class="cleaning">CLEANING</h2>
    <h3 class="madewithcare">MADE WITH CARE</h3>
  </section>
</header>

<main>
  <section class="main-title">
    <h1>Reliable Cleaning & Pet Sitting Services in Denver.</h1>
    <img src="assets/img/main.jpg" alt="person cleaning image">
    <h3>"Top-rated home cleaning and pet sitting services. Enjoy a spotless house and peace of mind for you, your family and your pets. Book your service today!"</h3>
    <a href="pages/contact.html" class="cg-button quote-button">Get a Free Quote</a>
  </section>
</main>

<?php
function renderSlider($titulo, $carpeta) {
    $images = [];
    $targetDir = 'assets/img/' . $carpeta . '/';

    if (is_dir($targetDir)) {
        $files = scandir($targetDir);
        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $images[] = $targetDir . $file;
            }
        }
    }
    
    $sliderClass = 'slider-' . $carpeta;
    ?>
    <div class="cleangreens-section">
        <h2><?php echo htmlspecialchars($titulo); ?></h2>
        <div class="swiper cleangreens-slider <?php echo $sliderClass; ?>">
            <div class="swiper-wrapper">
                <?php if (!empty($images)): ?>
                    <?php foreach ($images as $img): ?>
                        <div class="swiper-slide">
                            <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($titulo); ?>">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="swiper-slide">
                        <div class="slider-empty">
                            No hay fotos en <?php echo htmlspecialchars($targetDir); ?> todavía.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
    <?php
}

renderSlider('We take care of your friends', 'mascotas');
renderSlider('We clean your house', 'casas');
renderSlider('Enjoy a clean work environment', 'oficinas');
renderSlider('RVs Cleaning', 'rvs');
renderSlider('General Cleaning', 'general');
?>

<section id="whatWeDo">
  <h3>WHAT TYPES OF CLEANING WE DO?</h3>
  <section id="cards">
    <div class="card-div"><img src="assets/svg/whatwedo/cleaning.svg" alt="" class="card-icon"><h4>Residential</h4></div>
    <div class="card-div"><img src="assets/svg/whatwedo/pet.svg" alt="" class="card-icon"><h4>Pet Sitting</h4></div>
    <div class="card-div"><img src="assets/svg/whatwedo/office.svg" alt="" class="card-icon"><h4>Commercial</h4></div>
    <div class="card-div"><img src="assets/svg/whatwedo/sitting.svg" alt="" class="card-icon"><h4>House Sitting</h4></div>
    <div class="card-div"><img src="assets/svg/whatwedo/deep.svg" alt="" class="card-icon"><h4>Move-In/Out</h4></div>
    <div class="card-div"><img src="assets/svg/whatwedo/rv.svg" alt="" class="card-icon"><h4>RV's</h4></div>
    <div class="card-div"><img src="assets/svg/whatwedo/garage.svg" alt="" class="card-icon"><h4>Garage</h4></div>
    <div class="card-div"><img src="assets/svg/whatwedo/trash.svg" alt="" class="card-icon"><h4>Trash cans</h4></div>
  </section>
</section>

<section class="reviews-section">
    <h2>Client Reviews</h2>

    <div class="reviews-summary">
        <div>
            <div class="rating-average">
                <h3><?php echo ($totalResenas > 0) ? $promedio : '0.0'; ?> / 5.0</h3>
            </div>
            <div class="stars">
                <?php 
                $starRating = ($totalResenas > 0) ? floor($promedio) : 5;
                for($i=1; $i<=5; $i++) {
                    echo $i <= $starRating ? '★' : '☆';
                }
                ?>
            </div>
        </div>
        <div>
            <p style="margin: 0; color: #555;">Based on <strong><?php echo $totalResenas; ?></strong> customer reviews</p>
        </div>
    </div>

    <?php if(!empty($mensajeExito)): ?>
        <div class="alert-success"><?php echo $mensajeExito; ?></div>
    <?php endif; ?>
    <?php if(!empty($mensajeError)): ?>
        <div class="alert-error"><?php echo $mensajeError; ?></div>
    <?php endif; ?>

    <div class="review-form-box">
        <h3 style="margin-top:0; color:#2d3a22; font-size: 1.3rem; margin-bottom: 15px;">Leave us your review</h3>
        
        <form action="" method="POST">
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

            <label for="nombre" style="display:block; margin-bottom:5px; color:#2d3a22; font-weight:bold;">Name or Business</label>
            <input type="text" name="nombre" id="nombre" placeholder="e.g. Sonny Moore" required>
            
            <label style="display:block; margin-bottom: 5px; color: #2d3a22; font-weight:bold;">Rating</label>
            <div class="star-input" style="margin-bottom: 15px;">
                <input type="radio" id="star5" name="calificacion" value="5" required><label for="star5" title="5 stars">★</label>
                <input type="radio" id="star4" name="calificacion" value="4"><label for="star4" title="4 stars">★</label>
                <input type="radio" id="star3" name="calificacion" value="3"><label for="star3" title="3 stars">★</label>
                <input type="radio" id="star2" name="calificacion" value="2"><label for="star2" title="2 stars">★</label>
                <input type="radio" id="star1" name="calificacion" value="1"><label for="star1" title="1 star">★</label>
            </div>

            <label for="comentario" style="display:block; margin-bottom:5px; color:#2d3a22; font-weight:bold;">Your Message</label>
            <textarea name="comentario" id="comentario" placeholder="How was your experience with Clean Greens?" required></textarea>
            
            <button type="submit" name="enviar_resena">Submit Review</button>
        </form>
    </div>

    <div class="reviews-list">
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $rev): ?>
                <div class="review-card">
                    <div class="review-header">
                        <span class="review-author"><?php echo htmlspecialchars($rev['nombre']); ?></span>
                        <span class="review-date"><?php echo date('M d, Y', strtotime($rev['fecha'])); ?></span>
                    </div>
                    <div class="stars" style="margin-bottom: 8px; font-size: 1.1rem;">
                        <?php 
                        $rating = intval($rev['calificacion']);
                        for($i=1; $i<=5; $i++) {
                            echo $i <= $rating ? '★' : '☆';
                        }
                        ?>
                    </div>
                    <p style="margin: 0; color: #444; line-height: 1.5;"><?php echo nl2br(htmlspecialchars($rev['comentario'])); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; color: #777; padding: 20px;">No reviews yet. Be the first one to leave feedback!</p>
        <?php endif; ?>
    </div>
</section>

<footer>
  <section class="footer-social-media">
    <a href="https://www.facebook.com/people/Clean-Greens-LLC/61593976450934/" target="_blank"><img src="assets/svg/facebook.svg" alt="facebook logo svg"></a>
    <a href="https://www.instagram.com/cleangreensll/" target="_blank"><img src="assets/svg/instagram.svg" alt="instagram logo svg"></a>
    <a href="https://nextdoor.com/pages/clean-greens-llc-aurora-co/" target="_blank"><img src="assets/svg/nextdoor.svg" alt="nextdoor logo svg"></a>
  </section>

  <img src="assets/logos/circle_green.svg" alt="clean greens footer logo" class="footer-logo">
  <h4>© 2024 Clean Greens LLC</h4>
  <p>All Rights Reserved</p>
</footer>

<script src="js/nav_toggle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
  const swipers = document.querySelectorAll('.cleangreens-slider');
  swipers.forEach((sliderEl) => {
    const slidesCount = sliderEl.querySelectorAll('.swiper-slide').length;
    const nextBtn = sliderEl.querySelector('.swiper-button-next');
    const prevBtn = sliderEl.querySelector('.swiper-button-prev');
    const paginationEl = sliderEl.querySelector('.swiper-pagination');
    const enableLoop = slidesCount > 1;

    new Swiper(sliderEl, {
      loop: enableLoop,
      autoplay: enableLoop ? { delay: 5000, disableOnInteraction: false } : false,
      pagination: { el: paginationEl, clickable: true },
      navigation: { nextEl: nextBtn, prevEl: prevBtn },
    });
  });
</script>

<script src="https://www.google.com/recaptcha/api.js?render=6Let_hcuAAAAAKG__dlc_iyjR20jxkqAUFBofXCF"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const reviewForm = document.querySelector('.review-form-box form');
    const recaptchaInput = document.getElementById('g-recaptcha-response');

    function fetchRecaptchaToken() {
      if (typeof grecaptcha !== 'undefined') {
        grecaptcha.ready(function() {
          grecaptcha.execute('6Let_hcuAAAAAKG__dlc_iyjR20jxkqAUFBofXCF', { action: 'submit_review' })
            .then(function(token) {
              if (recaptchaInput) recaptchaInput.value = token;
            });
        });
      }
    }

    fetchRecaptchaToken();

    if (reviewForm) {
      reviewForm.addEventListener('submit', function(event) {
        if (!recaptchaInput.value) {
          event.preventDefault();
          grecaptcha.ready(function() {
            grecaptcha.execute('6Let_hcuAAAAAKG__dlc_iyjR20jxkqAUFBofXCF', { action: 'submit_review' })
              .then(function(token) {
                recaptchaInput.value = token;
                reviewForm.submit();
              });
          });
        }
      });
    }
  });
</script>
</body>
</html>