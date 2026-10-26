<?php
/**
 * Shared footer
 * Included by every page in public_html/
 */

/* ------------------------------------------------------------------
   DEFAULTS  (yahi values dikhengi agar admin panel se kuch na aaye)
   Social links yahan badlo -> apne asli page ka link daalo.
------------------------------------------------------------------- */
$wkFooter = [
  'about'     => 'A sanctuary for wonder, conservation, and discovery. Meet incredible animals, explore living habitats and connect with the natural world — every visit supports the wildlife we protect.',
  'address'   => "123 Rainforest Road\nWildlife County",
  'email'     => 'hello@wildlifekingdom.com',
  'phone'     => '',
  'instagram' => 'https://www.instagram.com/',
  'linkedin'  => 'https://www.linkedin.com/',
  'facebook'  => 'https://www.facebook.com/',
];

/* Admin panel (Pages & Content / Website Settings) me jo likha ho wo yahan apne aap aa jaata hai.
   Table ya connection na mile to upar wale defaults hi chalte hain, page kabhi nahi tootta. */
if (isset($conn) && $conn instanceof mysqli) {
  try {
    $map = [
      'footer_about'     => 'about',
      'contact_address'  => 'address',
      'contact_email'    => 'email',
      'contact_phone'    => 'phone',
      'social_instagram' => 'instagram',
      'social_linkedin'  => 'linkedin',
      'social_facebook'  => 'facebook',
    ];
    $in  = "'" . implode("','", array_keys($map)) . "'";
    $res = $conn->query("SELECT `key`, `value` FROM settings WHERE `key` IN ($in)");
    if ($res) {
      while ($r = $res->fetch_assoc()) {
        $val = trim((string)($r['value'] ?? ''));
        if ($val !== '' && isset($map[$r['key']])) { $wkFooter[$map[$r['key']]] = $val; }
      }
    }
  } catch (Throwable $e) { /* defaults use honge */ }
}

$wkAddress = nl2br(htmlspecialchars($wkFooter['address'], ENT_QUOTES, 'UTF-8'));
$wkEmail   = htmlspecialchars($wkFooter['email'], ENT_QUOTES, 'UTF-8');
$wkPhone   = htmlspecialchars($wkFooter['phone'], ENT_QUOTES, 'UTF-8');
$wkTel     = preg_replace('/[^0-9+]/', '', $wkFooter['phone']);
$wkAbout   = htmlspecialchars($wkFooter['about'], ENT_QUOTES, 'UTF-8');
?>
<style>
/* ===== Footer layout: har column ka "head row" barabar, isliye logo aur headings ek line me ===== */
footer.site-footer { padding-top: 0 !important; padding-bottom: 0 !important; margin-bottom: 0; }
footer.site-footer .footer-inner {
  display: grid;
  grid-template-columns: 1.5fr 1fr 1fr 1.25fr;
  column-gap: clamp(24px, 3.5vw, 56px);
  row-gap: 0;
  align-items: start;
  width: 100%;
  max-width: 1536px;               /* 1440px content + side padding */
  margin: 0 auto;
  padding: 38px clamp(20px, 3vw, 48px) 30px;
  box-sizing: border-box;
}
footer.site-footer .footer-brand,
footer.site-footer .footer-links,
footer.site-footer .footer-contact { min-width: 0; margin: 0; padding: 0; }

/* Head row: logo ya heading, dono isi height ke andar vertically beech me */
footer.site-footer .fcol-head {
  display: flex;
  align-items: center;
  min-height: 84px;
  margin: 0 0 12px;
}
footer.site-footer .fcol-head h4 {
  margin: 0;
  font-family: 'Playfair Display', Georgia, serif;
  font-size: 1.12rem;
  font-weight: 600;
  line-height: 1.2;
  letter-spacing: .2px;
  color: #f4c542;
}
footer.site-footer .footer-brand .logo { display: inline-flex; align-items: center; margin: -10px 0; }   /* logo row se thoda bahar nikalta hai, isliye footer lamba nahi hota */
footer.site-footer .footer-brand .logo img {
  display: block;
  height: 104px !important;
  width: auto !important;
  max-width: 100%;
  object-fit: contain;
}

