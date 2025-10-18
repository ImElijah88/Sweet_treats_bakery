// Show/hide password
function togglePassword(fieldId) {
  var passwordField = document.getElementById(fieldId);
  if (passwordField) {
    if (passwordField.type === "password") {
      passwordField.type = "text";
    } else {
      passwordField.type = "password";
    }
  }
}

// Toggle reply form visibility
function toggleReplyForm(formId) {
  var form = document.getElementById("replyForm_" + formId);
  if (form) {
    if (form.style.display === "none" || form.style.display === "") {
      form.style.display = "block";
    } else {
      form.style.display = "none";
    }
  }
}

// Add emoji to textarea
function addEmoji(element, formId) {
  var emoji = element.textContent;
  var form = document.getElementById("replyForm_" + formId);
  if (form) {
    var textarea = form.querySelector("textarea");
    if (textarea) {
      textarea.value += emoji;
      textarea.focus();
    }
  }
}

// Hide success/error messages after 5 seconds
window.onload = function () {
  var messages = document.querySelectorAll(".success, .error");
  for (var i = 0; i < messages.length; i++) {
    setTimeout(
      (function (msg) {
        return function () {
          msg.style.display = "none";
        };
      })(messages[i]),
      5000
    );
  }

  // Create theme toggle button
  var themeToggle = document.createElement("div");
  themeToggle.className = "theme-toggle";
  themeToggle.innerHTML = '<span class="theme-icon">🌙</span>';
  document.body.appendChild(themeToggle);

  // Create hamburger menu
  var hamburger = document.createElement("div");
  hamburger.className = "hamburger";
  hamburger.innerHTML = "<span></span><span></span><span></span>";
  document.body.appendChild(hamburger);

  // Create mobile navigation
  var mobileNav = document.createElement("div");
  mobileNav.className = "mobile-nav";

  // Create overlay
  var overlay = document.createElement("div");
  overlay.className = "nav-overlay";
  document.body.appendChild(overlay);

  // Get user status
  var isAdmin = document.body.getAttribute("data-is-admin") === "true";
  var isLoggedIn = document.body.getAttribute("data-logged-in") === "true";

  // Create navigation based on user type
  var navItems = "";
  if (isAdmin) {
    navItems =
      '<ul><li><a href="admin_dashboard.php">Dashboard</a></li><li><a href="manage_menu.php">Manage Menu</a></li><li><a href="view_feedback.php">Manage Feedback</a></li><li><a href="logout.php">Logout</a></li></ul>';
  } else if (isLoggedIn) {
    navItems =
      '<ul><li><a href="home.php">Home</a></li><li><a href="menu.php">Our Menu</a></li><li><a href="feedback.php">Reviews</a></li><li><a href="my_profile.php">My Profile</a></li><li><a href="feedback_form.php">Leave Review</a></li><li><a href="logout.php">Logout</a></li></ul>';
  } else {
    navItems =
      '<ul><li><a href="home.php">Home</a></li><li><a href="menu.php">Our Menu</a></li><li><a href="feedback.php">Reviews</a></li><li><a href="index.php">Login</a></li><li><a href="register.php">Register</a></li></ul>';
  }

  mobileNav.innerHTML = navItems;
  document.body.appendChild(mobileNav);

  // Create menu activation zone
  var menuZone = document.createElement("div");
  menuZone.className = "menu-activation-zone";
  document.body.appendChild(menuZone);

  // Menu zone hover events
  menuZone.addEventListener("mouseenter", function () {
    mobileNav.classList.add("active");
    overlay.classList.add("active");
  });

  mobileNav.addEventListener("mouseleave", function () {
    mobileNav.classList.remove("active");
    overlay.classList.remove("active");
  });

  // Hamburger menu toggle
  hamburger.addEventListener("click", function () {
    hamburger.classList.toggle("active");
    mobileNav.classList.toggle("active");
    overlay.classList.toggle("active");
  });

  // Close menu when overlay is clicked
  overlay.addEventListener("click", function () {
    hamburger.classList.remove("active");
    mobileNav.classList.remove("active");
    overlay.classList.remove("active");
  });

  overlay.addEventListener("mouseenter", function () {
    mobileNav.classList.remove("active");
    overlay.classList.remove("active");
  });

  // Close menu when a link is clicked
  mobileNav.addEventListener("click", function (e) {
    if (e.target.tagName === "A") {
      hamburger.classList.remove("active");
      mobileNav.classList.remove("active");
      overlay.classList.remove("active");
    }
  });

  // Theme toggle functionality
  var currentTheme = localStorage.getItem("theme") || "light";
  document.documentElement.setAttribute("data-theme", currentTheme);
  updateThemeIcon(currentTheme);

  themeToggle.addEventListener("click", function () {
    var newTheme = currentTheme === "dark" ? "light" : "dark";
    currentTheme = newTheme;
    document.documentElement.setAttribute("data-theme", newTheme);
    localStorage.setItem("theme", newTheme);
    updateThemeIcon(newTheme);
  });

  function updateThemeIcon(theme) {
    var icon = themeToggle.querySelector(".theme-icon");
    icon.textContent = theme === "dark" ? "☀️" : "🌙";
  }

  // Smooth video scrubbing (extra feature)
  var video = document.getElementById("story-video");
  if (video) {
    var aboutSection = document.querySelector(".about-section");
    var currentTime = 0;

    if (aboutSection) {
      aboutSection.addEventListener("wheel", function (e) {
        if (video.duration) {
          e.preventDefault();

          // Frame-by-frame control (assuming 30fps)
          var estimatedFrameRate = 30;
          var totalFrames = video.duration * estimatedFrameRate;
          var timePerFrame = video.duration / totalFrames;
          var timeChange = e.deltaY > 0 ? timePerFrame : -timePerFrame;

          currentTime += timeChange;
          currentTime = Math.max(0, Math.min(video.duration, currentTime));

          video.currentTime = currentTime;
        }
      });

      // Initialize
      video.addEventListener("loadedmetadata", function () {
        currentTime = 0;
        video.currentTime = 0;
      });
    }
  }
};
