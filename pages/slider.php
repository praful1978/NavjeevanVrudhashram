<?php
$dir = "images/slider_images"; // Folder path
$images = glob($dir . "/*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);
?>


  <style>
    .slider {
      overflow: hidden;
      position: relative;
      width: 100%;
      max-width: 900px;
      height: 500px;
        margin: 10px auto 0 auto; /* 👈 30px gap from top */
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }
    .slides {
      display: flex;
      width: 100%;
      height: 100%;
      transition: transform 0.7s ease-in-out;
    }
    .slides img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      flex-shrink: 0;
    }
    /* Dots */
    .dots {
      position: absolute;
      bottom: 15px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 8px;
    }
    .dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: rgba(255,255,255,0.6);
      cursor: pointer;
      transition: background 0.3s;
    }
    .dot.active {
      background: #fff;
    }
  </style>
</head>
<body>

<div class="slider">
  <div class="slides" id="slides">
    <?php foreach($images as $img): ?>
      <img src="<?php echo $img; ?>" alt="Slide">
    <?php endforeach; ?>
  </div>
  <div class="dots" id="dots"></div>
</div>

<script>
let index = 0;
const slides = document.getElementById("slides");
const total = slides.children.length;
const dotsContainer = document.getElementById("dots");

// Create dots dynamically
for (let i = 0; i < total; i++) {
  const dot = document.createElement("div");
  dot.classList.add("dot");
  if (i === 0) dot.classList.add("active");
  dot.addEventListener("click", () => goToSlide(i));
  dotsContainer.appendChild(dot);
}
const dots = document.querySelectorAll(".dot");

function goToSlide(i) {
  index = i;
  slides.style.transform = `translateX(-${index * 100}%)`;
  updateDots();
}

function nextSlide() {
  index = (index + 1) % total;
  goToSlide(index);
}

function updateDots() {
  dots.forEach((d, i) => d.classList.toggle("active", i === index));
}

// Auto slide every 4s
setInterval(nextSlide, 4000);
</script>


