<?= $this->extend('layout/mainclient') ?>

<?= $this->section('body') ?>

<div class="container">
    <h1>Our <span class="highlight">Mission</span></h1>
    <p>Our mission is to provide a seamless and efficient gym management experience through innovative technology, ensuring a hassle-free environment for both clients and coaches. We strive to promote health, fitness, and convenience by integrating QR-based attendance, personalized coaching, and smart scheduling, empowering individuals to achieve their fitness goals with ease. We are committed to enhancing the fitness journey of every member by offering a secure, well-organized, and user-friendly system that maximizes efficiency and accessibility. Our focus is to bridge the gap between technology and fitness, making health management effortless, engaging, and results-driven for everyone, from beginners to seasoned athletes.</p>
    
    <h1>Our <span class="highlight">Vision</span></h1>
    <p>Our vision is to become the leading tech-driven gym management solution that transforms the fitness industry through automation, security, and personalized training experiences. We envision a future where every gym can operate with maximum efficiency, providing members with innovative tools to track their progress, interact with professional coaches, and stay committed to their fitness journey. By integrating cutting-edge technology, we aim to create a fitness environment that is accessible, organized, and engaging. Our long-term goal is to empower gyms and fitness centers worldwide, enabling them to offer superior experiences while fostering a culture of health, wellness, and continuous improvement.</p>
</div>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 20px;
        color: white;
        text-align: center;
        background-color: #2c3e50;
    }
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        background: #2c3e50;
    }
    h1 {
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #ecf0f1;
    }
    p {
        font-size: 1.2rem;
        line-height: 1.6;
        opacity: 0.9;
        text-align: justify;
        color: #bdc3c7;
    }
    .highlight {
        color: #f39c12;
        font-weight: 600;
    }
</style>

<?= $this->endSection() ?>
