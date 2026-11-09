<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="/assets/svg/favicon.svg">
	<link rel="stylesheet" href="css/global.css">
	<link rel="stylesheet" href="css/navbar.css">
	<link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
	<title>Home | Clean Greens</title>
</head>
<body>

<header>
  <section id=navSection>
    <a href="https://cleangreensllc.com/"><img src="assets/logos/clean_greens.svg" alt="Clean Greens Logo" class="cg-logo-img"></a>
    <nav>
      <ul>
        <li><a href="/pages/services.html">Services</a></li>
        <li><a href="/pages/how_it_works.html">How it Works</a></li>
        <li><a href="/pages/about_us.html">About Us</a></li>
        <li><a href="/pages/contact.html">Contact</a></li>
      </ul>
    </nav>

    <img src="/assets/svg/bars.svg" alt="navlinks toggle" class="nav-toggle-bars">
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
// Carpeta donde se guardan las imágenes del slider
$sliderDir = 'assets/img/slider/';
$images = [];

// Verificamos si la carpeta existe para leer su contenido
if (is_dir($sliderDir)) {
    $files = scandir($sliderDir);
    foreach ($files as $file) {
        // Filtramos solo archivos de imagen válidos
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $images[] = $sliderDir . $file;
        }
    }
}
?>

<!-- Contenedor del Slider -->
<div class="swiper cleangreens-slider">
    <div class="swiper-wrapper">
        <?php if (!empty($images)): ?>
            <?php foreach ($images as $img): ?>
                <div class="swiper-slide">
                    <img src="<?php echo htmlspecialchars($img); ?>" alt="CleanGreens Banner">
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Mensaje de respaldo si la carpeta está vacía -->
            <div class="swiper-slide" style="display: flex; align-items: center; justify-content: center; background: #f0f0f0; color: #666;">
                <p>Sube imágenes a la carpeta assets/slider/ para mostrarlas aquí.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Paginación (puntitos abajo) -->
    <div class="swiper-pagination"></div>
    
    <!-- Botones de navegación (flechas laterales) -->
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>

<section id="whatWeDo">
  <h3>WHAT TYPES OF CLEANING WE DO?</h3>

  <section id="cards">
    <div class="card-div">
      <img src="assets/svg/whatwedo/cleaning.svg" alt="" class="card-icon">
      <h4>Residential</h4>
    </div>

    <div class="card-div">
      <img src="assets/svg/whatwedo/pet.svg" alt="" class="card-icon">
      <h4>Pet Sitting</h4>
    </div>

    <div class="card-div">
      <img src="assets/svg/whatwedo/office.svg" alt="" class="card-icon">
      <h4>Commercial</h4>
    </div>

    <div class="card-div">
      <img src="assets/svg/whatwedo/sitting.svg" alt="" class="card-icon">
      <h4>House Sitting</h4>
    </div>

    <div class="card-div">
      <img src="assets/svg/whatwedo/deep.svg" alt="" class="card-icon">
      <h4>Move-In/Out</h4>
    </div>

    <div class="card-div">
      <img src="assets/svg/whatwedo/rv.svg" alt="" class="card-icon">
      <h4>RV's</h4>
    </div>

    <div class="card-div">
      <img src="assets/svg/whatwedo/garage.svg" alt="" class="card-icon">
      <h4>Garage</h4>
    </div>

    <div class="card-div">
      <img src="assets/svg/whatwedo/trash.svg" alt="" class="card-icon">
      <h4>Trash cans</h4>
    </div>
  </section>
</section>

<footer>
  <section class="footer-social-media">
    <a href="https://www.facebook.com/people/Clean-Greens-LLC/61593976450934/" target="_blank"><img src="/assets/svg/facebook.svg" alt="facebook logo svg"></a>
    <a href="https://www.instagram.com/cleangreensll/" target="_blank"><img src="/assets/svg/instagram.svg" alt="instagram logo svg"></a>
    <a href="https://nextdoor.com/pages/clean-greens-llc-aurora-co/" target="_blank"><img src="/assets/svg/nextdoor.svg" alt="facebook logo svg"></a>
  </section>

  <img src="assets/logos/circle_green.svg" alt="clean greens footer logo" class="footer-logo">
  <h4>© 2024 Clean Greens LLC</h4>
  <p>All Rights Reserved</p>
</footer>

<script src="js/nav_toggle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  const swiper = new Swiper('.cleangreens-slider', {
    loop: true,               
    autoplay: {
      delay: 4500,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });
</script>
</body>
</html>