/* Body rows: sab ki pehli line ek hi line-height se shuru hoti hai */
footer.site-footer .footer-about,
footer.site-footer .footer-links ul,
footer.site-footer .fc-list { margin: 0; padding: 0; list-style: none; font-size: .94rem; line-height: 1.7; }
footer.site-footer .footer-about { max-width: 400px; color: #a9b79d; }

footer.site-footer .footer-links li { margin: 0 0 7px; padding: 0; }
footer.site-footer .footer-links li:last-child { margin-bottom: 0; }
footer.site-footer .footer-links a,
footer.site-footer .fc-list a {
  color: #e9ecd8; text-decoration: none;
  display: inline-block; transition: color .2s, transform .2s;
}
footer.site-footer .footer-links a:hover,
footer.site-footer .fc-list a:hover { color: #f4c542; transform: translateX(3px); }

/* Contact list with icons */
footer.site-footer .fc-list li { display: flex; align-items: flex-start; gap: 10px; margin: 0 0 9px; color: #e9ecd8; }
footer.site-footer .fc-list li:last-child { margin-bottom: 0; }
footer.site-footer .fc-list svg {
  flex: 0 0 17px; width: 17px; height: 17px; margin-top: 4px;
  fill: none; stroke: #f4c542; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
}
footer.site-footer .fc-list span, footer.site-footer .fc-list a { min-width: 0; overflow-wrap: anywhere; }

/* Social icons */
footer.site-footer .footer-social { display: flex; gap: 10px; margin-top: 16px; }
footer.site-footer .footer-social a {
  display: grid; place-items: center;
  width: 36px; height: 36px; border-radius: 50%;
  color: #f4c542; background: rgba(244, 197, 66, .08);
  border: 1px solid rgba(244, 197, 66, .35);
  transition: background .2s, color .2s, transform .2s, border-color .2s;
}
footer.site-footer .footer-social a:hover { background: #f4c542; color: #0c2d20; border-color: #f4c542; transform: translateY(-3px); }
footer.site-footer .footer-social a:focus-visible { outline: 2px solid #f4c542; outline-offset: 3px; }
footer.site-footer .footer-social svg {
  width: 17px; height: 17px;
  fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
}

/* Copyright bar */
footer.site-footer .footer-bottom {
  border-top: 1px solid rgba(255, 255, 255, .08);
  padding: 14px clamp(20px, 3vw, 48px);
  text-align: center;
}
footer.site-footer .footer-bottom p { margin: 0; font-size: .84rem; color: #8fa08a; }

/* Tablet: 2 columns, brand upar poori width me */
@media (max-width: 999px) {
  footer.site-footer .footer-inner { grid-template-columns: 1fr 1fr; row-gap: 24px; }
  footer.site-footer .footer-brand { grid-column: 1 / -1; }
  footer.site-footer .fcol-head { min-height: 0; margin-bottom: 10px; }
  footer.site-footer .footer-brand .logo { margin: 0; }
  footer.site-footer .footer-brand .logo img { height: 84px !important; }
  footer.site-footer .footer-about { max-width: 520px; }
}
/* Mobile: 1 column */
@media (max-width: 560px) {
  footer.site-footer .footer-inner { grid-template-columns: 1fr; row-gap: 22px; }
  footer.site-footer .footer-brand .logo img { height: 72px !important; }
}
@media (prefers-reduced-motion: reduce) {
  footer.site-footer a { transition: none !important; }
}
</style>

<footer class="site-footer">
  <div class="footer-inner">

    <div class="footer-brand">
      <div class="fcol-head">
        <a href="index.php" class="logo" aria-label="Wildlife Kingdom home">
          <img src="../assets/images/logo/wildlife-kingdom-header.png" alt="Wildlife Kingdom" onerror="this.style.display='none'">
        </a>
      </div>
      <p class="footer-about"><?php echo $wkAbout; ?></p>

      <div class="footer-social" aria-label="Follow us">
        <a href="<?php echo htmlspecialchars($wkFooter['instagram'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
          <svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.4A4 4 0 1 1 12.6 8 4 4 0 0 1 16 11.4z"/><path d="M17.5 6.5h.01"/></svg>
        </a>
        <a href="<?php echo htmlspecialchars($wkFooter['linkedin'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
          <svg viewBox="0 0 24 24"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
        </a>
        <a href="<?php echo htmlspecialchars($wkFooter['facebook'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
          <svg viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
      </div>
    </div>

    <div class="footer-links">
      <div class="fcol-head"><h4>Explore</h4></div>
      <ul>
        <li><a href="animals.php">Animals</a></li>
        <li><a href="habitats.php">Habitats</a></li>
        <li><a href="events.php">Events</a></li>
        <li><a href="gallery.php">Gallery</a></li>
      </ul>
    </div>

    <div class="footer-links">
      <div class="fcol-head"><h4>Visit</h4></div>
      <ul>
        <li><a href="tickets.php">Tickets</a></li>
        <li><a href="visit-us.php">Plan Your Visit</a></li>
        <li><a href="conservation.php">Conservation</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </div>

    <div class="footer-contact">
      <div class="fcol-head"><h4>Get in touch</h4></div>
      <ul class="fc-list">
        <li>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
          <span><?php echo $wkAddress; ?></span>
        </li>
        <li>
          <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
          <a href="mailto:<?php echo $wkEmail; ?>"><?php echo $wkEmail; ?></a>
        </li>
        <?php if ($wkPhone !== ''): ?>
        <li>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
          <a href="tel:<?php echo htmlspecialchars($wkTel, ENT_QUOTES, 'UTF-8'); ?>"><?php echo $wkPhone; ?></a>
        </li>
        <?php endif; ?>
      </ul>
    </div>

  </div>

  <div class="footer-bottom">
    <p>&copy; <?php echo date('Y'); ?> Wildlife Kingdom. All rights reserved.</p>
  </div>
</footer>

<script src="../assets/js/main.js"></script>
</body>
</html>