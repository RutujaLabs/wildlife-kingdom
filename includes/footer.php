<?php
/**
 * Shared footer
 * Included by every page in public_html/
 */
?>
<style>
/* Footer logo — larger */
.site-footer .footer-brand .logo { display: inline-block; margin-bottom: 6px; }
.site-footer .footer-brand .footer-about { margin: 0; max-width: 360px; font-size: .98rem; line-height: 1.75; }
.site-footer .footer-brand .logo img {
  display: block; height: auto !important; width: clamp(120px, 11vw, 150px) !important; max-width: 100%; object-fit: contain;
}
@media (max-width: 600px) { .site-footer .footer-brand .logo img { width: 120px !important; } }
</style>
<footer class="site-footer">
  <div class="footer-inner">
    <div class="footer-brand">
      <a href="index.php" class="logo" aria-label="Wildlife Kingdom home">
        <img src="../assets/images/logo/wildlife-kingdom-header.png" alt="Wildlife Kingdom" onerror="this.style.display='none'">
      </a>
      <p class="footer-about">A sanctuary for wonder, conservation, and discovery. Meet incredible animals, explore living habitats and connect with the natural world — every visit supports the wildlife we protect.</p>
    </div>

    <div class="footer-links">
      <h4>Explore</h4>
      <ul>
        <li><a href="animals.php">Animals</a></li>
        <li><a href="habitats.php">Habitats</a></li>
        <li><a href="events.php">Events</a></li>
        <li><a href="gallery.php">Gallery</a></li>
      </ul>
    </div>

    <div class="footer-links">
      <h4>Visit</h4>
      <ul>
        <li><a href="tickets.php">Tickets</a></li>
        <li><a href="visit-us.php">Plan Your Visit</a></li>
        <li><a href="conservation.php">Conservation</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </div>

    <div class="footer-contact">
      <h4>Get in touch</h4>
      <p>123 Rainforest Road<br>Wildlife County</p>
      <p>hello@wildlifekingdom.com</p>
    </div>
  </div>

  <div class="footer-bottom">
    <p>&copy; <?php echo date('Y'); ?> Wildlife Kingdom. All rights reserved.</p>
  </div>
</footer>

<script src="../assets/js/main.js"></script>
</body>
</html>