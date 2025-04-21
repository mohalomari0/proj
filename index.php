<?php
include "db.php";

// استعلام لجلب المنتجات
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $products = []; // إذا لم تكن هناك منتجات، نعرف مصفوفة فارغة
}

// استعلام لجلب المنتجات المعروضة مع صورة المنتج ووصفه من جدول المنتجات
$offer_sql = "SELECT p.*, po.original_price, po.offer_price 
              FROM products p 
              JOIN product_offers po ON p.id = po.product_id";
$offer_result = $conn->query($offer_sql);

if ($offer_result->num_rows > 0) {
    $offer_products = $offer_result->fetch_all(MYSQLI_ASSOC);
} else {
    $offer_products = []; // إذا لم تكن هناك منتجات معروضة، نعرف مصفوفة فارغة
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ElectroHome - Home Appliances</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.0/mdb.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
    }

    .carousel-item img {
      height: 300px;
      object-fit: cover;
    }

    .carousel-caption {
      background: rgba(0, 0, 0, 0.5);
      padding: 10px;
      border-radius: 5px;
    }

    .hero-section {
      background: url('./home.png') no-repeat center center/cover;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-align: center;
    }

    .hero-section h1 {
      font-size: 4rem;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .hero-section p {
      font-size: 1.5rem;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    .hero-section .btn {
      padding: 10px 30px;
      font-size: 1.2rem;
      border-radius: 25px;
      background-color: #007bff;
      border: none;
      transition: background-color 0.3s ease;
    }

    .hero-section .btn:hover {
      background-color: #0056b3;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
      background-color: rgba(0, 0, 0, 0.7);
      border-radius: 50%;
      padding: 15px;
    }

    .carousel-control-prev,
    .carousel-control-next {
      width: 5%;
    }

    .carousel-indicators [data-bs-target] {
      background-color: #000;
    }

    .carousel-indicators .active {
      background-color: #ff0000;
    }

    .product-card {
      display: flex;
      align-items: center;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      margin: 10px;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .product-card img {
      width: 40%; /* عرض الصورة */
      height: 200px; /* ارتفاع ثابت للصورة */
      object-fit: cover; /* عرض الصورة بشكل كامل دون تشويه */
      border-radius: 10px;
      margin-right: 20px;
    }

    .product-info {
      width: 60%; /* عرض قسم المعلومات */
    }

    .product-info h5 {
      font-size: 1.5rem;
      margin-bottom: 10px;
      color: #333;
    }

    .product-info p {
      margin-bottom: 5px;
      color: #666;
    }

    .original-price {
      text-decoration: line-through;
      color: #888;
    }

    .offer-price {
      color: #d9534f;
      font-weight: bold;
    }

    footer {
      background: linear-gradient(135deg, #333, #000);
    }

    footer .social-icons a {
      color: #fff;
      margin: 0 10px;
      font-size: 1.5rem;
      transition: color 0.3s ease;
    }

    footer .social-icons a:hover {
      color: #007bff;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .hero-section, .carousel, footer {
      animation: fadeIn 1s ease-in-out;
    }

    @keyframes slideIn {
      from { transform: translateY(50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .carousel, footer {
      animation: slideIn 1s ease-in-out;
    }

    @media (max-width: 768px) {
      .product-card {
        flex-direction: column;
        text-align: center;
      }

      .product-card img {
        width: 100%;
        margin-right: 0;
        margin-bottom: 15px;
      }
    }
  </style>
</head>

<body>
  <!-- Navbar Section -->
  <?php include "nav.php" ?>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <h1>Welcome to ElectroHome</h1>
      <p>Your one-stop shop for the best home appliances.</p>
      <a href="/proj/pages/login.php" class="btn btn-primary">Shop Now</a>
    </div>
  </section>

  <!-- سلايدر المنتجات الجديدة -->
  <div class="container my-5">
    <h2 class="text-center mb-4">New Products</h2>
    <div id="newProductsCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <?php foreach ($products as $index => $product): ?>
          <button type="button" data-bs-target="#newProductsCarousel" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-current="true" aria-label="Slide <?= $index + 1 ?>"></button>
        <?php endforeach; ?>
      </div>
      <div class="carousel-inner">
        <?php foreach ($products as $index => $product): ?>
          <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
            <div class="product-card">
              <img src="./pages/product_admin/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
              <div class="product-info">
                <h5><?= $product['name'] ?></h5>
                <p><?= $product['description'] ?></p>
                <p>Price: <?= $product['price'] ?>$</p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#newProductsCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#newProductsCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>

  <!-- سلايدر المنتجات التي عليها عروض -->
  <div class="container my-5">
    <h2 class="text-center mb-4">Special Offers</h2>
    <div id="offersCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php foreach ($offer_products as $index => $offer): ?>
          <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
            <div class="product-card">
              <img src="./pages/product_admin/<?= $offer['image'] ?>" alt="<?= $offer['name'] ?>">
              <div class="product-info">
                <h5><?= $offer['name'] ?></h5>
                <p><?= $offer['description'] ?></p>
                <p class="original-price">Original Price: <?= $offer['original_price'] ?>$</p>
                <p class="offer-price">Offer Price: <?= $offer['offer_price'] ?>$</p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#offersCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#offersCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>

  <!-- قسم الأكثر مبيعًا -->
  <div class="container my-5">
    <h2 class="text-center mb-4">Best Sellers</h2>
    <div class="row">
      <?php foreach ($products as $product): ?>
        <div class="col-md-4 mb-4">
          <div class="product-card h-100">
            <img src="./pages/product_admin/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="img-fluid">
            <div class="product-info">
              <h5><?= $product['name'] ?></h5>
              <p><?= $product['description'] ?></p>
              <p>Price: <?= $product['price'] ?>$</p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- زر العودة إلى الأعلى -->
  <button id="backToTop" class="btn btn-primary" style="position: fixed; bottom: 20px; right: 20px; display: none;">
    <i class="fas fa-arrow-up"></i>
  </button>

  <!-- Footer Section -->
  <footer class="text-center text-lg-start bg-dark text-light">
    <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom border-secondary">
      <div class="me-5 d-none d-lg-block">
        <span>Get connected with us on social networks:</span>
      </div>
      <div>
        <a href="" class="me-4 text-light">
          <i style="color:  rgb(160, 2, 2);" class="fab fa-facebook-f"></i>
        </a>
        <a href="" class="me-4 text-light">
          <i style="color:  rgb(160, 2, 2);" class="fab fa-twitter"></i>
        </a>
        <a href="" class="me-4 text-light">
          <i style="color:  rgb(160, 2, 2);" class="fab fa-instagram"></i>
        </a>
      </div>
    </section>
    <section class="">
      <div class="container text-center text-md-start mt-5">
        <div class="row mt-3">
          <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
            <h6 class="text-uppercase fw-bold mb-4">
              <i class="fas fa-gem me-3 text-light"></i>ElectroHome
            </h6>
            <p>
              We provide top-quality home appliances to meet your needs with guaranteed efficiency and satisfaction.
            </p>
          </div>
          <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
            <h6 class="text-uppercase fw-bold mb-4">
              Useful links
            </h6>
            <p>
              <a href="#!" class="text-light">Pricing</a>
            </p>
            <p>
              <a href="#!" class="text-light">Orders</a>
            </p>
            <p>
              <a href="#!" class="text-light">Help</a>
            </p>
          </div>
          <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
            <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
            <p><i class="fas fa-home me-3 text-light"></i> Jordan, Amman 10012, Aq</p>
            <p>
              <i class="fas fa-envelope me-3 text-light"></i>
              info@example.com
            </p>
            <p><i class="fas fa-phone me-3 text-light"></i> +692 7734 4567 7</p>
          </div>
        </div>
      </div>
    </section>
    <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.2);">
      © 2025 All Rights Reserved - ElectroHome.
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.0/mdb.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // زر العودة إلى الأعلى
    window.onscroll = function() {
      if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("backToTop").style.display = "block";
      } else {
        document.getElementById("backToTop").style.display = "none";
      }
    };

    document.getElementById("backToTop").addEventListener("click", function() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  </script>
</body>

</html>