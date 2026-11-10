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
/* ==========================================================================
   FOOTER — Wildlife Kingdom (compact, aligned like reference design)
   Background color site ke CSS se hi aayega (yahan set nahi kiya).
   Watermark: ../assets/images/static/footer-watermark.webp
   ========================================================================== */
footer.site-footer {
  --wk-gold: #f4c542;
  --wk-cream: #f3f0dc;
  --wk-wm: clamp(280px, 24vw, 470px);
  position: relative;
  overflow: hidden;
  padding: 0 !important;
  margin: 0;
  isolation: isolate;
}

/* ---- Watermark (right side) ---- */
footer.site-footer::before {
  content: "";
  position: absolute;
  right: 0; top: 0; bottom: 46px;
  width: var(--wk-wm);
  background: url("../assets/images/static/footer-watermark.webp") no-repeat right center / contain;
  opacity: .95;
  pointer-events: none;
  z-index: 0;
}

/* ---- Main grid (desktop) ---- */
footer.site-footer .footer-inner {
  position: relative; z-index: 1;
  display: grid !important;
  grid-template-columns: minmax(260px, 1.5fr) max-content max-content max-content;
  column-gap: 0 !important;
  row-gap: 0;
  align-items: start;
  justify-content: stretch;
  width: 100% !important;
  max-width: none !important;
  margin: 0 !important;
  padding: 22px calc(var(--wk-wm) + 10px) 14px clamp(20px, 2.4vw, 44px) !important;
  box-sizing: border-box;
}
footer.site-footer .footer-brand { min-width: 0; padding-right: clamp(18px, 2.4vw, 44px); }

/* ---- Brand ---- */
footer.site-footer .footer-brand .logo { display: inline-block; line-height: 0; margin: 0 0 10px; }
footer.site-footer .footer-brand .logo img {
  display: block;
  height: clamp(92px, 7.6vw, 118px) !important;
  width: auto !important;
  max-width: 100%;
  object-fit: contain;
}
footer.site-footer .footer-about {
  margin: 0;
  max-width: 360px;
  font-size: .95rem;
  line-height: 1.5;
  color: var(--wk-cream);
}

