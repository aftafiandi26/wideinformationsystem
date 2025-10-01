$(document).ready(function () {
  // Sidebar toggle functionality
  $("#sidebarToggle").on("click", function (e) {
    e.preventDefault();
    if ($(window).width() <= 768) {
      $("#sidebar").toggleClass("show");
      $("#sidebarOverlay").toggleClass("show");
      if ($("#sidebar").hasClass("show")) {
        $("body").addClass("sidebar-open");
      } else {
        $("body").removeClass("sidebar-open");
      }
    } else {
      $("#sidebar").toggleClass("collapsed");
      $(".navbar-top").toggleClass("collapsed");
      $("#mainContent").toggleClass("collapsed");
    }
  });

  // Navbar toggle area for desktop
  $("#navbarToggleArea").on("click", function () {
    if ($(window).width() > 768) {
      $("#sidebar").toggleClass("collapsed");
      $(".navbar-top").toggleClass("collapsed");
      $("#mainContent").toggleClass("collapsed");
    }
  });

  // Close sidebar when clicking overlay
  $("#sidebarOverlay").on("click", function () {
    $("#sidebar").removeClass("show");
    $("#sidebarOverlay").removeClass("show");
    $("body").removeClass("sidebar-open");
  });

  // Close sidebar when clicking menu items on mobile
  $(".sidebar .nav > li > a").on("click", function () {
    if ($(window).width() <= 768) {
      if (!$(this).hasClass("dropdown-toggle")) {
        $("#sidebar").removeClass("show");
        $("#sidebarOverlay").removeClass("show");
        $("body").removeClass("sidebar-open");
      }
    }
  });

  // Handle window resize
  $(window).on("resize", function () {
    if ($(window).width() > 768) {
      $("#sidebar").removeClass("show");
      $("#sidebarOverlay").removeClass("show");
      $("body").removeClass("sidebar-open");
    }
  });

  // Touch gestures for mobile
  if ($(window).width() <= 768) {
    let startX = 0;
    let startY = 0;

    $(document).on("touchstart", function (e) {
      startX = e.originalEvent.touches[0].clientX;
      startY = e.originalEvent.touches[0].clientY;
    });

    $(document).on("touchend", function (e) {
      if (!startX || !startY) return;

      let endX = e.originalEvent.changedTouches[0].clientX;
      let endY = e.originalEvent.changedTouches[0].clientY;
      let diffX = startX - endX;
      let diffY = startY - endY;

      if (
        Math.abs(diffX) > Math.abs(diffY) &&
        diffX > 50 &&
        $("#sidebar").hasClass("show")
      ) {
        $("#sidebar").removeClass("show");
        $("#sidebarOverlay").removeClass("show");
        $("body").removeClass("sidebar-open");
      }

      startX = 0;
      startY = 0;
    });
  }

  // Dropdown functionality
  $(".dropdown-toggle").on("click", function (e) {
    e.preventDefault();
    var target = $(this).data("target");
    var $this = $(this);
    var $target = $(target);

    // Toggle the dropdown
    $target.toggleClass("show");
    var isExpanded = $target.hasClass("show");
    $this.attr("aria-expanded", isExpanded);

    // Update arrow classes
    if (isExpanded) {
      $this.removeClass("collapsed");
      $this.addClass("active");
    } else {
      $this.removeClass("active");
      $this.addClass("collapsed");
    }
  });

  // Maintenance alert
  $("a#undermaintanance").on("click", function () {
    var param = $(this).attr("title");
    var time = $(this).attr("time");
    alert("Sorry, " + param + " under maintenance !!");
    alert("You can access it at " + time);
  });
});
