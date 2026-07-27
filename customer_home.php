<?php
session_start();
include 'db.php';

$category_query = "SELECT * FROM categories";
$categories = mysqli_query($conn, $category_query);

$selected_cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;

if ($selected_cat_id > 0) {
    $product_query = "SELECT products.*, categories.name AS category_name 
                      FROM products 
                      LEFT JOIN categories ON products.category_id = categories.id 
                      WHERE products.category_id = $selected_cat_id";
} else {
    $product_query = "SELECT products.*, categories.name AS category_name 
                      FROM products 
                      LEFT JOIN categories ON products.category_id = categories.id";
}

$products = mysqli_query($conn, $product_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap">
</head>
<body style="margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; background-color: #0b0f19; color: #f8fafc; line-height: 1.6;">

    <!-- Navigation Bar -->
    <nav style="display: flex; justify-content: space-between; align-items: center; padding: 1.2rem 2.5rem; background: rgba(11, 15, 25, 0.85); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255, 255, 255, 0.08); position: sticky; top: 0; z-index: 1000;">
        <div style="font-family: 'Space Grotesk', sans-serif; font-size: 1.5rem; font-weight: 700;">
            <a href="customer_home.php" style="text-decoration: none; background: linear-gradient(135deg, #3b82f6 0%, #22d3ee 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">PremiumStore</a>
        </div>

        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <?php if (isset($_SESSION['user_name'])): ?>
                <a href="chekout cart.html" style="color: #cbd5e1; text-decoration: none; font-weight: 500; font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-shopping-cart" style="color: #22d3ee;"></i> Checkout
                </a>
                
                <span style="background: rgba(255, 255, 255, 0.05); padding: 0.5rem 1.2rem; border-radius: 50px; border: 1px solid rgba(255, 255, 255, 0.1); color: #f8fafc; font-size: 0.9rem; font-weight: 500; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-user-circle" style="color: #3b82f6;"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </span>

                <a href="logout.php" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); padding: 0.5rem 1.2rem; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            <?php else: ?>
                <a href="login.html" style="color: #f8fafc; text-decoration: none; font-weight: 500; font-size: 0.95rem; padding: 0.5rem 1.2rem;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a href="register.html" style="background: linear-gradient(135deg, #2563eb 0%, #06b6d4 100%); color: #fff; padding: 0.5rem 1.4rem; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 0.9rem;">
                    <i class="fas fa-user-plus"></i> Register
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <main>
        
        <!-- Hero Section -->
        <section class="hero-banner" style="position: relative; background: radial-gradient(circle at 50% 50%, #1e2640 0%, #0b0f19 100%); min-height: 75vh; display: flex; align-items: center; justify-content: center; padding: 4rem 1.5rem; text-align: center; overflow: hidden;">
            <div style="position: absolute; width: 300px; height: 300px; background: rgba(37, 99, 235, 0.2); filter: blur(100px); top: 10%; left: 15%; border-radius: 50%; pointer-events: none;"></div>
            <div style="position: absolute; width: 300px; height: 300px; background: rgba(6, 182, 212, 0.15); filter: blur(100px); bottom: 10%; right: 15%; border-radius: 50%; pointer-events: none;"></div>
            
            <div class="hero-content" style="position: relative; z-index: 2; max-width: 750px;">
                <span class="hero-badge" style="background: linear-gradient(90deg, rgba(37, 99, 235, 0.2), rgba(6, 182, 212, 0.2)); border: 1px solid rgba(6, 182, 212, 0.4); color: #22d3ee; padding: 0.5rem 1.5rem; border-radius: 50px; font-size: 0.85rem; font-weight: 600; letter-spacing: 1.5px; display: inline-block; margin-bottom: 2rem; text-transform: uppercase;">
                    <i class="fas fa-bolt" style="margin-right: 5px; color: #f59e0b;"></i> New Premium Collection Live
                </span>
                
                <h1 class="hero-title" style="font-family: 'Space Grotesk', sans-serif; font-size: 3.5rem; font-weight: 700; line-height: 1.15; margin-bottom: 1.5rem; color: #ffffff;">
                    Redefining The Way <br>You <span style="background: linear-gradient(135deg, #3b82f6 0%, #22d3ee 50%, #10b981 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Shop Premium</span>
                </h1>
                
                <p style="color: #94a3b8; font-size: 1.1rem; margin-bottom: 2.5rem;">Explore our store and browse through top categories easily.</p>

                <div class="hero-buttons" style="display: flex; flex-wrap: wrap; gap: 1.2rem; justify-content: center;">
                    <a href="#shop" style="display: inline-block; padding: 1rem 2.5rem; border-radius: 12px; font-weight: 600; text-decoration: none; font-size: 1rem; background: linear-gradient(135deg, #2563eb 0%, #06b6d4 100%); color: #fff; box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4); border: 1px solid rgba(255, 255, 255, 0.15);">
                        Explore Store <i class="fas fa-shopping-bag" style="margin-left: 8px;"></i>
                    </a>
                    
                    <a href="#categories" style="display: inline-block; padding: 1rem 2.5rem; border-radius: 12px; font-weight: 600; text-decoration: none; font-size: 1rem; background: rgba(255, 255, 255, 0.05); color: #22d3ee; border: 1px solid rgba(34, 211, 238, 0.3); backdrop-filter: blur(10px); transition: 0.3s;">
                        View Categories <i class="fas fa-th-large" style="margin-left: 8px;"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section id="categories" class="category-section" style="padding: 5rem 1.5rem; max-width: 1200px; margin: 0 auto;">
            <div class="section-header" style="text-align: center; margin-bottom: 3.5rem;">
                <h2 style="font-family: 'Space Grotesk', sans-serif; font-size: 2.5rem; font-weight: 700; margin-bottom: 0.7rem;">
                    Trending <span style="background: linear-gradient(135deg, #3b82f6 0%, #22d3ee 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Collections</span>
                </h2>
                <p style="color: #64748b; font-size: 1.05rem;">Click a category below to filter products.</p>
                <div style="width: 50px; height: 3px; background: linear-gradient(90deg, #2563eb, #06b6d4); margin: 1.2rem auto 0 auto; border-radius: 10px;"></div>
            </div>

            <div class="category-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;">
                
                <a href="customer_home.php#shop" style="text-decoration: none; color: inherit;">
                    <article style="position: relative; background: <?php echo ($selected_cat_id == 0) ? 'rgba(37, 99, 235, 0.2)' : 'linear-gradient(145deg, #131a2e 0%, #0e1324 100%)'; ?>; padding: 2rem 1.5rem; border-radius: 16px; text-align: center; border: 1px solid <?php echo ($selected_cat_id == 0) ? '#22d3ee' : 'rgba(255, 255, 255, 0.05)'; ?>; cursor: pointer;">
                        <div style="width: 60px; height: 60px; background: rgba(34, 211, 238, 0.1); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.2rem auto; font-size: 1.5rem; color: #22d3ee;">
                            <i class="fas fa-th-large"></i>
                        </div>
                        <h3 style="font-family: 'Space Grotesk', sans-serif; font-size: 1.2rem; margin-bottom: 0.4rem; font-weight: 700;">All Products</h3>
                        <p style="color: #64748b; font-size: 0.85rem;">Show everything</p>
                    </article>
                </a>

                <?php if ($categories && mysqli_num_rows($categories) > 0): ?>
                    <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                        <a href="customer_home.php?cat_id=<?php echo $cat['id']; ?>#shop" style="text-decoration: none; color: inherit;">
                            <article style="position: relative; background: <?php echo ($selected_cat_id == $cat['id']) ? 'rgba(37, 99, 235, 0.2)' : 'linear-gradient(145deg, #131a2e 0%, #0e1324 100%)'; ?>; padding: 2rem 1.5rem; border-radius: 16px; text-align: center; border: 1px solid <?php echo ($selected_cat_id == $cat['id']) ? '#22d3ee' : 'rgba(255, 255, 255, 0.05)'; ?>; cursor: pointer;">
                                <div style="width: 60px; height: 60px; background: rgba(34, 211, 238, 0.1); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.2rem auto; font-size: 1.5rem; color: #22d3ee;">
                                    <i class="<?php echo htmlspecialchars($cat['icon'] ?? 'fas fa-tag'); ?>"></i>
                                </div>
                                <h3 style="font-family: 'Space Grotesk', sans-serif; font-size: 1.2rem; margin-bottom: 0.4rem; font-weight: 700;"><?php echo htmlspecialchars($cat['name']); ?></h3>
                                <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 1rem;"><?php echo htmlspecialchars($cat['description'] ?? ''); ?></p>
                                <span style="color: #3b82f6; font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                                    View Products <i class="fas fa-arrow-right" style="font-size: 0.75rem;"></i>
                                </span>
                            </article>
                        </a>
                    <?php endwhile; ?>
                <?php endif; ?>

            </div>
        </section>

        <!-- Products Section -->
        <section id="shop" style="padding: 2rem 1.5rem 6rem 1.5rem; max-width: 1200px; margin: 0 auto;">
            <div class="section-header" style="text-align: center; margin-bottom: 3.5rem;">
                <h2 style="font-family: 'Space Grotesk', sans-serif; font-size: 2.5rem; font-weight: 700; margin-bottom: 0.7rem;">
                    Featured <span style="background: linear-gradient(135deg, #3b82f6 0%, #22d3ee 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Products</span>
                </h2>
                
                <?php if ($selected_cat_id > 0): ?>
                    <p style="color: #22d3ee; font-size: 0.95rem;">
                        Showing filtered products. <a href="customer_home.php#shop" style="color: #ef4444; text-decoration: underline; margin-left: 10px;">Clear Filter</a>
                    </p>
                <?php endif; ?>
                
                <div style="width: 50px; height: 3px; background: linear-gradient(90deg, #2563eb, #06b6d4); margin: 1.2rem auto 0 auto; border-radius: 10px;"></div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 2rem;">
                <?php if ($products && mysqli_num_rows($products) > 0): ?>
                    <?php while ($prod = mysqli_fetch_assoc($products)): ?>
                        <div style="background: linear-gradient(145deg, #131a2e 0%, #0e1324 100%); padding: 1.5rem; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.05); box-shadow: 0 10px 30px rgba(0,0,0,0.25); display: flex; flex-direction: column; justify-content: space-between;">
                            
                            <div style="width: 100%; height: 180px; border-radius: 12px; overflow: hidden; margin-bottom: 1.2rem; background: rgba(255,255,255,0.02); display: flex; align-items: center; justify-content: center;">
                                <?php if (!empty($prod['image'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($prod['image']); ?>" alt="<?php echo htmlspecialchars($prod['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-box-open" style="font-size: 3rem; color: #334155;"></i>
                                <?php endif; ?>
                            </div>

                            <div>
                                <span style="font-size: 0.75rem; text-transform: uppercase; color: #22d3ee; letter-spacing: 1px; font-weight: 600; font-family: 'Space Grotesk', sans-serif;">
                                    <?php echo htmlspecialchars($prod['category_name'] ?? 'General'); ?>
                                </span>

                                <h3 style="font-family: 'Space Grotesk', sans-serif; font-size: 1.2rem; font-weight: 700; margin: 0.4rem 0 0.8rem 0; color: #ffffff;">
                                    <?php echo htmlspecialchars($prod['title']); ?>
                                </h3>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 1rem; margin-top: 0.5rem;">
                                <span style="font-size: 1.3rem; font-weight: 700; color: #38ef7d; font-family: 'Space Grotesk', sans-serif;">
                                    $<?php echo number_format($prod['price'], 2); ?>
                                </span>
                                
                                <a href="customer cart.html?add=<?php echo $prod['id']; ?>" style="background: rgba(34, 211, 238, 0.1); color: #22d3ee; border: 1px solid rgba(34, 211, 238, 0.3); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-cart-plus"></i> Add
                                </a>
                            </div>

                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #64748b; grid-column: 1/-1;">No products found in this category.</p>
                <?php endif; ?>
            </div>
        </section>

    </main>

</body>
</html>