/* ---- Social ---- */
footer.site-footer .footer-social { display: flex; gap: 14px; margin-top: 14px; }
footer.site-footer .footer-social a {
  display: grid; place-items: center;
  width: 42px; height: 42px; border-radius: 50%;
  color: var(--wk-gold);
  border: 2px solid var(--wk-gold);
  background: transparent;
  transition: background .2s, color .2s, transform .2s;
}
footer.site-footer .footer-social a:hover { background: var(--wk-gold); color: #0c2d20; transform: translateY(-3px); }
footer.site-footer .footer-social a:focus-visible { outline: 2px solid var(--wk-gold); outline-offset: 3px; }
footer.site-footer .footer-social svg { width: 22px; height: 22px; }
footer.site-footer .footer-social svg.is-fill { fill: currentColor; stroke: none; }
footer.site-footer .footer-social svg.is-line { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

/* ---- Link / contact columns with gold vertical divider ---- */
footer.site-footer .footer-links,
footer.site-footer .footer-contact {
  min-width: 0;
  width: auto !important;
  flex: none !important;
  margin: 18px 0 6px !important;
  padding: 2px clamp(22px, 3vw, 50px) 4px clamp(22px, 3vw, 50px) !important;
  border-left: 2px solid var(--wk-gold);
  box-sizing: border-box;
}
footer.site-footer .fcol-head { margin: 0 0 16px; }
footer.site-footer .fcol-head h4 {
  margin: 0;
  font-family: 'Playfair Display', Georgia, serif;
  font-size: clamp(1.25rem, 1.5vw, 1.5rem);
  font-weight: 700;
  line-height: 1.15;
  white-space: nowrap;
  color: var(--wk-gold);
}
footer.site-footer .fcol-head h4::after {
  content: ""; display: block;
  width: 48px; height: 3px; margin-top: 10px;
  background: var(--wk-gold);
}

footer.site-footer .footer-links ul,
footer.site-footer .fc-list { margin: 0; padding: 0; list-style: none; }
footer.site-footer .footer-links li { margin: 0 0 7px; padding: 0; }
footer.site-footer .footer-links li:last-child { margin-bottom: 0; }
footer.site-footer .footer-links a {
  display: inline-flex; align-items: center; gap: 14px;
  white-space: nowrap;
  color: var(--wk-cream); text-decoration: none;
  font-size: 1rem; line-height: 1.6;
  transition: color .2s, transform .2s;
}
footer.site-footer .footer-links a:hover { color: var(--wk-gold); transform: translateX(3px); }

footer.site-footer .fc-ico {
  flex: 0 0 24px; width: 24px; height: 24px;
  fill: none; stroke: var(--wk-gold); stroke-width: 1.8;
  stroke-linecap: round; stroke-linejoin: round;
}
footer.site-footer .fc-ico.is-fill { fill: var(--wk-gold); stroke: none; }

/* Contact list */
footer.site-footer .fc-list li {
  display: flex; align-items: flex-start; gap: 14px;
  margin: 0 0 12px; color: var(--wk-cream);
  font-size: 1rem; line-height: 1.5;
  white-space: nowrap;
}
footer.site-footer .fc-list li:last-child { margin-bottom: 0; }
footer.site-footer .fc-list .fc-ico { margin-top: 2px; }
footer.site-footer .fc-list span, footer.site-footer .fc-list a { min-width: 0; }
footer.site-footer .fc-list a { color: var(--wk-cream); text-decoration: none; transition: color .2s; }
footer.site-footer .fc-list a:hover { color: var(--wk-gold); }

/* ---- Copyright bar with gold lines on both sides ---- */
footer.site-footer .footer-bottom {
  position: relative; z-index: 1;
  display: flex; align-items: center; gap: clamp(16px, 2.6vw, 38px);
  padding: 0 0 16px;
  margin: 0;
  border: 0;
}
footer.site-footer .footer-bottom::before,
footer.site-footer .footer-bottom::after {
  content: ""; flex: 1 1 0; height: 2px; background: var(--wk-gold);
}
footer.site-footer .footer-bottom p {
  margin: 0; flex: 0 0 auto; text-align: center;
  font-size: 1rem; color: var(--wk-cream);
}

/* ---- Tablet / small laptop ---- */
@media (max-width: 1279px) {
  footer.site-footer::before { width: min(60vw, 400px); opacity: .3; bottom: 46px; }
  footer.site-footer .footer-inner {
    grid-template-columns: 1fr 1fr;
    row-gap: 22px;
    padding: 24px clamp(20px, 4vw, 40px) 16px !important;
  }
  footer.site-footer .footer-brand { grid-column: 1 / -1; padding-right: 0; }
  footer.site-footer .footer-about { max-width: 520px; }
  footer.site-footer .footer-links,
  footer.site-footer .footer-contact { margin: 0 !important; padding: 0 0 0 20px !important; }
  footer.site-footer .footer-contact { grid-column: 1 / -1; border-left: 0; padding-left: 0 !important; }
  footer.site-footer .fc-list li { white-space: normal; }
}
/* ---- Mobile ---- */
@media (max-width: 560px) {
  footer.site-footer .footer-inner { grid-template-columns: 1fr; }
  footer.site-footer .footer-links { border-left: 0; padding-left: 0 !important; }
  footer.site-footer .footer-bottom { padding: 0 16px 16px; gap: 12px; }
  footer.site-footer .footer-bottom p { font-size: .88rem; }
}
@media (prefers-reduced-motion: reduce) {
  footer.site-footer a { transition: none !important; }
}
</style>

<footer class="site-footer">
  <div class="footer-inner">

    <div class="footer-brand">
      <a href="index.php" class="logo" aria-label="Wildlife Kingdom home">
        <img src="../assets/images/logo/wildlife-kingdom-header.png" alt="Wildlife Kingdom" onerror="this.style.display='none'">
      </a>
      <p class="footer-about"><?php echo $wkAbout; ?></p>

      <div class="footer-social" aria-label="Follow us">
        <a href="<?php echo htmlspecialchars($wkFooter['instagram'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
          <svg class="is-line" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4.2"/><circle cx="17.3" cy="6.7" r=".6" fill="currentColor"/></svg>
        </a>
        <a href="<?php echo htmlspecialchars($wkFooter['linkedin'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
          <svg class="is-fill" viewBox="0 0 24 24"><path d="M6.94 5a2 2 0 1 1-4-.002 2 2 0 0 1 4 .002zM7 8.48H3V21h4V8.48zm6.32 0H9.34V21h3.94v-6.57c0-3.66 4.77-4 4.77 0V21H22v-7.93c0-6.17-7.06-5.94-8.72-2.91l.04-1.68z"/></svg>
        </a>
        <a href="<?php echo htmlspecialchars($wkFooter['facebook'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
          <svg class="is-fill" viewBox="0 0 24 24"><path d="M13.5 21v-7.5h2.6l.4-3h-3V8.6c0-.9.3-1.5 1.6-1.5h1.6V4.4c-.3 0-1.2-.1-2.3-.1-2.3 0-3.9 1.4-3.9 4v2.2H8v3h2.6V21h2.9z"/></svg>
        </a>
      </div>
    </div>

    <div class="footer-links">
      <div class="fcol-head"><h4>Explore</h4></div>
      <ul>
        <li><a href="animals.php">
          <svg class="fc-ico is-fill" viewBox="0 0 24 24" aria-hidden="true"><ellipse cx="5" cy="11" rx="2.2" ry="2.8"/><ellipse cx="9.2" cy="5.8" rx="2.2" ry="2.9"/><ellipse cx="14.8" cy="5.8" rx="2.2" ry="2.9"/><ellipse cx="19" cy="11" rx="2.2" ry="2.8"/><path d="M12 11c-3 0-6 3.2-6 6 0 2 1.6 3 3.2 3 1 0 1.8-.5 2.8-.5s1.8.5 2.8.5c1.6 0 3.2-1 3.2-3 0-2.8-3-6-6-6z"/></svg>
          Animals</a></li>
        <li><a href="habitats.php">
          <svg class="fc-ico is-fill" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 3.5C11 3.5 4.5 8.5 4.5 16c0 1.2.2 2.2.6 3.1L3 21.2l1.3 1.3 2.2-2.1c.9.4 1.9.6 3 .6 7.5 0 11-6.5 11-17.5z"/><path d="M6.5 18.5C9 13.5 12.5 10 17 8" fill="none" stroke="#0c2d20" stroke-width="1.2" stroke-linecap="round"/></svg>
          Habitats</a></li>
        <li><a href="events.php">
          <svg class="fc-ico" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4.5" width="18" height="16.5" rx="2.2"/><path d="M3 9.5h18M8 2.5v4M16 2.5v4"/><path d="M7.5 13h.01M12 13h.01M16.5 13h.01M7.5 16.8h.01M12 16.8h.01M16.5 16.8h.01" stroke-width="2.4"/></svg>
          Events</a></li>
        <li><a href="gallery.php">
          <svg class="fc-ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 8.2a2 2 0 0 1 2-2h2.4l1.5-2.2h6.2l1.5 2.2H19a2 2 0 0 1 2 2V18a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><circle cx="12" cy="13" r="3.8"/></svg>
          Gallery</a></li>
      </ul>
    </div>

    <div class="footer-links">
      <div class="fcol-head"><h4>Visit</h4></div>
      <ul>
        <li><a href="http://localhost/wildlife-kingdom/public_html/tickets">
          <svg class="fc-ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M3.2 14.3 14.3 3.2a1.6 1.6 0 0 1 2.2 0l1.3 1.3a2.4 2.4 0 0 0 3.7 3.7l.1.1a1.6 1.6 0 0 1 0 2.2L10.5 21.7a1.6 1.6 0 0 1-2.2 0l-1.3-1.3a2.4 2.4 0 0 0-3.7-3.7l-.1-.1a1.6 1.6 0 0 1 0-2.3z" transform="translate(0 -.4)"/><path d="m12.6 8.6 2.8 2.8" stroke-dasharray="1.6 2.2"/></svg>
          Tickets</a></li>
        <li><a href="contact.php">
          <svg class="fc-ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21.5s7-6.2 7-11.5a7 7 0 1 0-14 0c0 5.3 7 11.5 7 11.5z"/><circle cx="12" cy="10" r="2.6"/></svg>
          Plan Your Visit</a></li>
        <li><a href="about.php">
          <svg class="fc-ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.5 4.5 5.2v6.1c0 4.9 3.1 8.6 7.5 10.2 4.4-1.6 7.5-5.3 7.5-10.2V5.2z"/><path d="M15.3 8.2c-3.6.2-5.6 2-5.6 4.8 0 1 .3 1.7.8 2.2l-1.4 1.5.9.8 1.4-1.5c.5.3 1.1.4 1.8.4 2.7 0 4.2-3.2 4.1-8.2z" fill="currentColor" stroke="none" style="color:#f4c542"/></svg>
          Conservation</a></li>
        <li><a href="contact.php">
          <svg class="fc-ico" viewBox="0 0 24 24" aria-hidden="true"><rect x="2.8" y="5" width="18.4" height="14" rx="2"/><path d="m3.2 7 8.8 6.2L20.8 7"/></svg>
          Contact</a></li>
      </ul>
    </div>

    <div class="footer-contact">
      <div class="fcol-head"><h4>Get in touch</h4></div>
      <ul class="fc-list">
        <li>
          <svg class="fc-ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21.5s7-6.2 7-11.5a7 7 0 1 0-14 0c0 5.3 7 11.5 7 11.5z"/><circle cx="12" cy="10" r="2.6"/></svg>
          <span><?php echo $wkAddress; ?></span>
        </li>
        <li>
          <svg class="fc-ico" viewBox="0 0 24 24" aria-hidden="true"><rect x="2.8" y="5" width="18.4" height="14" rx="2"/><path d="m3.2 7 8.8 6.2L20.8 7"/></svg>
          <a href="mailto:<?php echo $wkEmail; ?>"><?php echo $wkEmail; ?></a>
        </li>
        <?php if ($wkPhone !== ''): ?>
        <li>
          <svg class="fc-ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
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