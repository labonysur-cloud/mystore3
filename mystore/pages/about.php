<?php
include '../includes/header.php';
?>

<style>
    body, html {
        height: 100%;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #4a2c00;
        position: relative;
        min-height: 100vh;
        overflow-x: hidden;
        background: #f5f0e6;
    }
    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('https://i.pinimg.com/736x/8f/83/4c/8f834cfd55d24e18082fbee2013f3108.jpg') no-repeat center center fixed;
        background-size: cover;
        filter: brightness(0.5) saturate(0.7) blur(5px);
        z-index: -1;
        pointer-events: none;
        transition: filter 0.3s ease;
    }
    .about-container:hover ~ body::before {
        filter: brightness(0.7) saturate(0.9) blur(3px);
    }
    .about-container:hover {
        background: rgba(255, 255, 255, 0.95);
        transition: background 0.3s ease;
    }
    .about-container {
        background: rgba(255, 255, 255, 0.85);
        max-width: 900px;
        margin: 60px auto 140px auto;
        padding: 30px 40px;
        border-radius: 15px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        position: relative;
        z-index: 10;
    }
    .about-container h2 {
        font-size: 2.8rem;
        margin-bottom: 25px;
        font-weight: 700;
        text-align: center;
        color: #b8860b;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
    }
    .about-content {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }
    .about-left {
        flex: 1 1 300px;
        text-align: center;
    }
    .about-left img.about-image {
        max-width: 100%;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    }
    .about-right {
        flex: 1 1 400px;
        font-size: 1.2rem;
        line-height: 1.6;
        background: rgba(255, 255, 255, 0.9);
        padding: 20px 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .developers {
        position: relative;
        /* Changed from fixed to relative to prevent overlap and scrolling issues */
        /* Removed bottom, left, and transform properties */
        max-width: 900px;
        width: 90%;
        max-height: 120px;
        overflow-y: auto;
        background: rgba(255, 255, 255, 0.9);
        text-align: center;
        padding: 15px 20px;
        font-size: 1rem;
        color: #6b4f01;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        font-style: italic;
        font-weight: 600;
        letter-spacing: 0.03em;
        border-top: 1px solid #b8860b;
        border-radius: 10px 10px 0 0;
        z-index: 1000;
        scrollbar-width: thin;
        scrollbar-color: #b8860b transparent;
    }
    .developers::-webkit-scrollbar {
        width: 8px;
    }
    .developers::-webkit-scrollbar-track {
        background: transparent;
    }
    .developers::-webkit-scrollbar-thumb {
        background-color: #b8860b;
        border-radius: 10px;
        border: 2px solid transparent;
    }
    .developers h3 {
        margin: 0 0 5px 0;
        font-weight: 700;
        color: #b8860b;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.15);
    }
    a {
        color: #b8860b;
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
    }
    @media (max-width: 768px) {
        .about-content {
            flex-direction: column;
        }
        .about-right, .about-left {
            flex: 1 1 100%;
        }
        .about-container {
            margin: 40px 20px 140px 20px;
            padding: 20px;
        }
        .developers {
            bottom: 80px;
            max-height: 150px;
        }
    }
</style>

<div class="about-container">
    <h2>About Us</h2>

    <div class="about-content">
        <div class="about-left">
            <img src="https://i.pinimg.com/736x/8f/83/4c/8f834cfd55d24e18082fbee2013f3108.jpg" alt="About Us Image" class="about-image" />
        </div>
        <div class="about-right">
            <p>Welcome to <strong>My Store</strong>, where elegance meets everyday charm. Born from a love for classic femininity and timeless beauty, My Store is more than just an online boutique—it's a curated collection of dreamlike fashion and accessories designed to make you feel like royalty. Our vision is simple: to help you embrace your inner princess through fashion that celebrates grace, softness, and individuality. From flowy floral dresses and romantic lace bows to delicately crafted bags and hair accessories, every item in our collection is handpicked with care, ensuring high quality, comfort, and a fairytale aesthetic. We believe that beauty lies in the details, which is why each piece is chosen for its unique design, soft color palette, and whimsical flair. Whether you're dressing up for a special moment or simply adding a touch of magic to your daily life, My Store is here to make your wardrobe as enchanting as your imagination. Thank you for being a part of our story. Your support means the world to us.</p>
        </div>
    </div>

    <div class="contact-form-container" style="margin-top: 40px; background: rgba(255, 255, 255, 0.9); padding: 25px 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 600px; margin-left: auto; margin-right: auto;">
        <h3 style="text-align: center; color: #b8860b; margin-bottom: 20px;">Contact Us</h3>
        <form action="https://formspree.io/f/xkgbojdq" method="POST">
            <label for="name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a2c00;">Name</label>
            <input type="text" id="name" name="name" required style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #b8860b; border-radius: 6px; font-size: 1rem;">

            <label for="email" style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a2c00;">Email</label>
            <input type="email" id="email" name="_replyto" required style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #b8860b; border-radius: 6px; font-size: 1rem;">

            <label for="message" style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a2c00;">Message</label>
            <textarea id="message" name="message" rows="5" required style="width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #b8860b; border-radius: 6px; font-size: 1rem;"></textarea>

            <button type="submit" style="background-color: #b8860b; color: white; padding: 12px 25px; border: none; border-radius: 8px; font-size: 1.1rem; cursor: pointer; width: 100%;">Send Message</button>
        </form>
    </div>

<div class="developers">
    <h3>Developed By</h3>
    <p>Labony Sur, Aupurba Sarker, Sajeed Awal Sarif, Moontakim Moon and Muntasir Chowdhury — a team of developers dedicated to bringing elegant, user-centered design and functionality to life through thoughtful digital craftsmanship.</p>
</div>

<?php
include '../includes/footer.php';
?>
