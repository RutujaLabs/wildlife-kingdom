<?php
$pageTitle = 'About Us';
$pageCss = 'pages.css';
require '../includes/header.php';
?>

<style>
/* About page banner */
.about-banner {
  position: relative;
  height: 500px;
  background-color: #0B2F22;
  background-image: url('../assets/images/static/zookeeper-feeding-baby-elephant-zoo.webp');
  background-size: cover;
  background-position: center;
}
.about-banner::after {
  content: "";
  position: absolute;
  left: 0; right: 0; bottom: 0;
  height: 4px;
  background: #e8b52a;
}
</style>

<section class="about-banner" role="img" aria-label="Zookeeper feeding a baby elephant at Wildlife Kingdom"></section>

<style>
@import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap');

/* =====================================================
   About - Section 1 (ab-hero): single story card
   image bleeds to the edge + side tab + quote strip
   ===================================================== */
.ab-hero {
  background: #FFFDF8;
  padding: clamp(18px, 2.4vw, 32px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif;
}
.ab-hero, .ab-hero * { box-sizing: border-box; }
.ab-hero-wrap {
  max-width: 1500px;
  margin: 0 auto;
  padding: 0 clamp(12px, 2vw, 24px);
}
.ab-hero-card {
  border: 1px solid rgba(232, 161, 91, .55);
  border-radius: 20px;
  overflow: hidden;
  background: #fff;
  box-shadow: 0 10px 28px rgba(23, 59, 42, .10);
}
.ab-hero-main {
  display: grid;
  grid-template-columns: minmax(280px, 0.85fr) minmax(0, 1.15fr);
}

/* ---- image cell: pura container image se bhara, side space nahi ---- */
.ab-hero-media {
  display: flex;
  margin: 0;
  min-height: 340px;
  background: linear-gradient(160deg, #173B2A, #0B2F22);
}
.ab-hero-img {
  position: relative;
  flex: 1 1 auto;
  min-width: 0;
  overflow: hidden;
}
.ab-hero-img img {
  position: absolute; inset: 0;
  width: 100%; height: 100%;
  object-fit: cover;                 /* container poora bhara rahega */
  object-position: center;
  display: block;
}
.ab-hero-tab {                       /* vertical label tab, image ke side me */
  flex: 0 0 38px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #E8A15B;
  color: #0B2F22;
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .26em;
  text-transform: uppercase;
  writing-mode: vertical-rl;
  transform: rotate(180deg);
  white-space: nowrap;
}

/* ---- text cell ---- */
.ab-hero-text {
  padding: clamp(18px, 2.4vw, 34px) clamp(18px, 2.8vw, 40px);
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.ab-hero .ab-hero-text h2 {
  margin: 0 0 14px;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.3rem, 2.2vw, 1.9rem);
  font-weight: 700;
  letter-spacing: .01em;
  line-height: 1.12;
  color: #173B2A;
}
.ab-hero .ab-hero-text h2 em {
  font-style: italic;
  font-weight: 600;
  color: #E8A15B;
}
.ab-hero-text p {
  margin: 0 0 11px;
  padding-left: 14px;
  border-left: 3px solid #E8A15B;
  font-size: .95rem;
  line-height: 1.7;
  color: #2B241D;
  text-align: justify;
}
.ab-hero-text p:last-child { margin-bottom: 0; }

/* ---- quote strip ---- */
.ab-hero-quote {
  position: relative;
  margin: 0;
  padding: 14px clamp(44px, 6vw, 80px);
  background: #173B2A;
  border-top: 3px solid #E8A15B;
  text-align: center;
  color: #FFFDF8;
  font-family: 'Playfair Display', Georgia, serif;
  font-style: italic;
  font-size: clamp(.9rem, 1.2vw, 1.05rem);
  line-height: 1.5;
}
.ab-hero-quote::before,
.ab-hero-quote::after {
  position: absolute;
  top: 50%;
  transform: translateY(-38%);
  font-size: 2.8rem;
  line-height: 1;
  color: #E8A15B;
  font-family: 'Playfair Display', Georgia, serif;
}
.ab-hero-quote::before { content: "\201C"; left: clamp(14px, 3vw, 36px); }
.ab-hero-quote::after  { content: "\201D"; right: clamp(14px, 3vw, 36px); }

@media (max-width: 900px) {
  .ab-hero-main { grid-template-columns: 1fr; }
  .ab-hero-media { flex-direction: column; min-height: 0; }
  .ab-hero-img { flex: 0 0 auto; height: 260px; }
  .ab-hero-tab {
    flex: 0 0 auto;
    padding: 9px 16px;
    writing-mode: horizontal-tb;
    transform: none;
  }
}
</style>

<section class="ab-hero">
  <div class="ab-hero-wrap">
    <div class="ab-hero-card">

      <div class="ab-hero-main">
        <figure class="ab-hero-media">
          <figcaption class="ab-hero-tab">About Wildlife Kingdom</figcaption>
          <div class="ab-hero-img">
            <img src="../assets/images/static/about-wildlife-kingdom.webp" alt="Wildlife Kingdom - where curiosity meets the wild" onerror="this.style.display='none'">
          </div>
        </figure>

        <div class="ab-hero-text">
          <h2>Where Curiosity <em>Meets the Wild</em></h2>
          <p>Wildlife Kingdom is a place created for those who want to look beyond the ordinary and experience the natural world in a different way. Here, every animal has a story, every habitat reveals something new, and every visit brings an opportunity to discover the remarkable diversity of life on our planet.</p>
          <p>From the quiet presence of a majestic elephant to the powerful gaze of a big cat, Wildlife Kingdom brings visitors closer to the fascinating world of wildlife. Our spaces are designed to encourage observation, curiosity, and a deeper appreciation for the animals that share our planet.</p>
          <p>A visit here is more than simply seeing animals. It is about discovering how different species live, understanding the environments they depend on, and appreciating the delicate relationships that exist between wildlife and nature. Every habitat offers a glimpse into a different part of the natural world.</p>
        </div>
      </div>

      <blockquote class="ab-hero-quote">Discover the wild. Understand its stories. Protect what makes it extraordinary.</blockquote>

    </div>
  </div>
</section>


<style>
/* =====================================================
   About - Section 2 (ab-exp): dark green, intro + 3 numbered cards
   ===================================================== */
.ab-exp {
  position: relative;
  overflow: hidden;
  background: #173B2A;
  padding: clamp(24px, 3.2vw, 44px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif;
}
.ab-exp, .ab-exp * { box-sizing: border-box; }
.ab-exp::before, .ab-exp::after {      /* soft decorative rings */
  content: "";
  position: absolute;
  border-radius: 50%;
  border: 2px solid rgba(232, 161, 91, .14);
  pointer-events: none;
}
.ab-exp::before { width: 340px; height: 340px; right: -110px; top: -140px; }
.ab-exp::after  { width: 240px; height: 240px; left: -90px; bottom: -110px; }

.ab-exp-wrap {
  position: relative;
  z-index: 1;
  max-width: 1500px;
  margin: 0 auto;
  padding: 0 clamp(12px, 2vw, 24px);
}

/* ---- top row: label + heading | intro ---- */
.ab-exp-top {
  display: grid;
  grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.1fr);
  gap: clamp(18px, 3vw, 44px);
  align-items: center;
  margin-bottom: clamp(18px, 2.4vw, 30px);
}
.ab-exp-label {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin: 0 0 14px;
  padding: 7px 15px;
  border: 1px solid rgba(232, 161, 91, .7);
  border-radius: 999px;
  background: rgba(232, 161, 91, .12);
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .22em;
  text-transform: uppercase;
  color: #FFFDF8;
}
.ab-exp-label::before {
  content: "";
  width: 8px; height: 8px;
  border-radius: 50%;
  background: #E8A15B;
}
.ab-exp .ab-exp-title {
  margin: 0;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.4rem, 2.5vw, 2.1rem);
  font-weight: 700;
  letter-spacing: .01em;
  line-height: 1.15;
  color: #FFFDF8;
}
.ab-exp .ab-exp-title em {
  display: block;
  font-style: italic;
  font-weight: 600;
  color: #E8A15B;
}
.ab-exp-intro {
  margin: 0;
  padding: 4px 0 4px 18px;
  border-left: 4px solid #E8A15B;
  font-size: 1.02rem;
  line-height: 1.75;
  color: #FFFDF8;
  text-align: justify;
}

/* ---- numbered cards ---- */
.ab-exp-cards {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: clamp(14px, 1.8vw, 24px);
  padding-top: 18px;                 /* number badges overlap the card top */
}
.ab-exp-card {
  position: relative;
  padding: 30px clamp(16px, 1.8vw, 24px) 18px;
  background: rgba(255, 253, 248, .06);
  border: 1px solid rgba(232, 161, 91, .38);
  border-radius: 16px;
}
.ab-exp-num {
  position: absolute;
  top: -18px; left: clamp(16px, 1.8vw, 24px);
  width: 38px; height: 38px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: #E8A15B;
  border: 3px solid #173B2A;
  color: #0B2F22;
  font-family: 'Playfair Display', Georgia, serif;
  font-size: .95rem;
  font-weight: 700;
}
.ab-exp-card p {
  margin: 0;
  font-size: .95rem;
  line-height: 1.7;
  color: rgba(255, 253, 248, .9);
  text-align: justify;
}

@media (max-width: 900px) {
  .ab-exp-top { grid-template-columns: 1fr; }
  .ab-exp-cards { grid-template-columns: 1fr; gap: 28px; }
}
</style>

<section class="ab-exp">
  <div class="ab-exp-wrap">

    <div class="ab-exp-top">
      <div>
        <span class="ab-exp-label">The Wildlife Kingdom Experience</span>
        <h2 class="ab-exp-title">Not Just Something to See. <em>Something to Understand.</em></h2>
      </div>
      <p class="ab-exp-intro">Wildlife is often remembered through photographs, names, and fascinating facts. But the real story begins when we start understanding the lives behind those moments &mdash; how animals behave, where they come from, what they need to survive, and how closely their lives are connected to the world around them.</p>
    </div>

    <div class="ab-exp-cards">
      <article class="ab-exp-card">
        <span class="ab-exp-num">01</span>
        <p>At Wildlife Kingdom, we believe that seeing an animal is only the beginning of the experience. A closer look can reveal remarkable behaviours, unique adaptations, complex social relationships, and the environments that make each species extraordinary.</p>
      </article>
      <article class="ab-exp-card">
        <span class="ab-exp-num">02</span>
        <p>Every habitat tells a different story. The shape of a landscape, the availability of water, the vegetation around it, and even the way an animal moves through its surroundings can reveal how perfectly life adapts to its environment.</p>
      </article>
      <article class="ab-exp-card">
        <span class="ab-exp-num">03</span>
        <p>That is why Wildlife Kingdom brings together animals, habitats, discovery, and learning as one experience. Instead of simply moving from one exhibit to another, visitors are encouraged to pause, observe, ask questions, and look at wildlife with a deeper sense of curiosity.</p>
      </article>
    </div>

  </div>
</section>

<style>
/* =====================================================
   About - Section 3 (ab-story): story thread + animated timeline
   ===================================================== */
.ab-story {
  background: #FFFDF8;
  padding: clamp(24px, 3.2vw, 44px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif;
  overflow: hidden;
}
.ab-story, .ab-story * { box-sizing: border-box; }
.ab-story-wrap {
  max-width: 1500px;
  margin: 0 auto;
  padding: 0 clamp(12px, 2vw, 24px);
}

/* ---- scroll reveal (sirf JS chalne par) ---- */
.ab-story.js-anim:not(.is-in) [data-r] { opacity: 0; transform: translateY(18px); }
.ab-story [data-r] {
  transition: opacity .7s ease, transform .7s ease;
  transition-delay: calc(var(--d, 0) * 1s);
}

/* ---- top: heading + intro | story thread ---- */
.ab-story-top {
  display: grid;
  grid-template-columns: minmax(0, 0.8fr) minmax(0, 1.2fr);
  gap: clamp(18px, 3vw, 44px);
  align-items: stretch;
}
.ab-story-copy {
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 14px;
}

/* ---- image: corner brackets + wipe reveal + slow zoom ---- */
.ab-story-media {
  position: relative;
  margin: 0;
  min-height: 320px;
  border-radius: 20px;
  background: linear-gradient(160deg, #173B2A, #0B2F22);
  box-shadow: 0 10px 28px rgba(23, 59, 42, .14);
}
.ab-story-media::before,
.ab-story-media::after {              /* orange corner brackets */
  content: "";
  position: absolute;
  width: 54px; height: 54px;
  border: 4px solid #E8A15B;
  pointer-events: none;
  z-index: 2;
}
.ab-story-media::before { top: -8px; left: -8px; border-right: 0; border-bottom: 0; border-radius: 22px 0 0 0; }
.ab-story-media::after  { bottom: -8px; right: -8px; border-left: 0; border-top: 0; border-radius: 0 0 22px 0; }
.ab-story-img {
  position: absolute; inset: 0;
  border-radius: 20px;
  overflow: hidden;
  clip-path: inset(0);
  transition: clip-path 1.3s cubic-bezier(.65, 0, .35, 1) .2s;
}
.ab-story.js-anim:not(.is-in) .ab-story-img { clip-path: inset(0 100% 0 0); }
.ab-story-img img {
  width: 100%; height: 100%;
  object-fit: cover; object-position: center;
  display: block;
}
.ab-story.is-in .ab-story-img img { animation: abZoom 16s ease-in-out 1.5s infinite alternate; }
@keyframes abZoom { from { transform: scale(1); } to { transform: scale(1.07); } }
.ab-story-label {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin: 0 0 12px;
  padding: 7px 15px;
  border: 1px solid rgba(232, 161, 91, .7);
  border-radius: 999px;
  background: rgba(232, 161, 91, .12);
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .22em;
  text-transform: uppercase;
  color: #173B2A;
}
.ab-story-label::before {
  content: "";
  width: 8px; height: 8px;
  border-radius: 50%;
  background: #E8A15B;
  animation: abBlink 2s ease-in-out infinite;
}
.ab-story .ab-story-title {
  margin: 0 0 12px;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.4rem, 2.5vw, 2.1rem);
  font-weight: 700;
  letter-spacing: .01em;
  line-height: 1.15;
  color: #173B2A;
}
.ab-story .ab-story-title em {
  font-style: italic;
  font-weight: 600;
  color: #E8A15B;
}
.ab-story-intro {
  margin: 0;
  font-family: 'Playfair Display', Georgia, serif;
  font-style: italic;
  font-size: clamp(.98rem, 1.3vw, 1.12rem);
  line-height: 1.65;
  color: #173B2A;
  text-align: justify;
}

.ab-story-body {
  position: relative;
  padding-left: 26px;
}
.ab-story-body::before {              /* thread line - grows when visible */
  content: "";
  position: absolute;
  left: 5px; top: 8px; bottom: 8px;
  width: 2px;
  background: linear-gradient(#E8A15B, rgba(232, 161, 91, .25));
  transform-origin: top;
  transition: transform 1.6s ease .3s;
}
.ab-story.js-anim:not(.is-in) .ab-story-body::before { transform: scaleY(0); }
.ab-story-body p {
  position: relative;
  margin: 0 0 11px;
  font-size: .95rem;
  line-height: 1.7;
  color: #2B241D;
  text-align: justify;
}
.ab-story-body p:last-child { margin-bottom: 0; }
.ab-story-body p::before {            /* dot on the thread */
  content: "";
  position: absolute;
  left: -26px; top: .5em;
  width: 12px; height: 12px;
  border-radius: 50%;
  background: #E8A15B;
  border: 3px solid #FFFDF8;
  box-shadow: 0 0 0 1px #E8A15B;
}

/* ---- timeline panel ---- */
.ab-st-panel {
  position: relative;
  margin-top: clamp(18px, 2.4vw, 28px);
  padding: clamp(18px, 2.4vw, 28px) clamp(14px, 2vw, 28px) clamp(16px, 2vw, 24px);
  background: #173B2A;
  border-radius: 20px;
  border-bottom: 4px solid #E8A15B;
  box-shadow: 0 12px 30px rgba(23, 59, 42, .18);
  overflow: hidden;
}
.ab-st-panel::before {                /* soft ring decoration */
  content: "";
  position: absolute;
  width: 260px; height: 260px;
  right: -90px; top: -120px;
  border-radius: 50%;
  border: 2px solid rgba(232, 161, 91, .15);
  pointer-events: none;
}
.ab-st-head {
  position: relative;
  margin: 0 0 clamp(16px, 2vw, 24px);
  text-align: center;
  font-family: 'Playfair Display', Georgia, serif;
  font-size: clamp(1.2rem, 2vw, 1.6rem);
  font-weight: 700;
  color: #FFFDF8;
}
.ab-st-head::after {
  content: "";
  display: block;
  width: 64px; height: 3px;
  margin: 10px auto 0;
  border-radius: 3px;
  background: #E8A15B;
}

.ab-st-steps {
  position: relative;
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 10px;
}
.ab-st-steps::before,
.ab-st-steps::after {                 /* base line + animated fill line */
  content: "";
  position: absolute;
  top: 40px;
  left: 12.5%; right: 12.5%;
  height: 3px;
  border-radius: 3px;
}
.ab-st-steps::before { background: rgba(255, 253, 248, .16); }
.ab-st-steps::after {
  background: linear-gradient(90deg, #E8A15B, #f3c28f);
  transform-origin: left;
  transition: transform 2.6s ease .4s;
}
.ab-story.js-anim:not(.is-in) .ab-st-steps::after { transform: scaleX(0); }

.ab-st-step {
  position: relative;
  z-index: 1;
  padding: 12px 10px 12px;
  text-align: center;
  border: 1px solid transparent;
  border-radius: 16px;
  cursor: pointer;
  outline: none;
  transition: background .35s ease, border-color .35s ease;
}
.ab-story.js-anim:not(.is-in) .ab-st-step { opacity: 0; }
.ab-story.js-anim.is-in .ab-st-step {          /* ek ek karke upar aate hain */
  animation: abRise .7s ease both;
  animation-delay: calc(var(--i) * .5s);
}
.ab-st-step.is-active,
.ab-st-step:focus-visible {
  background: rgba(255, 253, 248, .07);
  border-color: rgba(232, 161, 91, .5);
}
.ab-st-node {
  width: 56px; height: 56px;
  margin: 0 auto 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: #173B2A;
  border: 2px solid #E8A15B;
  font-size: 1.5rem;
  transition: background .35s ease, transform .35s ease;
}
.ab-st-emoji { display: inline-block; line-height: 1; }
.ab-st-step.is-active .ab-st-node {
  background: #E8A15B;
  transform: scale(1.12);
  animation: abPulse 1.8s ease-out infinite;
}
.ab-st-step.is-active .ab-st-emoji { animation: abBob 1.6s ease-in-out infinite; }
.ab-st-no {
  display: block;
  margin-bottom: 4px;
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .2em;
  text-transform: uppercase;
  color: #E8A15B;
}
.ab-st-step strong {
  display: block;
  margin-bottom: 4px;
  font-family: 'Playfair Display', Georgia, serif;
  font-size: 1.02rem;
  font-weight: 700;
  color: #FFFDF8;
}
.ab-st-step p {
  margin: 0;
  font-size: .88rem;
  line-height: 1.55;
  color: rgba(255, 253, 248, .82);
}

@keyframes abPulse {
  0%   { box-shadow: 0 0 0 0 rgba(232, 161, 91, .55); }
  100% { box-shadow: 0 0 0 16px rgba(232, 161, 91, 0); }
}
@keyframes abBob {
  0%, 100% { transform: translateY(0); }
  50%      { transform: translateY(-3px); }
}
@keyframes abRise {
  from { opacity: 0; transform: translateY(22px); }
  to   { opacity: 1; transform: none; }
}
@keyframes abBlink {
  0%, 100% { opacity: 1; }
  50%      { opacity: .35; }
}

@media (max-width: 900px) {
  .ab-story-top { grid-template-columns: 1fr; }
  .ab-story-media { min-height: 0; height: 260px; }
  .ab-st-steps { grid-template-columns: 1fr; gap: 6px; }
  .ab-st-steps::before,
  .ab-st-steps::after {               /* vertical line on mobile */
    top: 40px; bottom: 40px;
    left: 38px; right: auto;
    width: 3px; height: auto;
  }
  .ab-st-steps::after { transform-origin: top; }
  .ab-story.js-anim:not(.is-in) .ab-st-steps::after { transform: scaleY(0); }
  .ab-st-step {
    display: grid;
    grid-template-columns: 56px minmax(0, 1fr);
    column-gap: 14px;
    align-items: start;
    text-align: left;
    padding: 10px;
  }
  .ab-st-node { grid-row: 1 / span 3; margin: 0; }
}

@media (prefers-reduced-motion: reduce) {
  .ab-story *, .ab-story *::before, .ab-story *::after {
    animation: none !important;
    transition: none !important;
  }
}
</style>

<section class="ab-story" id="abStory">
  <div class="ab-story-wrap">

    <div class="ab-story-top">

      <figure class="ab-story-media">
        <div class="ab-story-img">
          <img src="../assets/images/static/about-species-story.webp" alt="Every species has a story at Wildlife Kingdom" onerror="this.style.display='none'">
        </div>
      </figure>

      <div class="ab-story-copy">
        <div data-r>
          <span class="ab-story-label">The Stories Behind the Wild</span>
          <h2 class="ab-story-title">Every Species <em>Has a Story.</em></h2>
        </div>

        <div class="ab-story-body" data-r style="--d:.15">
          <p>At Wildlife Kingdom, we encourage visitors to look beyond the surface. Every species has developed remarkable ways to find food, communicate, protect itself, raise its young, and survive within its environment.</p>
          <p>These behaviours are not random. They are shaped by millions of years of adaptation and by the habitats in which animals live. Understanding these small details can completely change the way we experience wildlife.</p>
          <p>From the strength of a predator to the patience of a herbivore, from the intelligence of social animals to the incredible adaptations of birds and reptiles, every species has something unique to reveal.</p>
        </div>
      </div>

    </div>

    <div class="ab-st-panel" data-r style="--d:.2">
      <h3 class="ab-st-head">Look Beyond the Animal</h3>

      <ol class="ab-st-steps" id="abSteps">
        <li class="ab-st-step" style="--i:0" tabindex="0">
          <span class="ab-st-node"><span class="ab-st-emoji">&#128064;</span></span>
          <span class="ab-st-no">01 &mdash; Observe</span>
          <strong>Watch the little things.</strong>
          <p>Notice movement, behaviour, sounds, interactions and reactions.</p>
        </li>
        <li class="ab-st-step" style="--i:1" tabindex="0">
          <span class="ab-st-node"><span class="ab-st-emoji">&#129504;</span></span>
          <span class="ab-st-no">02 &mdash; Discover</span>
          <strong>Ask why.</strong>
          <p>Why does an animal behave this way? What helps it survive?</p>
        </li>
        <li class="ab-st-step" style="--i:2" tabindex="0">
          <span class="ab-st-node"><span class="ab-st-emoji">&#127807;</span></span>
          <span class="ab-st-no">03 &mdash; Understand</span>
          <strong>See the connection.</strong>
          <p>Discover how the animal depends on its habitat and ecosystem.</p>
        </li>
        <li class="ab-st-step" style="--i:3" tabindex="0">
          <span class="ab-st-node"><span class="ab-st-emoji">&#10084;&#65039;</span></span>
          <span class="ab-st-no">04 &mdash; Appreciate</span>
          <strong>See wildlife differently.</strong>
          <p>Understanding creates a deeper respect for the lives and environments around us.</p>
        </li>
      </ol>
    </div>

  </div>
</section>

<script>
/* Section 3: scroll par animation start + steps ek ek karke active hote hain.
   Hover / click / keyboard se koi bhi step khud select kar sakte ho. */
(function () {
  var sec = document.getElementById('abStory');
  if (!sec) return;
  var steps = Array.prototype.slice.call(sec.querySelectorAll('.ab-st-step'));
  var list  = document.getElementById('abSteps');
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var cur = -1, timer = null, paused = false;

  function setActive(i) {
    cur = i;
    steps.forEach(function (st, k) { st.classList.toggle('is-active', k === i); });
  }
  function start() {
    if (reduce || timer) return;
    setActive(0);
    timer = setInterval(function () {
      if (!paused) setActive((cur + 1) % steps.length);
    }, 2800);
  }

  steps.forEach(function (st, i) {
    st.addEventListener('mouseenter', function () { paused = true; setActive(i); });
    st.addEventListener('click',      function () { paused = true; setActive(i); });
    st.addEventListener('focus',      function () { paused = true; setActive(i); });
  });
  list.addEventListener('mouseleave', function () { paused = false; });
  list.addEventListener('focusout',   function () { paused = false; });

  sec.classList.add('js-anim');

  function reveal() {
    sec.classList.add('is-in');
    setTimeout(start, 1200);
  }

  if (reduce || !('IntersectionObserver' in window)) { reveal(); return; }

  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (en) {
      if (en.isIntersecting) { reveal(); io.disconnect(); }
    });
  }, { threshold: 0.18 });
  io.observe(sec);
})();
</script>

<style>
/* =====================================================
   About - Section 4 (ab-eco): ecosystem network
   full-width dark green · left: text + diagram · right: image with live info card
   ===================================================== */
.ab-eco {
  position: relative;
  overflow: hidden;
  background: #173B2A;
  padding: clamp(16px, 2vw, 26px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif;
  color: #FFFDF8;
}
.ab-eco, .ab-eco * { box-sizing: border-box; }
.ab-eco::before, .ab-eco::after {
  content: ""; position: absolute; border-radius: 50%;
  border: 2px solid rgba(232, 161, 91, .14); pointer-events: none;
}
.ab-eco::before { width: 320px; height: 320px; left: -120px; top: -150px; }
.ab-eco::after  { width: 240px; height: 240px; right: -90px; bottom: -110px; }
.ab-eco-wrap {
  position: relative; z-index: 1;
  max-width: 1500px; margin: 0 auto;
  padding: 0 clamp(12px, 2vw, 24px);
}

/* reveal */
.ab-eco.js-anim:not(.is-in) [data-r] { opacity: 0; transform: translateY(18px); }
.ab-eco [data-r] { transition: opacity .7s ease, transform .7s ease; transition-delay: calc(var(--d, 0) * 1s); }

/* ---- main: left column | image right ---- */
.ab-eco-main {
  display: grid;
  grid-template-columns: minmax(0, 1.5fr) minmax(0, .5fr);
  gap: clamp(12px, 1.6vw, 20px);
  align-items: stretch;
}
.ab-eco-left { display: flex; flex-direction: column; gap: clamp(10px, 1.4vw, 16px); min-width: 0; }

/* head: title | paragraphs */
.ab-eco-head {
  display: grid;
  grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr);
  gap: clamp(14px, 2.4vw, 32px);
  align-items: center;
}
.ab-eco-label {
  display: inline-flex; align-items: center; gap: 10px;
  margin: 0 0 8px; padding: 6px 14px;
  border: 1px solid rgba(232, 161, 91, .7);
  border-radius: 999px; background: rgba(232, 161, 91, .12);
  font-size: .66rem; font-weight: 700;
  letter-spacing: .22em; text-transform: uppercase; color: #FFFDF8;
}
.ab-eco-label::before {
  content: ""; width: 8px; height: 8px; border-radius: 50%;
  background: #E8A15B; animation: abBlink 2s ease-in-out infinite;
}
.ab-eco .ab-eco-title {
  margin: 0;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.4rem, 2.5vw, 2.1rem);
  font-weight: 700; letter-spacing: .01em; line-height: 1.12; color: #FFFDF8;
}
.ab-eco .ab-eco-title em { display: block; font-style: italic; font-weight: 600; color: #E8A15B; }
.ab-eco-body { padding-left: 16px; border-left: 3px solid #E8A15B; }
.ab-eco-body p {
  margin: 0 0 7px; font-size: .9rem; line-height: 1.6;
  color: rgba(255, 253, 248, .9); text-align: justify;
}
.ab-eco-body p:last-child { margin-bottom: 0; }

/* ---- diagram: fills the whole left column ---- */
.ab-eco-map {
  position: relative;
  flex: 1 1 auto;
  min-height: 330px;
  background:
    radial-gradient(circle at 50% 50%, rgba(232, 161, 91, .10), transparent 62%),
    rgba(255, 253, 248, .05);
  border: 1px solid rgba(232, 161, 91, .38);
  border-radius: 20px;
}
.ab-eco-svg { position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; overflow: visible; }
.ab-eco-svg path {
  fill: none; stroke: rgba(255, 253, 248, .22); stroke-width: 1.4;
  vector-effect: non-scaling-stroke;
  transition: stroke .3s ease, opacity .3s ease, stroke-width .3s ease;
}
.ab-eco-svg path.is-chain { stroke: rgba(232, 161, 91, .55); stroke-width: 2; stroke-dasharray: 7 7; animation: abFlow 1.2s linear infinite; }
.ab-eco-map.has-active .ab-eco-svg path { opacity: .1; }
.ab-eco-map.has-active .ab-eco-svg path.is-on { opacity: 1; stroke: #E8A15B; stroke-width: 2.8; }
@keyframes abFlow { to { stroke-dashoffset: -14; } }

.ab-eco-core {
  position: absolute; left: 50%; top: 50%;
  transform: translate(-50%, -50%);
  width: 24%; text-align: center; pointer-events: none;
  font-family: 'Playfair Display', Georgia, serif;
  font-style: italic; font-size: clamp(.72rem, 1.05vw, .95rem);
  line-height: 1.3; color: rgba(255, 253, 248, .85);
  background: #173B2A; padding: 8px; border-radius: 14px;
}
.ab-eco-core b { display: block; color: #E8A15B; font-weight: 600; }

.ab-eco-node {
  position: absolute; z-index: 2;
  transform: translate(-50%, -50%);
  display: flex; flex-direction: column; align-items: center; gap: 4px;
  padding: 0; background: none; border: 0; cursor: pointer;
  font-family: inherit; color: #FFFDF8;
  transition: opacity .3s ease; outline: none;
}
.ab-eco-ico {
  width: 50px; height: 50px;
  display: flex; align-items: center; justify-content: center;
  border-radius: 50%; background: #173B2A;
  border: 2px solid #E8A15B;
  font-size: 1.35rem; line-height: 1;
  transition: background .3s ease, transform .3s ease;
}
.ab-eco-node.is-sat .ab-eco-ico { width: 38px; height: 38px; font-size: 1.05rem; border: 2px dashed rgba(232, 161, 91, .8); }
.ab-eco-nm {
  font-size: .62rem; font-weight: 700;
  letter-spacing: .1em; text-transform: uppercase;
  color: rgba(255, 253, 248, .92); white-space: nowrap;
}
.ab-eco-map.has-active .ab-eco-node { opacity: .32; }
.ab-eco-map.has-active .ab-eco-node.is-linked { opacity: 1; }
.ab-eco-map.has-active .ab-eco-node.is-linked .ab-eco-ico { background: rgba(232, 161, 91, .35); }
.ab-eco-node.is-on { opacity: 1 !important; }
.ab-eco-node.is-on .ab-eco-ico { background: #E8A15B; transform: scale(1.15); animation: abPulse 1.8s ease-out infinite; }
.ab-eco-node:focus-visible .ab-eco-ico { box-shadow: 0 0 0 3px rgba(255, 253, 248, .8); }
.ab-eco.js-anim:not(.is-in) .ab-eco-node { opacity: 0; }
.ab-eco.js-anim.is-in .ab-eco-map:not(.has-active) .ab-eco-node { animation: abRise .7s ease both; animation-delay: calc(.2s + var(--i) * .12s); }

/* ---- image (right) with info card BELOW the image ---- */
.ab-eco-media {
  position: relative; margin: 0;
  display: flex; flex-direction: column;
  min-height: 420px;
  border-radius: 20px;
  border: 1px solid rgba(232, 161, 91, .5);
  background: #0B2F22;
  box-shadow: 0 14px 34px rgba(0, 0, 0, .28);
  overflow: hidden;
}
.ab-eco-img {
  position: relative; flex: 1 1 auto;
  min-height: 240px; overflow: hidden;
  background: linear-gradient(160deg, #1f4d38, #0B2F22);
  clip-path: inset(0);
  transition: clip-path 1.3s cubic-bezier(.65, 0, .35, 1) .2s;
}
.ab-eco.js-anim:not(.is-in) .ab-eco-img { clip-path: inset(100% 0 0 0); }
.ab-eco-img img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; display: block; }
.ab-eco.is-in .ab-eco-img img { animation: abZoom 16s ease-in-out 1.5s infinite alternate; }
.ab-eco-tag {                          /* small label on image */
  position: absolute; top: 12px; left: 12px; z-index: 2;
  padding: 5px 12px; border-radius: 999px;
  background: #E8A15B; color: #0B2F22;
  font-size: .62rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase;
}
.ab-eco-info {                         /* sits under the image, fixed height so nothing jumps */
  position: relative; z-index: 2; flex: 0 0 auto;
  min-height: 178px;
  padding: 12px 16px 14px;
  background: #0B2F22;
  border-top: 1px solid rgba(232, 161, 91, .45);
  border-bottom: 3px solid #E8A15B;
}
.ab-eco-info-in { animation: abRise .5s ease both; }
.ab-eco-info-hd { display: flex; align-items: center; gap: 10px; margin-bottom: 6px; }
.ab-eco-info-ico { font-size: 1.5rem; line-height: 1; }
.ab-eco-info-kick {
  display: block; margin-bottom: 1px;
  font-size: .6rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #E8A15B;
}
.ab-eco .ab-eco-info h3 {
  margin: 0;
  font-family: 'Playfair Display', Georgia, serif;
  font-size: clamp(1rem, 1.3vw, 1.15rem);
  letter-spacing: .08em; text-transform: uppercase; color: #FFFDF8;
}
.ab-eco-info p { margin: 0 0 6px; font-size: .8rem; line-height: 1.45; color: rgba(255, 253, 248, .92); }
.ab-eco-links { display: flex; flex-wrap: wrap; gap: 5px; }
.ab-eco-links span {
  padding: 3px 9px;
  border: 1px solid rgba(232, 161, 91, .6); border-radius: 999px;
  font-size: .58rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
  color: #FFFDF8; background: rgba(232, 161, 91, .12);
}

/* ---- closing strip ---- */
.ab-eco-close {
  margin: clamp(10px, 1.4vw, 16px) 0 0;
  padding: 10px clamp(14px, 3vw, 40px);
  text-align: center;
  background: rgba(255, 253, 248, .06);
  border: 1px solid rgba(232, 161, 91, .38);
  border-left: 4px solid #E8A15B;
  border-radius: 14px;
  font-family: 'Playfair Display', Georgia, serif;
  font-style: italic;
  font-size: clamp(.88rem, 1.15vw, 1rem);
  line-height: 1.55;
  color: rgba(255, 253, 248, .94);
}

@media (max-width: 1000px) {
  .ab-eco-main { grid-template-columns: 1fr; }
  .ab-eco-media { min-height: 560px; }
  .ab-eco-map { min-height: 340px; }
}
@media (max-width: 700px) {
  .ab-eco-head { grid-template-columns: 1fr; gap: 10px; }
  .ab-eco-map { min-height: 400px; }
  .ab-eco-ico { width: 42px; height: 42px; font-size: 1.15rem; }
  .ab-eco-node.is-sat .ab-eco-ico { width: 34px; height: 34px; font-size: .95rem; }
  .ab-eco-nm { font-size: .54rem; letter-spacing: .05em; }
  .ab-eco-core { display: none; }
  .ab-eco-media { min-height: 520px; }
}
@media (prefers-reduced-motion: reduce) {
  .ab-eco *, .ab-eco *::before, .ab-eco *::after { animation: none !important; transition: none !important; }
}
</style>

<section class="ab-eco" id="abEco">
  <div class="ab-eco-wrap">

    <div class="ab-eco-main">

      <div class="ab-eco-left">
        <div class="ab-eco-head" data-r>
          <div>
            <span class="ab-eco-label">The Bigger Picture</span>
            <h2 class="ab-eco-title">Nothing in Nature <em>Exists Alone.</em></h2>
          </div>
          <div class="ab-eco-body">
            <p>One small change can travel through an entire ecosystem. When vegetation changes, herbivores feel it. When herbivores change, predators feel it. When water becomes scarce, whole habitats must adapt.</p>
            <p>A bird spreading seeds, an insect pollinating a flower, a predator controlling a population, a tree giving shelter &mdash; each plays a part. Wildlife Kingdom invites visitors to see nature as a living network, not a collection of individual species.</p>
          </div>
        </div>

        <div class="ab-eco-map" id="abEcoMap" data-r style="--d:.1">
          <svg class="ab-eco-svg" id="abEcoSvg" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"></svg>
          <div class="ab-eco-core"><b>One World.</b>Many Connections.</div>

          <!-- main cycle: Sun > Plants > Herbivores > Predators > Decomposers > Soil > Plants -->
          <button class="ab-eco-node" style="left:50%;top:12%;--i:0" data-id="sun" data-x="50" data-y="12"><span class="ab-eco-ico">&#9728;&#65039;</span><span class="ab-eco-nm">Sun</span></button>
          <button class="ab-eco-node" style="left:76%;top:36%;--i:1" data-id="plants" data-x="76" data-y="36"><span class="ab-eco-ico">&#127795;</span><span class="ab-eco-nm">Plants</span></button>
          <button class="ab-eco-node" style="left:76%;top:68%;--i:2" data-id="herbivores" data-x="76" data-y="68"><span class="ab-eco-ico">&#129420;</span><span class="ab-eco-nm">Herbivores</span></button>
          <button class="ab-eco-node" style="left:50%;top:88%;--i:3" data-id="predators" data-x="50" data-y="88"><span class="ab-eco-ico">&#129409;</span><span class="ab-eco-nm">Predators</span></button>
          <button class="ab-eco-node" style="left:24%;top:68%;--i:4" data-id="decomposers" data-x="24" data-y="68"><span class="ab-eco-ico">&#127812;</span><span class="ab-eco-nm">Decomposers</span></button>
          <button class="ab-eco-node" style="left:24%;top:36%;--i:5" data-id="soil" data-x="24" data-y="36"><span class="ab-eco-ico">&#127793;</span><span class="ab-eco-nm">Soil</span></button>

          <!-- branches -->
          <button class="ab-eco-node is-sat" style="left:7%;top:14%;--i:6" data-id="climate" data-x="7" data-y="14" data-links="sun,plants,soil"><span class="ab-eco-ico">&#127757;</span><span class="ab-eco-nm">Climate</span></button>
          <button class="ab-eco-node is-sat" style="left:7%;top:50%;--i:7" data-id="vegetation" data-x="7" data-y="50" data-links="plants,soil,herbivores"><span class="ab-eco-ico">&#127807;</span><span class="ab-eco-nm">Vegetation</span></button>
          <button class="ab-eco-node is-sat" style="left:7%;top:86%;--i:8" data-id="animals" data-x="7" data-y="86" data-links="herbivores,predators,decomposers"><span class="ab-eco-ico">&#128024;</span><span class="ab-eco-nm">Animals</span></button>
          <button class="ab-eco-node is-sat" style="left:93%;top:14%;--i:9" data-id="water" data-x="93" data-y="14" data-links="plants,herbivores,predators"><span class="ab-eco-ico">&#128167;</span><span class="ab-eco-nm">Water</span></button>
          <button class="ab-eco-node is-sat" style="left:93%;top:50%;--i:10" data-id="birds" data-x="93" data-y="50" data-links="plants,predators,insects"><span class="ab-eco-ico">&#128038;</span><span class="ab-eco-nm">Birds</span></button>
          <button class="ab-eco-node is-sat" style="left:93%;top:86%;--i:11" data-id="insects" data-x="93" data-y="86" data-links="plants,decomposers,birds"><span class="ab-eco-ico">&#129419;</span><span class="ab-eco-nm">Insects</span></button>
        </div>
      </div>

      <figure class="ab-eco-media" data-r style="--d:.15">
        <div class="ab-eco-img">
          <img src="../assets/images/static/zoo-wildlife-exhibit-elephant-zebra.webp" alt="A living ecosystem at Wildlife Kingdom" onerror="this.style.display='none'">
        </div>
        <span class="ab-eco-tag">One World</span>
        <aside class="ab-eco-info" id="abEcoInfo" aria-live="polite">
          <div class="ab-eco-info-in" id="abEcoInfoIn"></div>
        </aside>
      </figure>

    </div>

    <p class="ab-eco-close" data-r>This is the bigger story behind wildlife &mdash; relationships, dependence, adaptation and balance. Once you see the connections, a habitat becomes a living world where everything has a role.</p>

  </div>
</section>

<script>
/* Section 4: ecosystem network - hover/click/focus lights up connected elements.
   Auto-cycles through the elements until the visitor interacts. */
(function () {
  var sec = document.getElementById('abEco');
  if (!sec) return;
  var map = document.getElementById('abEcoMap');
  var svg = document.getElementById('abEcoSvg');
  var infoIn = document.getElementById('abEcoInfoIn');
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  var INFO = {
    sun:         { k: 'Energy source',  t: 'Almost all life on Earth begins here. Sunlight powers plants, drives the climate and sets the rhythm of every habitat.' },
    plants:      { k: 'The foundation', t: 'They provide food, shelter and oxygen while forming the foundation of countless food chains.' },
    herbivores:  { k: 'Link in the chain', t: 'By grazing and browsing, they turn plant energy into food for predators and shape the landscape as they move.' },
    predators:   { k: 'Keepers of balance', t: 'Predators help regulate animal populations and maintain balance within their ecosystems.' },
    decomposers: { k: 'Nature\u2019s recyclers', t: 'Fungi and microbes break down what has died and return its nutrients to the earth, so nothing is wasted.' },
    soil:        { k: 'Living ground',  t: 'Soil stores nutrients and water and holds roots in place. It is where the end of one life becomes the start of another.' },
    water:       { k: 'Essential',      t: 'Every habitat depends on it. From drinking and cooling to growing vegetation, water influences almost every part of an ecosystem.' },
    vegetation:  { k: 'Shelter & food', t: 'Grasses, shrubs and trees create homes, shade and hiding places, and decide which animals can live in a habitat.' },
    animals:     { k: 'Every role counts', t: 'From the largest elephant to the smallest creature, each animal moves energy, seeds and nutrients through its habitat.' },
    birds:       { k: 'Sky travellers', t: 'Birds spread seeds, control insects and carry nutrients across great distances, linking habitats far apart.' },
    insects:     { k: 'Small but vital', t: 'Small in size, enormous in impact. Pollination, decomposition and food chains all depend on them.' },
    climate:     { k: 'The big dial',   t: 'Temperature and rainfall decide which plants grow and which animals can live where. When climate shifts, whole habitats adapt.' }
  };
  var CHAIN = ['sun', 'plants', 'herbivores', 'predators', 'decomposers', 'soil', 'plants'];

  var els = {}, pos = {}, order = [];
  Array.prototype.forEach.call(map.querySelectorAll('.ab-eco-node'), function (n) {
    var id = n.getAttribute('data-id');
    els[id] = n; order.push(id);
    pos[id] = { x: +n.getAttribute('data-x'), y: +n.getAttribute('data-y') };
  });

  var adj = {}, paths = [];
  order.forEach(function (id) { adj[id] = {}; });
  var NS = 'http://www.w3.org/2000/svg';

  function addEdge(a, b, chain, curve) {
    adj[a][b] = adj[b][a] = true;
    var p = document.createElementNS(NS, 'path');
    var A = pos[a], B = pos[b], d;
    if (curve) d = 'M' + A.x + ' ' + A.y + ' Q50 56 ' + B.x + ' ' + B.y;
    else d = 'M' + A.x + ' ' + A.y + ' L' + B.x + ' ' + B.y;
    p.setAttribute('d', d);
    if (chain) p.setAttribute('class', 'is-chain');
    svg.appendChild(p);
    paths.push({ el: p, a: a, b: b });
  }
  for (var i = 0; i < CHAIN.length - 1; i++) addEdge(CHAIN[i], CHAIN[i + 1], true, i === CHAIN.length - 2);
  order.forEach(function (id) {
    var l = els[id].getAttribute('data-links');
    if (l) l.split(',').forEach(function (t) { addEdge(id, t, false, false); });
  });

  function label(id) { return els[id].querySelector('.ab-eco-nm').textContent; }
  function icon(id)  { return els[id].querySelector('.ab-eco-ico').textContent; }

  var cur = null;
  function setActive(id) {
    cur = id;
    map.classList.add('has-active');
    order.forEach(function (k) {
      els[k].classList.toggle('is-on', k === id);
      els[k].classList.toggle('is-linked', !!adj[id][k]);
    });
    paths.forEach(function (p) { p.el.classList.toggle('is-on', p.a === id || p.b === id); });

    var links = Object.keys(adj[id]).map(function (k) { return '<span>' + label(k) + '</span>'; }).join('');
    infoIn.style.animation = 'none'; void infoIn.offsetWidth; infoIn.style.animation = '';
    infoIn.innerHTML =
      '<div class="ab-eco-info-hd"><div class="ab-eco-info-ico">' + icon(id) + '</div>' +
      '<div><span class="ab-eco-info-kick">' + INFO[id].k + '</span><h3>' + label(id) + '</h3></div></div>' +
      '<p>' + INFO[id].t + '</p>' +
      '<div class="ab-eco-links">' + links + '</div>';
  }

  var timer = null, paused = false;
  function start() {
    if (reduce || timer) return;
    var idx = 0;
    setActive(order[0]);
    timer = setInterval(function () {
      if (paused) return;
      idx = (idx + 1) % order.length;
      setActive(order[idx]);
    }, 3200);
  }
  order.forEach(function (id) {
    function pick() { paused = true; setActive(id); }
    els[id].addEventListener('mouseenter', pick);
    els[id].addEventListener('click', pick);
    els[id].addEventListener('focus', pick);
  });
  map.addEventListener('mouseleave', function () { paused = false; });
  map.addEventListener('focusout', function () { paused = false; });

  sec.classList.add('js-anim');
  function reveal() {
    sec.classList.add('is-in');
    if (reduce) setActive('water'); else setTimeout(start, 1800);
  }
  if (reduce || !('IntersectionObserver' in window)) { reveal(); return; }
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (en) { if (en.isIntersecting) { reveal(); io.disconnect(); } });
  }, { threshold: 0.12 });
  io.observe(sec);
})();
</script>

<style>
/* =====================================================
   About - Section 5 (ab-mem): redesigned
   bg = Section 1 (#FFFDF8) | arch image + journey timeline
   ===================================================== */
.ab-mem {
  background: #FFFDF8;
  padding: clamp(12px, 1.6vw, 22px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif;
  overflow: hidden;
}
.ab-mem, .ab-mem * { box-sizing: border-box; }
.ab-mem-wrap { max-width: 1500px; margin: 0 auto; padding: 0 clamp(12px, 2vw, 24px); }

.ab-mem-card { background: transparent; }

/* reveal (only when JS runs) */
.ab-mem.js-anim [data-r] { opacity: 0; transform: translateY(24px); transition: opacity .8s ease, transform .8s ease; transition-delay: calc(var(--d, 0) * 1s); }
.ab-mem.js-anim.is-in [data-r] { opacity: 1; transform: none; }

/* ---------- main: image | text ---------- */
.ab-mem-head { width: 100%; padding: clamp(6px, 1vw, 12px) clamp(4px, 1vw, 12px) 0; }
.ab-mem-head .ab-mem-title { width: 100%; margin: 0; }
.ab-mem-rule {
  display: block; width: 100%; height: 3px; margin-top: 14px; border-radius: 3px;
  background: linear-gradient(90deg, #E8A15B, rgba(232,161,91,.15));
  transform-origin: left; animation: abMemRule 4s ease-in-out infinite alternate;
}
@keyframes abMemRule { from { transform: scaleX(.55); } to { transform: scaleX(1); } }
.ab-mem-main {
  display: grid;
  grid-template-columns: minmax(240px, .62fr) minmax(0, 1.38fr);
  gap: clamp(20px, 3.4vw, 48px);
  align-items: center;
  padding: clamp(10px, 1.6vw, 22px) clamp(4px, 1vw, 12px);
}

/* arch image */
.ab-mem-media { position: relative; margin: 0; padding: 0 10px 10px 0; }
.ab-mem-media::before {            /* offset amber frame */
  content: ""; position: absolute; inset: 10px 0 0 10px;
  border: 2px solid #E8A15B; border-radius: 999px 999px 18px 18px;
  animation: abMemFrame 5s ease-in-out infinite alternate;
}
@keyframes abMemFrame { to { transform: translate(-6px, -6px); } }
.ab-mem-arch {
  position: relative; z-index: 1;
  height: clamp(230px, 22vw, 300px);
  border-radius: 999px 999px 18px 18px;
  overflow: hidden;
  background: linear-gradient(180deg, #E8A15B 0%, #f3c98f 38%, #173B2A 38.1%, #0B2F22 100%);
}
.ab-mem-scene { position: absolute; inset: 0; width: 100%; height: 100%; }   /* fallback art under the photo */
.ab-mem-arch img {
  position: absolute; inset: 0; width: 100%; height: 100%;
  object-fit: cover; object-position: center; display: block;
  animation: abMemZoom 14s ease-in-out infinite alternate;
}
@keyframes abMemZoom { to { transform: scale(1.08); } }
.ab-mem-arch::after {              /* soft bottom fade */
  content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 28%;
  background: linear-gradient(0deg, rgba(11,47,34,.55), transparent); pointer-events: none;
}

/* rotating badge */
.ab-mem-badge {
  position: absolute; z-index: 3; right: -4px; bottom: 6px;
  width: 96px; height: 96px; border-radius: 50%;
  background: #173B2A; border: 3px solid #E8A15B;
  box-shadow: 0 10px 22px rgba(23,59,42,.35);
  display: flex; align-items: center; justify-content: center;
}
.ab-mem-badge svg.ring { position: absolute; inset: 0; width: 100%; height: 100%; animation: abMemSpin 22s linear infinite; }
.ab-mem-badge .ab-mem-paw { position: relative; animation: abMemBeat 2.4s ease-in-out infinite; }
@keyframes abMemSpin { to { transform: rotate(360deg); } }
@keyframes abMemBeat { 0%,100% { transform: scale(1); } 50% { transform: scale(1.18); } }

/* text */
.ab-mem-label {
  display: inline-flex; align-items: center; gap: 10px;
  margin: 0 0 10px; padding: 6px 14px;
  border: 1px solid rgba(232, 161, 91, .7);
  border-radius: 999px; background: rgba(232, 161, 91, .12);
  font-size: .68rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase;
  color: #173B2A;
}
.ab-mem-label::before {
  content: ""; width: 8px; height: 8px; border-radius: 50%; background: #E8A15B;
  animation: abMemBlink 2s ease-in-out infinite;
}
@keyframes abMemBlink { 0%,100% { opacity: 1; transform: scale(1); } 50% { opacity: .35; transform: scale(.7); } }
.ab-mem .ab-mem-title {
  width: 100%; margin: 0 0 12px;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.4rem, 2.5vw, 2.1rem);
  font-weight: 700; letter-spacing: .01em; line-height: 1.15;
  color: #173B2A;
}
.ab-mem .ab-mem-title em { display: inline; font-style: italic; font-weight: 600; color: #E8A15B; }
.ab-mem-text p {
  margin: 0 0 11px; padding-left: 14px;
  border-left: 3px solid #E8A15B;
  font-size: .95rem; line-height: 1.7; color: #2B241D;
  text-align: justify;
}
.ab-mem-text p:last-child { margin-bottom: 0; }

/* ---------- journey band ---------- */
.ab-mem-journey {
  position: relative;
  padding: clamp(14px, 1.8vw, 22px) clamp(4px, 1vw, 12px) clamp(20px, 2.4vw, 30px);
  background: transparent;
}
.ab-mem-jt {
  margin: 0 0 clamp(12px, 1.6vw, 18px);
  text-align: center;
  font-family: 'Playfair Display', Georgia, serif;
  font-size: clamp(1.1rem, 1.7vw, 1.4rem); font-weight: 600; font-style: italic;
  color: #173B2A;
}
.ab-mem-jt span { color: #E8A15B; }

.ab-mem-track {
  position: relative; list-style: none; margin: 0; padding: 0;
  display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 10px;
}
.ab-mem-rail, .ab-mem-fill, .ab-mem-walker { position: absolute; pointer-events: none; }
.ab-mem-rail, .ab-mem-fill { top: 33px; left: 10%; right: 10%; height: 3px; border-radius: 3px; }
.ab-mem-rail { background: repeating-linear-gradient(90deg, rgba(23,59,42,.28) 0 8px, transparent 8px 16px); }
.ab-mem-fill { background: linear-gradient(90deg, #E8A15B, #173B2A); transform: scaleX(0); transform-origin: left; }
.ab-mem-walker { top: 33px; left: 10%; margin: -24px 0 0 -11px; font-size: 1.15rem; opacity: 0; z-index: 3; }
.ab-mem.is-in .ab-mem-fill   { animation: abMemFill 3.4s linear .3s forwards; }
.ab-mem.is-in .ab-mem-walker { animation: abMemWalk 3.4s linear .3s forwards; }
@keyframes abMemFill { to { transform: scaleX(1); } }
@keyframes abMemWalk { 0% { left: 10%; opacity: 1; } 96% { opacity: 1; } 100% { left: 90%; opacity: 0; } }

.ab-mem-step { position: relative; text-align: center; padding: 0 4px; outline: none; }
.ab-mem.js-anim .ab-mem-step { opacity: 0; transform: translateY(20px) scale(.94); }
.ab-mem.js-anim.is-in .ab-mem-step { animation: abMemPop .7s cubic-bezier(.2,.9,.3,1.2) both; animation-delay: calc(.3s + var(--i) * .85s); }
@keyframes abMemPop { to { opacity: 1; transform: none; } }

.ab-mem-orb {
  position: relative; width: 66px; height: 66px; margin: 0 auto 8px;
  border-radius: 50%;
  background: radial-gradient(circle at 30% 25%, #1f4d38, #0B2F22);
  border: 3px solid #E8A15B;
  box-shadow: 0 0 0 5px rgba(232,161,91,.18), 0 6px 14px rgba(23,59,42,.28);
  display: flex; align-items: center; justify-content: center;
  transition: transform .35s ease, box-shadow .35s ease;
}
.ab-mem-orb svg { width: 36px; height: 36px; animation: abMemFloat 4s ease-in-out infinite; animation-delay: calc(var(--i) * -.7s); }
@keyframes abMemFloat { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-4px); } }
.ab-mem-step:hover .ab-mem-orb, .ab-mem-step:focus-visible .ab-mem-orb {
  transform: translateY(-5px) scale(1.08);
  box-shadow: 0 0 0 9px rgba(232,161,91,.28), 0 14px 24px rgba(23,59,42,.32);
}
.ab-mem-num {
  position: absolute; top: -7px; right: -7px; width: 24px; height: 24px; border-radius: 50%;
  background: #E8A15B; color: #0B2F22; border: 2px solid #FFFDF8;
  font-size: .6rem; font-weight: 700; display: flex; align-items: center; justify-content: center;
}
.ab-mem-name {
  margin: 0 0 4px; font-size: .76rem; font-weight: 700; letter-spacing: .24em; text-transform: uppercase; color: #173B2A;
}
.ab-mem-desc { margin: 0 auto; max-width: 200px; font-size: .84rem; line-height: 1.45; color: #2B241D; }


/* ---- extra timeline motion ---- */
.ab-mem-rail { overflow: hidden; }
.ab-mem-rail::after {                       /* light pulse travelling along the path */
  content: ""; position: absolute; top: -2px; left: -20%; width: 20%; height: 7px; border-radius: 6px;
  background: linear-gradient(90deg, transparent, rgba(232,161,91,.95), transparent);
  opacity: 0;
}
.ab-mem.is-in .ab-mem-rail::after { animation: abMemShine 3.2s ease-in-out 4.2s infinite; }
@keyframes abMemShine { 0% { left: -20%; opacity: 1; } 100% { left: 100%; opacity: 1; } }

.ab-mem-orb::before {                       /* orbiting dashed ring */
  content: ""; position: absolute; inset: -9px; border-radius: 50%;
  border: 1.5px dashed rgba(232,161,91,.85);
  animation: abMemSpin2 16s linear infinite;
}
.ab-mem-step:nth-child(even) .ab-mem-orb::before { animation-direction: reverse; }
@keyframes abMemSpin2 { to { transform: rotate(360deg); } }
.ab-mem.is-in .ab-mem-orb { animation: abMemGlow 3.6s ease-in-out infinite; animation-delay: calc(var(--i) * .5s + 4s); }
@keyframes abMemGlow {
  0%,100% { box-shadow: 0 0 0 5px rgba(232,161,91,.18), 0 6px 14px rgba(23,59,42,.28); }
  50%     { box-shadow: 0 0 0 11px rgba(232,161,91,.07), 0 8px 20px rgba(23,59,42,.34); }
}
.ab-mem-num { animation: abMemBob 2.8s ease-in-out infinite; animation-delay: calc(var(--i) * -.45s); }
@keyframes abMemBob { 0%,100% { transform: scale(1); } 50% { transform: scale(1.2); } }
.ab-mem-walker { animation-timing-function: linear; }
.ab-mem-name { position: relative; display: inline-block; padding-bottom: 5px; }
.ab-mem-name::after {
  content: ""; position: absolute; left: 50%; bottom: 0; width: 0; height: 2px; border-radius: 2px;
  background: #E8A15B; transform: translateX(-50%); transition: width .4s ease;
}
.ab-mem.is-in .ab-mem-name::after { animation: abMemUnder .6s ease forwards; animation-delay: calc(.6s + var(--i) * .85s); }
@keyframes abMemUnder { to { width: 26px; } }
.ab-mem-step:hover .ab-mem-name::after { width: 60%; animation: none; }


/* ---------- responsive ---------- */
@media (max-width: 900px) {
  .ab-mem-head { width: 100%; padding: clamp(6px, 1vw, 12px) clamp(4px, 1vw, 12px) 0; }
.ab-mem-head .ab-mem-title { width: 100%; margin: 0; }
.ab-mem-rule {
  display: block; width: 100%; height: 3px; margin-top: 14px; border-radius: 3px;
  background: linear-gradient(90deg, #E8A15B, rgba(232,161,91,.15));
  transform-origin: left; animation: abMemRule 4s ease-in-out infinite alternate;
}
@keyframes abMemRule { from { transform: scaleX(.55); } to { transform: scaleX(1); } }
.ab-mem-main { grid-template-columns: 1fr; }
  .ab-mem-media { max-width: 340px; width: 100%; margin: 0 auto; }
}
@media (max-width: 760px) {
  .ab-mem-track { grid-template-columns: 1fr; gap: 16px; }
  .ab-mem-rail, .ab-mem-fill { top: 34px; bottom: 34px; left: 33px; right: auto; width: 3px; height: auto; }
  .ab-mem-rail { background: repeating-linear-gradient(180deg, rgba(23,59,42,.28) 0 8px, transparent 8px 16px); }
  .ab-mem-fill { transform: scaleY(0); transform-origin: top; background: linear-gradient(180deg, #E8A15B, #173B2A); }
  .ab-mem-walker { top: 34px; left: 33px; margin: -10px 0 0 14px; }
  .ab-mem.is-in .ab-mem-fill   { animation-name: abMemFillV; }
  .ab-mem.is-in .ab-mem-walker { animation-name: abMemWalkV; }
  @keyframes abMemFillV { to { transform: scaleY(1); } }
  @keyframes abMemWalkV { 0% { top: 34px; opacity: 1; } 96% { opacity: 1; } 100% { top: calc(100% - 34px); opacity: 0; } }
  .ab-mem-rail::after { top: -20%; left: -2px; width: 7px; height: 20%; background: linear-gradient(180deg, transparent, rgba(232,161,91,.95), transparent); }
  .ab-mem.is-in .ab-mem-rail::after { animation-name: abMemShineV; }
  @keyframes abMemShineV { 0% { top: -20%; opacity: 1; } 100% { top: 100%; opacity: 1; } }
  .ab-mem-name { display: block; }
  .ab-mem-name::after { left: 0; transform: none; }
  .ab-mem-step { display: grid; grid-template-columns: 66px minmax(0, 1fr); column-gap: 16px; align-items: center; text-align: left; padding: 0; }
  .ab-mem-orb { margin: 0; }
  .ab-mem-desc { margin: 0; max-width: none; }
}
@media (prefers-reduced-motion: reduce) {
  .ab-mem *, .ab-mem *::before, .ab-mem *::after { animation: none !important; transition: none !important; }
  .ab-mem.js-anim [data-r], .ab-mem.js-anim .ab-mem-step { opacity: 1 !important; transform: none !important; }
  .ab-mem-fill { transform: none !important; }
}
</style>

<section class="ab-mem" id="abMem">
  <div class="ab-mem-wrap">
    <div class="ab-mem-card">

      <div class="ab-mem-main">
        <figure class="ab-mem-media" data-r>
          <div class="ab-mem-arch">
            <svg class="ab-mem-scene" viewBox="0 0 300 420" preserveAspectRatio="xMidYMax slice" aria-hidden="true">
              <circle cx="205" cy="120" r="38" fill="#FFFDF8" opacity=".85"/>
              <path d="M0 250 Q80 200 150 240 T300 225 V420 H0Z" fill="#173B2A"/>
              <path d="M0 300 Q90 265 170 295 T300 285 V420 H0Z" fill="#0B2F22"/>
              <path d="M70 300V250M70 262l-18-14M70 258l20-16" stroke="#E8A15B" stroke-width="5" stroke-linecap="round" fill="none"/>
              <ellipse cx="70" cy="238" rx="38" ry="12" fill="#E8A15B" opacity=".9"/>
            </svg>
            <img src="../assets/images/static/modern-safari-park-exhibit.webp" alt="Visitors enjoying a memorable day at Wildlife Kingdom" onerror="this.style.display='none'">
          </div>
          <div class="ab-mem-badge" aria-hidden="true">
            <svg class="ring" viewBox="0 0 128 128">
              <defs><path id="abMemCirc" d="M64 64 m-47 0 a47 47 0 1 1 94 0 a47 47 0 1 1 -94 0"/></defs>
              <text font-size="9" font-weight="700" fill="#FFFDF8" font-family="Lato, sans-serif">
                <textPath href="#abMemCirc" textLength="288" lengthAdjust="spacing">ARRIVE • EXPLORE • DISCOVER • EXPERIENCE • REMEMBER •</textPath>
              </text>
            </svg>
            <svg class="ab-mem-paw" viewBox="0 0 32 32" width="30" height="30" fill="#E8A15B"><ellipse cx="16" cy="21" rx="7" ry="6"/><ellipse cx="6.5" cy="14" rx="3" ry="3.8"/><ellipse cx="12.5" cy="8.5" rx="3" ry="4"/><ellipse cx="19.5" cy="8.5" rx="3" ry="4"/><ellipse cx="25.5" cy="14" rx="3" ry="3.8"/></svg>
          </div>
        </figure>

        <div class="ab-mem-text">
          <span class="ab-mem-label" data-r>The Wildlife Kingdom Experience</span>
          <h2 class="ab-mem-title" data-r style="--d:.08">More Than a Visit. <em>A Memory to Take Home.</em></h2>
          <div data-r style="--d:.16">
            <p>A visit to Wildlife Kingdom is an opportunity to step closer to the fascinating world of animals and nature. Whether you are exploring with family, spending time with friends, or visiting simply to experience something different, every moment offers something new to discover.</p>
            <p>From observing fascinating animal behaviour and exploring diverse wildlife habitats to capturing memorable photographs and enjoying special events, Wildlife Kingdom brings together wildlife, nature, learning, and entertainment in one experience.</p>
          </div>
        </div>
      </div>

      <div class="ab-mem-journey">
        <h3 class="ab-mem-jt" data-r>Your Day at <span>Wildlife Kingdom</span></h3>
        <ol class="ab-mem-track">
          <li class="ab-mem-rail" aria-hidden="true"></li>
          <li class="ab-mem-fill" aria-hidden="true"></li>
          <li class="ab-mem-walker" aria-hidden="true">&#128062;</li>

          <li class="ab-mem-step" style="--i:0" tabindex="0">
            <div class="ab-mem-orb"><span class="ab-mem-num">01</span>
              <svg viewBox="0 0 64 64" fill="none" stroke="#FFFDF8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="48" cy="14" r="5" stroke="#E8A15B" fill="#E8A15B" fill-opacity=".4"/>
                <path d="M10 54V28a22 22 0 0 1 44 0v26"/><path d="M18 54V30a14 14 0 0 1 28 0v24" stroke="#E8A15B"/>
                <path d="M6 54h52"/><path d="M32 54V34M26 54V38M38 54V38"/>
              </svg></div>
            <div><h4 class="ab-mem-name">Arrive</h4><p class="ab-mem-desc">Begin your wildlife journey.</p></div>
          </li>

          <li class="ab-mem-step" style="--i:1" tabindex="0">
            <div class="ab-mem-orb"><span class="ab-mem-num">02</span>
              <svg viewBox="0 0 64 64" fill="none" stroke="#FFFDF8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="24" cy="22" r="13" fill="#E8A15B" fill-opacity=".35"/><path d="M24 35v14M24 42l-6-5M24 40l6-5"/>
                <circle cx="49" cy="30" r="8" stroke="#E8A15B"/><path d="M49 38v11" stroke="#E8A15B"/>
                <path d="M6 58c14-4 22 0 34-3s14-3 18-3" stroke-dasharray="1 6"/>
              </svg></div>
            <div><h4 class="ab-mem-name">Explore</h4><p class="ab-mem-desc">Walk through fascinating habitats and discover different species.</p></div>
          </li>

          <li class="ab-mem-step" style="--i:2" tabindex="0">
            <div class="ab-mem-orb"><span class="ab-mem-num">03</span>
              <svg viewBox="0 0 64 64" fill="none" stroke="#FFFDF8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="27" cy="27" r="17"/>
                <path d="M17 36c0-11 8-17 21-17-1 12-8 18-19 17z" fill="#E8A15B" fill-opacity=".45" stroke="#E8A15B"/>
                <path d="M18 35l12-12" stroke="#E8A15B"/><path d="M40 40l16 16" stroke-width="5"/>
              </svg></div>
            <div><h4 class="ab-mem-name">Discover</h4><p class="ab-mem-desc">Learn about animal behaviour, habitats and the natural world.</p></div>
          </li>

          <li class="ab-mem-step" style="--i:3" tabindex="0">
            <div class="ab-mem-orb"><span class="ab-mem-num">04</span>
              <svg viewBox="0 0 64 64" fill="none" stroke="#FFFDF8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M8 20h12l4-7h16l4 7h12a2 2 0 0 1 2 2v28a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V22a2 2 0 0 1 2-2z"/>
                <circle cx="32" cy="35" r="11" fill="#E8A15B" fill-opacity=".35" stroke="#E8A15B"/><circle cx="32" cy="35" r="4"/>
              </svg></div>
            <div><h4 class="ab-mem-name">Experience</h4><p class="ab-mem-desc">Enjoy wildlife events, photography moments and memorable encounters.</p></div>
          </li>

          <li class="ab-mem-step" style="--i:4" tabindex="0">
            <div class="ab-mem-orb"><span class="ab-mem-num">05</span>
              <svg viewBox="0 0 64 64" fill="none" stroke="#FFFDF8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="10" y="8" width="44" height="48" rx="3"/><path d="M10 44h44"/>
                <path d="M32 36c-9-6-11-11-8-15s8-3 8 1c0-4 5-5 8-1s1 9-8 15z" fill="#E8A15B" fill-opacity=".6" stroke="#E8A15B"/>
                <path d="M20 51h14"/>
              </svg></div>
            <div><h4 class="ab-mem-name">Remember</h4><p class="ab-mem-desc">Take home memories that stay long after your visit.</p></div>
          </li>
        </ol>
      </div>
    </div>
  </div>
</section>

<script>
/* Section 5: start the journey animation when the section scrolls into view */
(function () {
  var sec = document.getElementById('abMem');
  if (!sec) return;
  sec.classList.add('js-anim');
  var go = function () { sec.classList.add('is-in'); };
  if (!('IntersectionObserver' in window)) { go(); return; }
  var io = new IntersectionObserver(function (es) {
    es.forEach(function (e) { if (e.isIntersecting) { go(); io.disconnect(); } });
  }, { threshold: 0.12 });
  io.observe(sec);
})();
</script>







<style>
/* =====================================================
   About - Section 6 (ab-kd): A Kingdom Measured in Moments
   dark green (same as Sections 2 & 4) · open numbered list drives one big clear image (right)
   ===================================================== */
.ab-kd {
  position: relative; overflow: hidden;
  background: #173B2A;
  padding: clamp(14px, 1.8vw, 24px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif;
  color: #FFFDF8;
}
.ab-kd, .ab-kd * { box-sizing: border-box; }
.ab-kd::before, .ab-kd::after {
  content: ""; position: absolute; border-radius: 50%;
  border: 2px solid rgba(232, 161, 91, .14); pointer-events: none;
}
.ab-kd::before { width: 300px; height: 300px; left: -120px; top: -150px; }
.ab-kd::after  { width: 220px; height: 220px; right: -80px; bottom: -100px; }
.ab-kd-wrap { position: relative; z-index: 1; max-width: 1500px; margin: 0 auto; padding: 0 clamp(12px, 2vw, 24px); }

/* reveal */
.ab-kd.js-anim:not(.is-in) [data-r] { opacity: 0; transform: translateY(18px); }
.ab-kd [data-r] { transition: opacity .7s ease, transform .7s ease; transition-delay: calc(var(--d, 0) * 1s); }

/* ---- top: list (left) | image (right) ---- */
.ab-kd-top { display: grid; grid-template-columns: minmax(0, 1.2fr) minmax(0, .8fr); gap: clamp(16px, 3vw, 44px); align-items: center; }
.ab-kd-label {
  display: inline-flex; align-items: center; gap: 10px;
  margin: 0 0 8px; padding: 6px 14px;
  border: 1px solid rgba(232, 161, 91, .7); border-radius: 999px;
  background: rgba(232, 161, 91, .12);
  font-size: .66rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: #FFFDF8;
}
.ab-kd-label::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: #E8A15B; animation: abKdBlink 2s ease-in-out infinite; }
@keyframes abKdBlink { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: .35; transform: scale(.7); } }
.ab-kd .ab-kd-title {
  margin: 0 0 6px;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.4rem, 2.6vw, 2.2rem); font-weight: 700; line-height: 1.12; color: #FFFDF8;
}
.ab-kd .ab-kd-title em { font-style: italic; font-weight: 600; color: #E8A15B; }
.ab-kd-head { margin-bottom: 6px; }
.ab-kd-head .ab-kd-title { margin: 0; white-space: nowrap; font-size: clamp(1.1rem, 2.35vw, 2.1rem); }

/* open numbered list (no boxes) */
.ab-kd-list { list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 clamp(16px, 2.2vw, 32px); }
.ab-kd-it {
  position: relative; display: flex; align-items: flex-start; gap: 12px;
  padding: 12px 0 12px; cursor: pointer; outline: none;
  border-top: 1px solid rgba(255, 253, 248, .2);
}
.ab-kd-line { position: absolute; left: 0; top: -1px; height: 2px; width: 100%; background: #E8A15B; transform: scaleX(0); transform-origin: left; }
.ab-kd-it.is-active .ab-kd-line { animation: abKdProg var(--dur, 4.2s) linear forwards; }
.ab-kd-list.is-hold .ab-kd-it.is-active .ab-kd-line { animation-play-state: paused; }
@keyframes abKdProg { to { transform: scaleX(1); } }

.ab-kd-num {                    /* outlined number that "fills up" when active */
  flex: 0 0 auto; min-width: 1.75em;
  font-family: 'Playfair Display', Georgia, serif;
  font-size: clamp(2.5rem, 4.2vw, 3.7rem); font-weight: 700; line-height: 1; letter-spacing: .01em;
  font-variant-numeric: tabular-nums;
  color: transparent; -webkit-text-fill-color: transparent;
  -webkit-text-stroke: 1.5px #E8A15B;
  background: linear-gradient(0deg, #F7C48E, #E8A15B 60%, #C97F3F) no-repeat bottom / 100% 0%;
  -webkit-background-clip: text; background-clip: text;
  transition: background-size .6s ease, transform .4s ease;
}
.ab-kd-it.is-active .ab-kd-num, .ab-kd-it:hover .ab-kd-num { background-size: 100% 100%; }
.ab-kd-it.is-active .ab-kd-num { transform: scale(1.06); transform-origin: left center; }
.ab-kd-txt { min-width: 0; opacity: .7; transition: opacity .4s ease; padding-top: 2px; }
.ab-kd-it.is-active .ab-kd-txt, .ab-kd-it:hover .ab-kd-txt { opacity: 1; }
.ab-kd-it h3 { margin: 0 0 3px; font-size: .8rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: #FFFDF8; }
.ab-kd-it.is-active h3 { color: #E8A15B; }
.ab-kd-it p { margin: 0; font-size: .85rem; line-height: 1.5; color: rgba(255, 253, 248, .88); }
.ab-kd-it:focus-visible { box-shadow: 0 0 0 2px rgba(255, 253, 248, .7); border-radius: 4px; }

/* ---- big image: arch + offset outline + rotating badge ---- */
.ab-kd-media {
  position: relative; margin: 0;
  min-height: clamp(300px, 28vw, 400px);
  border-radius: 170px 170px 20px 20px;
  background: linear-gradient(160deg, #1f4d38, #0B2F22);
  box-shadow: 0 14px 34px rgba(0, 0, 0, .28);
}
.ab-kd-media::before {
  content: ""; position: absolute; inset: -10px;
  border: 2px solid #E8A15B; border-radius: 180px 180px 28px 28px; opacity: .75; pointer-events: none;
}
.ab-kd-frame { position: absolute; inset: 0; overflow: hidden; border-radius: inherit; clip-path: inset(0); transition: clip-path 1.3s cubic-bezier(.65, 0, .35, 1) .2s; }
.ab-kd.js-anim:not(.is-in) .ab-kd-frame { clip-path: inset(100% 0 0 0); }
.ab-kd-slide {
  position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; display: block;
  opacity: 0; transform: scale(1.08);
  transition: opacity 1s ease, transform 6s ease-out;
}
.ab-kd-slide.is-on { opacity: 1; transform: scale(1); }
.ab-kd-cap {
  position: absolute; left: 14px; bottom: 14px; z-index: 2;
  padding: 6px 14px; border-radius: 999px;
  background: #E8A15B; color: #0B2F22;
  font-size: .66rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase;
  box-shadow: 0 6px 16px rgba(0, 0, 0, .3);
}
.ab-kd-cap span { display: inline-block; animation: abKdCap .5s ease both; }
@keyframes abKdCap { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }
.ab-kd-badge {
  position: absolute; z-index: 3; top: 6%; left: -22px;
  width: 92px; height: 92px; border-radius: 50%;
  background: #E8A15B; box-shadow: 0 8px 20px rgba(0, 0, 0, .3);
  display: flex; align-items: center; justify-content: center;
}
.ab-kd-badge svg { position: absolute; inset: 0; width: 100%; height: 100%; animation: abKdSpin 16s linear infinite; }
.ab-kd-badge text { font-family: 'Lato', sans-serif; font-size: 9px; font-weight: 700; fill: #0B2F22; }
.ab-kd-badge i { font-style: normal; font-size: 1.5rem; line-height: 1; animation: abKdBob 2.4s ease-in-out infinite; }
@keyframes abKdSpin { to { transform: rotate(360deg); } }
@keyframes abKdBob { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.18); } }

/* ---- take-home: one open row, no boxes ---- */
.ab-kd-take {
  margin-top: clamp(12px, 1.6vw, 18px); padding-top: clamp(10px, 1.4vw, 16px);
  border-top: 1px dashed rgba(232, 161, 91, .55);
  display: grid; grid-template-columns: minmax(0, .85fr) minmax(0, 3.15fr);
  gap: clamp(12px, 2vw, 28px); align-items: center;
}
.ab-kd-take-k { display: block; margin-bottom: 4px; font-size: .64rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: #E8A15B; }
.ab-kd .ab-kd-take h3 { margin: 0 0 4px; font-family: 'Playfair Display', Georgia, serif; font-size: clamp(1.1rem, 1.7vw, 1.4rem); line-height: 1.2; color: #FFFDF8; }
.ab-kd-take-line { margin: 0; font-family: 'Playfair Display', Georgia, serif; font-style: italic; font-size: 1rem; color: rgba(255, 253, 248, .85); }
.ab-kd-take-line s { text-decoration-color: #E8A15B; text-decoration-thickness: 2px; opacity: .65; }
.ab-kd-take-line b { color: #E8A15B; font-weight: 700; }
.ab-kd-keeps { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); }
.ab-kd-keep { display: flex; gap: 10px; align-items: flex-start; padding: 2px 14px; border-left: 1px dashed rgba(232, 161, 91, .45); }
.ab-kd-keep-ico {
  flex: 0 0 auto; width: 34px; height: 34px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  border: 2px solid #E8A15B; font-size: 1rem; line-height: 1;
  transition: background .3s ease, transform .3s ease;
}
.ab-kd.is-in .ab-kd-keep-ico { animation: abKdBob 3s ease-in-out infinite; animation-delay: calc(var(--i) * .5s); }
.ab-kd-keep:hover .ab-kd-keep-ico { background: #E8A15B; transform: scale(1.12) rotate(-8deg); animation: none; }
.ab-kd-keep h4 { margin: 0 0 2px; font-size: .78rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #E8A15B; }
.ab-kd-keep p { margin: 0; font-size: .84rem; line-height: 1.45; color: rgba(255, 253, 248, .9); }

@media (max-width: 1000px) {
  .ab-kd-top { grid-template-columns: 1fr; }
  .ab-kd-media { min-height: 320px; margin: 6px 14px 0 14px; }
  .ab-kd-badge { left: -14px; }
  .ab-kd-take { grid-template-columns: 1fr; }
  .ab-kd-keeps { grid-template-columns: repeat(2, minmax(0, 1fr)); row-gap: 12px; }
  .ab-kd-keep:nth-child(odd) { border-left: 0; padding-left: 0; }
}
@media (max-width: 760px) {
  .ab-kd-head .ab-kd-title { white-space: normal; }
}
@media (max-width: 600px) {
  .ab-kd-list { grid-template-columns: 1fr; }
  .ab-kd-media { min-height: 260px; border-radius: 120px 120px 18px 18px; }
  .ab-kd-media::before { border-radius: 130px 130px 26px 26px; }
  .ab-kd-keeps { grid-template-columns: 1fr; }
  .ab-kd-keep, .ab-kd-keep:nth-child(odd) { border-left: 0; padding-left: 0; }
}
@media (prefers-reduced-motion: reduce) {
  .ab-kd *, .ab-kd *::before, .ab-kd *::after { animation: none !important; transition: none !important; }
  .ab-kd-it.is-active .ab-kd-line { transform: scaleX(1); }
}
</style>

<section class="ab-kd" id="abKd">
  <div class="ab-kd-wrap">

    <div class="ab-kd-top">
      <div>
        <div class="ab-kd-head" data-r>
          <span class="ab-kd-label">A Kingdom Measured in Moments</span>
          <h2 class="ab-kd-title">Every Visit Leaves <em>More Than a Footprint</em></h2>
        </div>
        <ol class="ab-kd-list" id="abKdList" data-r style="--d:.1">
          <li class="ab-kd-it" tabindex="0" data-cap="Wildlife Species"><span class="ab-kd-line"></span>
            <span class="ab-kd-num" data-n="1">01</span>
            <div class="ab-kd-txt"><h3>Wildlife Species</h3><p>Discover a diverse collection of animals and learn what makes each species unique.</p></div>
          </li>
          <li class="ab-kd-it" tabindex="0" data-cap="Natural Habitats"><span class="ab-kd-line"></span>
            <span class="ab-kd-num" data-n="2">02</span>
            <div class="ab-kd-txt"><h3>Natural Habitats</h3><p>Explore environments designed to introduce visitors to different landscapes and ecosystems.</p></div>
          </li>
          <li class="ab-kd-it" tabindex="0" data-cap="Wildlife Experiences"><span class="ab-kd-line"></span>
            <span class="ab-kd-num" data-n="3">03</span>
            <div class="ab-kd-txt"><h3>Wildlife Experiences</h3><p>From everyday exploration to special events, every visit offers a different way to experience nature.</p></div>
          </li>
          <li class="ab-kd-it" tabindex="0" data-cap="Stories to Discover"><span class="ab-kd-line"></span>
            <span class="ab-kd-num" data-n="4">04</span>
            <div class="ab-kd-txt"><h3>Stories to Discover</h3><p>Every animal, habitat and conservation effort has something new to teach.</p></div>
          </li>
        </ol>
      </div>

      <figure class="ab-kd-media" data-r style="--d:.15">
        <div class="ab-kd-frame">
          <img class="ab-kd-slide is-on" src="../assets/images/static/kingdom-species.webp" alt="Wildlife species at Wildlife Kingdom" onerror="this.style.display='none'">
          <img class="ab-kd-slide" src="../assets/images/static/kingdom-habitats.webp" alt="Natural habitats at Wildlife Kingdom" onerror="this.style.display='none'">
          <img class="ab-kd-slide" src="../assets/images/static/kingdom-experiences.webp" alt="Wildlife experiences at Wildlife Kingdom" onerror="this.style.display='none'">
          <img class="ab-kd-slide" src="../assets/images/static/kingdom-stories.webp" alt="Stories to discover at Wildlife Kingdom" onerror="this.style.display='none'">
        </div>
        <div class="ab-kd-badge" aria-hidden="true">
          <svg viewBox="0 0 100 100"><defs><path id="abKdCirc" d="M50,50 m-37,0 a37,37 0 1,1 74,0 a37,37 0 1,1 -74,0"/></defs>
            <text><textPath href="#abKdCirc" textLength="226" lengthAdjust="spacing">EVERY VISIT &#8226; MORE THAN A FOOTPRINT &#8226; </textPath></text></svg>
          <i>&#128062;</i>
        </div>
        <figcaption class="ab-kd-cap"><span id="abKdCap">Wildlife Species</span></figcaption>
      </figure>
    </div>

    <div class="ab-kd-take" data-r style="--d:.1">
      <div>
        <span class="ab-kd-take-k">Take Home</span>
        <h3>What We Want You To Take Home</h3>
        <p class="ab-kd-take-line">Not <s>merchandise</s>. <b>Thoughts.</b></p>
      </div>
      <div class="ab-kd-keeps">
        <div class="ab-kd-keep" style="--i:0"><span class="ab-kd-keep-ico">&#128269;</span><div><h4>Curiosity</h4><p>A question you didn&rsquo;t have when you arrived.</p></div></div>
        <div class="ab-kd-keep" style="--i:1"><span class="ab-kd-keep-ico">&#128161;</span><div><h4>Knowledge</h4><p>Something new you discovered about wildlife.</p></div></div>
        <div class="ab-kd-keep" style="--i:2"><span class="ab-kd-keep-ico">&#127811;</span><div><h4>Connection</h4><p>A stronger feeling of connection with the natural world.</p></div></div>
        <div class="ab-kd-keep" style="--i:3"><span class="ab-kd-keep-ico">&#127757;</span><div><h4>Responsibility</h4><p>A reason to care about what happens beyond the gates.</p></div></div>
      </div>
    </div>

  </div>
</section>

<script>
/* Section 6: numbered list drives the big image. Auto-advances (progress line), hover/tap/focus picks one. */
(function () {
  var sec = document.getElementById('abKd');
  if (!sec) return;
  var list = document.getElementById('abKdList');
  var items = list.querySelectorAll('.ab-kd-it');
  var slides = sec.querySelectorAll('.ab-kd-slide');
  var capEl = document.getElementById('abKdCap');
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var cur = 0, started = false;

  function setActive(i) {
    cur = i;
    Array.prototype.forEach.call(items, function (el, k) {
      el.classList.remove('is-active');
      if (k === i) { void el.offsetWidth; el.classList.add('is-active'); }
    });
    Array.prototype.forEach.call(slides, function (s, k) { s.classList.toggle('is-on', k === i); });
    capEl.style.animation = 'none'; void capEl.offsetWidth; capEl.style.animation = '';
    capEl.textContent = items[i].getAttribute('data-cap');
  }
  function roll(el, delay) {
    var final = '0' + el.getAttribute('data-n'), ticks = 0;
    setTimeout(function () {
      var t = setInterval(function () {
        ticks++;
        if (ticks >= 14) { clearInterval(t); el.textContent = final; return; }
        el.textContent = Math.floor(Math.random() * 10) + '' + Math.floor(Math.random() * 10);
      }, 55);
    }, delay);
  }

  Array.prototype.forEach.call(items, function (el, i) {
    function pick() { list.classList.add('is-hold'); setActive(i); }
    el.addEventListener('mouseenter', pick);
    el.addEventListener('click', pick);
    el.addEventListener('focus', pick);
    el.querySelector('.ab-kd-line').addEventListener('animationend', function () {
      if (!list.classList.contains('is-hold')) setActive((i + 1) % items.length);
    });
  });
  list.addEventListener('mouseleave', function () { list.classList.remove('is-hold'); setActive(cur); });
  list.addEventListener('focusout', function () { list.classList.remove('is-hold'); });

  function go() {
    if (started) return; started = true;
    sec.classList.add('is-in');
    if (reduce) { items[0].classList.add('is-active'); return; }
    Array.prototype.forEach.call(sec.querySelectorAll('.ab-kd-num'), function (el, i) { roll(el, 300 + i * 200); });
    setTimeout(function () { setActive(0); }, 900);
  }
  sec.classList.add('js-anim');
  if (reduce || !('IntersectionObserver' in window)) { go(); return; }
  var io = new IntersectionObserver(function (es) {
    es.forEach(function (e) { if (e.isIntersecting) { go(); io.disconnect(); } });
  }, { threshold: 0.12 });
  io.observe(sec);
})();
</script>

<style>
/* =====================================================
   About - Section 7 (ab-faq): FAQ
   cream background (same as Section 1) · image left · animated accordion right
   ===================================================== */
.ab-faq {
  position: relative; overflow: hidden;
  background: #FFFDF8;
  padding: clamp(16px, 2.2vw, 30px) 0;
  font-family: 'Lato', 'Segoe UI', sans-serif;
  color: #173B2A;
}
.ab-faq, .ab-faq * { box-sizing: border-box; }
.ab-faq-wrap { max-width: 1500px; margin: 0 auto; padding: 0 clamp(12px, 2vw, 24px); }

/* reveal */
.ab-faq.js-anim:not(.is-in) [data-r] { opacity: 0; transform: translateY(18px); }
.ab-faq [data-r] { transition: opacity .7s ease, transform .7s ease; transition-delay: calc(var(--d, 0) * 1s); }

.ab-faq-grid { display: grid; grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr); gap: clamp(18px, 3.2vw, 48px); align-items: stretch; }

/* ---- image (left): offset green block behind ---- */
.ab-faq-media { position: relative; margin: 0 12px 12px 0; min-height: 420px; }
.ab-faq-media::before {
  content: ""; position: absolute; inset: 0; transform: translate(12px, 12px);
  background: #173B2A; border-radius: 22px; z-index: 0;
}
.ab-faq-img {
  position: absolute; inset: 0; z-index: 1; overflow: hidden;
  border-radius: 22px; border: 2px solid #E8A15B;
  background: linear-gradient(160deg, #1f4d38, #0B2F22);
  clip-path: inset(0); transition: clip-path 1.3s cubic-bezier(.65, 0, .35, 1) .2s;
}
.ab-faq.js-anim:not(.is-in) .ab-faq-img { clip-path: inset(0 100% 0 0); }
.ab-faq-img img { width: 100%; height: 100%; object-fit: cover; object-position: center; display: block; }
.ab-faq.is-in .ab-faq-img img { animation: abFaqZoom 16s ease-in-out 1.5s infinite alternate; }
@keyframes abFaqZoom { from { transform: scale(1); } to { transform: scale(1.08); } }

.ab-faq-q {                         /* floating question mark with ripples */
  position: absolute; z-index: 3; right: -14px; top: 18px;
  width: 64px; height: 64px; border-radius: 50%;
  background: #E8A15B; color: #0B2F22;
  display: flex; align-items: center; justify-content: center;
  font-family: 'Playfair Display', Georgia, serif; font-size: 2rem; font-weight: 700; line-height: 1;
  box-shadow: 0 8px 20px rgba(23, 59, 42, .3);
  animation: abFaqFloat 3.6s ease-in-out infinite;
}
.ab-faq-q::before, .ab-faq-q::after {
  content: ""; position: absolute; inset: 0; border-radius: 50%;
  border: 2px solid #E8A15B; opacity: 0; animation: abFaqRipple 3s ease-out infinite;
}
.ab-faq-q::after { animation-delay: 1.5s; }
@keyframes abFaqRipple { 0% { transform: scale(1); opacity: .8; } 100% { transform: scale(1.9); opacity: 0; } }
@keyframes abFaqFloat { 0%, 100% { transform: translateY(0) rotate(-6deg); } 50% { transform: translateY(-8px) rotate(6deg); } }

/* ---- right: heading + accordion ---- */
.ab-faq-label {
  display: inline-flex; align-items: center; gap: 10px;
  margin: 0 0 8px; padding: 6px 14px;
  border: 1px solid rgba(232, 161, 91, .8); border-radius: 999px;
  background: rgba(232, 161, 91, .14);
  font-size: .66rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: #173B2A;
}
.ab-faq-label::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: #E8A15B; animation: abFaqBlink 2s ease-in-out infinite; }
@keyframes abFaqBlink { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: .35; transform: scale(.7); } }
.ab-faq .ab-faq-title {
  margin: 0 0 6px;
  font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
  font-size: clamp(1.4rem, 2.6vw, 2.2rem); font-weight: 700; line-height: 1.12; color: #173B2A;
}
.ab-faq .ab-faq-title em { font-style: italic; font-weight: 600; color: #C97F3F; }
.ab-faq-intro { margin: 0 0 10px; font-size: .92rem; line-height: 1.55; color: #3d4f45; }

.ab-faq-list { list-style: none; margin: 0; padding: 0; }
.ab-faq-item { position: relative; border-bottom: 1px solid rgba(23, 59, 42, .15); border-radius: 12px; transition: background .35s ease; }
.ab-faq-item:first-child { border-top: 1px solid rgba(23, 59, 42, .15); }
.ab-faq-item::before {              /* orange bar slides in when open */
  content: ""; position: absolute; left: 0; top: 8px; bottom: 8px; width: 4px; border-radius: 4px;
  background: #E8A15B; transform: scaleY(0); transition: transform .35s ease;
}
.ab-faq-item.is-open { background: rgba(23, 59, 42, .05); border-bottom-color: transparent; }
.ab-faq-item.is-open::before { transform: scaleY(1); }
.ab-faq.js-anim:not(.is-in) .ab-faq-item { opacity: 0; transform: translateX(16px); }
.ab-faq-item { transition: background .35s ease, opacity .6s ease, transform .6s ease; transition-delay: 0s, calc(.25s + var(--i) * .09s), calc(.25s + var(--i) * .09s); }

.ab-faq-btn {
  width: 100%; display: flex; align-items: center; gap: 12px;
  padding: 10px 12px; background: none; border: 0; cursor: pointer; text-align: left;
  font-family: inherit; color: #173B2A; outline: none;
}
.ab-faq-btn:focus-visible { box-shadow: 0 0 0 2px #E8A15B; border-radius: 12px; }
.ab-faq-no {
  flex: 0 0 auto; min-width: 2.1em;
  font-family: 'Playfair Display', Georgia, serif; font-size: 1.35rem; font-weight: 700; line-height: 1;
  color: transparent; -webkit-text-fill-color: transparent; -webkit-text-stroke: 1.3px #E8A15B;
  background: linear-gradient(0deg, #E8A15B, #C97F3F) no-repeat bottom / 100% 0%;
  -webkit-background-clip: text; background-clip: text;
  transition: background-size .5s ease;
}
.ab-faq-item.is-open .ab-faq-no, .ab-faq-btn:hover .ab-faq-no { background-size: 100% 100%; }
.ab-faq-qt { flex: 1 1 auto; font-size: .97rem; font-weight: 700; line-height: 1.35; transition: color .3s ease, transform .3s ease; }
.ab-faq-btn:hover .ab-faq-qt { transform: translateX(3px); }
.ab-faq-item.is-open .ab-faq-qt { color: #C97F3F; }
.ab-faq-ic {
  flex: 0 0 auto; position: relative; width: 26px; height: 26px; border-radius: 50%;
  border: 2px solid #E8A15B; transition: background .3s ease, transform .35s ease;
}
.ab-faq-ic::before, .ab-faq-ic::after { content: ""; position: absolute; left: 50%; top: 50%; background: #173B2A; transform: translate(-50%, -50%); transition: transform .35s ease, background .3s ease; }
.ab-faq-ic::before { width: 10px; height: 2px; }
.ab-faq-ic::after { width: 2px; height: 10px; }
.ab-faq-item.is-open .ab-faq-ic { background: #E8A15B; transform: rotate(180deg); }
.ab-faq-item.is-open .ab-faq-ic::after { transform: translate(-50%, -50%) scaleY(0); }

.ab-faq-a { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .4s ease; }
.ab-faq-item.is-open .ab-faq-a { grid-template-rows: 1fr; }
.ab-faq-a > div { overflow: hidden; }
.ab-faq-a p { margin: 0; padding: 0 14px 12px calc(12px + 2.1em + 12px); font-size: .9rem; line-height: 1.6; color: #3d4f45; }

@media (max-width: 900px) {
  .ab-faq-grid { grid-template-columns: 1fr; }
  .ab-faq-media { min-height: 240px; margin-bottom: 14px; }
  .ab-faq-q { right: -6px; width: 54px; height: 54px; font-size: 1.7rem; }
}
@media (max-width: 520px) {
  .ab-faq-a p { padding-left: 14px; }
}
@media (prefers-reduced-motion: reduce) {
  .ab-faq *, .ab-faq *::before, .ab-faq *::after { animation: none !important; transition: none !important; }
}
</style>

<section class="ab-faq" id="abFaq">
  <div class="ab-faq-wrap">
    <div class="ab-faq-grid">

      <figure class="ab-faq-media" data-r>
        <div class="ab-faq-img">
          <img src="../assets/images/static/faq-wildlife-kingdom.webp" alt="Planning a visit to Wildlife Kingdom" onerror="this.style.display='none'">
        </div>
        <span class="ab-faq-q" aria-hidden="true">?</span>
      </figure>

      <div class="ab-faq-right">
        <div data-r style="--d:.05">
          <span class="ab-faq-label">You May Be Wondering</span>
          <h2 class="ab-faq-title">Questions Before <em>You Explore?</em></h2>
          <p class="ab-faq-intro">Planning your visit to Wildlife Kingdom? Here are some of the questions visitors may have before exploring our wildlife, habitats and experiences.</p>
        </div>

        <ul class="ab-faq-list" id="abFaqList">
          <li class="ab-faq-item is-open" style="--i:0">
            <button class="ab-faq-btn" type="button" aria-expanded="true" aria-controls="abFaqA1" id="abFaqB1">
              <span class="ab-faq-no">01</span><span class="ab-faq-qt">What can I explore at Wildlife Kingdom?</span><span class="ab-faq-ic" aria-hidden="true"></span>
            </button>
            <div class="ab-faq-a" id="abFaqA1" role="region" aria-labelledby="abFaqB1"><div><p>Wildlife Kingdom offers visitors the opportunity to explore a variety of animals, habitats, wildlife experiences, events and nature-focused attractions in one destination.</p></div></div>
          </li>
          <li class="ab-faq-item" style="--i:1">
            <button class="ab-faq-btn" type="button" aria-expanded="false" aria-controls="abFaqA2" id="abFaqB2">
              <span class="ab-faq-no">02</span><span class="ab-faq-qt">Is Wildlife Kingdom suitable for families and children?</span><span class="ab-faq-ic" aria-hidden="true"></span>
            </button>
            <div class="ab-faq-a" id="abFaqA2" role="region" aria-labelledby="abFaqB2"><div><p>Yes. Wildlife Kingdom is designed to offer an engaging experience for visitors of different ages, making it a place where families can explore wildlife and learn about nature together.</p></div></div>
          </li>
          <li class="ab-faq-item" style="--i:2">
            <button class="ab-faq-btn" type="button" aria-expanded="false" aria-controls="abFaqA3" id="abFaqB3">
              <span class="ab-faq-no">03</span><span class="ab-faq-qt">What animals can I see at Wildlife Kingdom?</span><span class="ab-faq-ic" aria-hidden="true"></span>
            </button>
            <div class="ab-faq-a" id="abFaqA3" role="region" aria-labelledby="abFaqB3"><div><p>The wildlife collection includes different species across various animal groups. You can explore our Animals section to discover the species currently featured at Wildlife Kingdom.</p></div></div>
          </li>
          <li class="ab-faq-item" style="--i:3">
            <button class="ab-faq-btn" type="button" aria-expanded="false" aria-controls="abFaqA4" id="abFaqB4">
              <span class="ab-faq-no">04</span><span class="ab-faq-qt">Can I explore different wildlife habitats?</span><span class="ab-faq-ic" aria-hidden="true"></span>
            </button>
            <div class="ab-faq-a" id="abFaqA4" role="region" aria-labelledby="abFaqB4"><div><p>Yes. Habitats are an important part of the Wildlife Kingdom experience. Visitors can discover different environments and learn how animals interact with the spaces around them.</p></div></div>
          </li>
          <li class="ab-faq-item" style="--i:4">
            <button class="ab-faq-btn" type="button" aria-expanded="false" aria-controls="abFaqA5" id="abFaqB5">
              <span class="ab-faq-no">05</span><span class="ab-faq-qt">Does Wildlife Kingdom organise events or special experiences?</span><span class="ab-faq-ic" aria-hidden="true"></span>
            </button>
            <div class="ab-faq-a" id="abFaqA5" role="region" aria-labelledby="abFaqB5"><div><p>Wildlife Kingdom can feature wildlife-related events and special experiences. Check the Events section for the latest activities and upcoming programs.</p></div></div>
          </li>
          <li class="ab-faq-item" style="--i:5">
            <button class="ab-faq-btn" type="button" aria-expanded="false" aria-controls="abFaqA6" id="abFaqB6">
              <span class="ab-faq-no">06</span><span class="ab-faq-qt">Why is wildlife conservation important at Wildlife Kingdom?</span><span class="ab-faq-ic" aria-hidden="true"></span>
            </button>
            <div class="ab-faq-a" id="abFaqA6" role="region" aria-labelledby="abFaqB6"><div><p>Wildlife conservation helps protect animals, habitats and the ecosystems they depend on. Wildlife Kingdom aims to encourage greater awareness, understanding and appreciation of the natural world.</p></div></div>
          </li>
        </ul>
      </div>

    </div>
  </div>
</section>

<script>
/* Section 7: accordion - one answer open at a time */
(function () {
  var sec = document.getElementById('abFaq');
  if (!sec) return;
  var items = sec.querySelectorAll('.ab-faq-item');
  Array.prototype.forEach.call(items, function (it) {
    var btn = it.querySelector('.ab-faq-btn');
    btn.addEventListener('click', function () {
      var open = it.classList.contains('is-open');
      Array.prototype.forEach.call(items, function (o) {
        o.classList.remove('is-open');
        o.querySelector('.ab-faq-btn').setAttribute('aria-expanded', 'false');
      });
      if (!open) { it.classList.add('is-open'); btn.setAttribute('aria-expanded', 'true'); }
    });
  });
  sec.classList.add('js-anim');
  var go = function () { sec.classList.add('is-in'); };
  if (!('IntersectionObserver' in window)) { go(); return; }
  var io = new IntersectionObserver(function (es) {
    es.forEach(function (e) { if (e.isIntersecting) { go(); io.disconnect(); } });
  }, { threshold: 0.12 });
  io.observe(sec);
})();
</script>

<?php require '../includes/footer.php'; ?>