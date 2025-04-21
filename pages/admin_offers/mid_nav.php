



.navbar {
            width: 100%;
            background-color: #f8f9fa; /* لون خلفية النافبار */
            padding: 10px 20px;
            margin-bottom: 20px; /* تباعد أسفل النافبار */
        }




<nav class="navbar navbar-expand-lg navbar-light bg-light category-nav">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#categoryNavbar" aria-controls="categoryNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="categoryNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <!-- زر "All Products" -->
                        <li class="nav-item">
                            <a class="nav-link active" href="offers.php?category=all">All Products</a>
                        </li>
                        <!-- تصنيفات المنتجات -->
                        <?php if (isset($categories) && is_array($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="offers.php?category=<?php echo $category['id']; ?>">
                                        <?php echo $category['name']; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
