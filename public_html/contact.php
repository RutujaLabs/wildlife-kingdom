<?php
$pageTitle = 'Contact';
$pageCss = 'pages.css';
require '../config/db.php';

$formMessage = '';
$formStatus = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $formStatus = 'error';
        $formMessage = 'Please fill in your name, email and message.';
    } else {
        $stmt = $conn->prepare("INSERT INTO enquiries (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);

        if ($stmt->execute()) {
            $formStatus = 'success';
            $formMessage = "Thanks " . $name . ", your message has been received. We'll get back to you soon.";
        } else {
            $formStatus = 'error';
            $formMessage = 'Something went wrong, please try again.';
        }
    }
}

require '../includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <h1>Contact Us</h1>
    <p>Questions, feedback, or planning a group visit? We'd love to hear from you.</p>
  </div>
</section>

<section class="section section-deepest">
  <div class="container contact-grid" data-reveal>
    <div>
      <h2 style="margin-bottom:24px;">Send us a message</h2>

      <?php if ($formMessage): ?>
        <div class="form-msg <?php echo $formStatus; ?>"><?php echo htmlspecialchars($formMessage); ?></div>
      <?php endif; ?>

      <form class="contact-form" method="POST" action="contact.php">
        <label for="name">Full name</label>
        <input type="text" id="name" name="name" placeholder="Your name" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required>

        <label for="phone">Phone (optional)</label>
        <input type="tel" id="phone" name="phone" placeholder="+91 00000 00000">

        <label for="subject">Subject</label>
        <input type="text" id="subject" name="subject" placeholder="What's this about?">

        <label for="message">Message</label>
        <textarea id="message" name="message" placeholder="Tell us more..." required></textarea>

        <button type="submit" class="btn btn-gold">Send Message</button>
      </form>
    </div>

    <div>
      <h2 style="margin-bottom:24px;">Reach us directly</h2>
      <div class="contact-info-list">
        <div><h4>Address</h4><p>123 Rainforest Road, Wildlife County</p></div>
        <div><h4>Phone</h4><p>+91 00000 00000</p></div>
        <div><h4>Email</h4><p>hello@wildlifekingdom.com</p></div>
        <div><h4>Hours</h4><p>Open daily, 9:00 AM – 6:00 PM</p></div>
      </div>
    </div>
  </div>
</section>

<?php require '../includes/footer.php'; ?>