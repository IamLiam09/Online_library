(function ($) {
    var size;
  
    //SMALLER HEADER WHEN SCROLL PAGE
    function smallerMenu() {
      var sc = $(window).scrollTop();
      if (sc > 20) {
        $('#header-sroll').addClass('small');
      } else {
        $('#header-sroll').removeClass('small');
      }
    }
  
    // VERIFY WINDOW SIZE
    function windowSize() {
      size = $(document).width();
      if (size >= 991) {
        $('body').removeClass('open-menu');
        $('.hamburger-menu .bar').removeClass('animate');
      }
    };
  
    // ESC BUTTON ACTION
    $(document).keyup(function (e) {
      if (e.keyCode == 27) {
        $('.bar').removeClass('animate');
        $('body').removeClass('open-menu');
        $('header .desk-menu .menu-container .menu .menu-item-has-children a ul').each(function (index) {
          $(this).removeClass('open-sub');
        });
      }
    });
  
    $('#cd-primary-nav > li').hover(function () {
      $whidt_item = $(this).width();
      $whidt_item = $whidt_item - 8;
  
      $prevEl = $(this).prev('li');
      $preWidth = $(this).prev('li').width();
      var pos = $(this).position();
      pos = pos.left + 4;
      $('header .desk-menu .menu-container .menu>li.line').css({
        width: $whidt_item,
        left: pos,
        opacity: 1 });
  
    });
  
    // ANIMATE HAMBURGER MENU
    $('.hamburger-menu').on('click', function () {
      $('.hamburger-menu .bar').toggleClass('animate');
      if ($('body').hasClass('open-menu')) {
        $('body').removeClass('open-menu');
      } else {
        $('body').toggleClass('open-menu');
      }
    });
  
    $('header .desk-menu .menu-container .menu .menu-item-has-children ul').each(function (index) {
      $(this).append('<li class="back"><a href="#">Back</a></li>');
    });
  
    // RESPONSIVE MENU NAVIGATION
    $('header .desk-menu .menu-container .menu .menu-item-has-children > a').on('click', function (e) {
      e.preventDefault();
      if (size <= 1024) {
        $(this).next('ul').addClass('open-sub');
      }
    });
  
    // CLICK FUNCTION BACK MENU RESPONSIVE
    $('header .desk-menu .menu-container .menu .menu-item-has-children ul .back').on('click', function (e) {
      e.preventDefault();
      $(this).parent('ul').removeClass('open-sub');
    });
  
    $('body .over-menu').on('click', function () {
      $('body').removeClass('open-menu');
      $('.bar').removeClass('animate');
    });
  
    $(document).ready(function () {
      windowSize();
    });
  
    $(window).scroll(function () {
      smallerMenu();
    });
  
    $(window).resize(function () {
      windowSize();
    });
  
  })(jQuery);
  //# sourceURL=pen.js

  
  // active add and remove
  
  $(".menu-item a").click(function()
  {
    $(".menu-item a").parent().removeClass("active");
  
    $(this).parent().addClass("active");
  });
  
  // end active add and remove

  // language dropdown
  
  /*Dropdown Menu*/
  $('.dropdown').click(function () {
        $(this).attr('tabindex', 1).focus();
        $(this).toggleClass('active');
        $(this).find('.dropdown-menu').slideToggle(300);
    });
    $('.dropdown').focusout(function () {
        $(this).removeClass('active');
        $(this).find('.dropdown-menu').slideUp(300);
    });
    $('.dropdown .dropdown-menu li').click(function () {
        $(this).parents('.dropdown').find('span').text($(this).text());
        $(this).parents('.dropdown').find('input').attr('value', $(this).attr('id'));
    });

    /*End Dropdown Menu*/

    // tab 

    // initialize
$(document)
.find('.nav-tabs:has(.nav-underline)').each(function initialize() {
  const $container = $(this);
  const $active = $container.find('li.active').first();
  const $underline = $container.find('.nav-underline');

  const left = $active.position().left;
  const width = $active.outerWidth();

  $underline.css({left, width});
});

$(document)
.on('mouseenter focus', '.nav-tabs:has(.nav-underline) > li > a', function () {
  const $this = $(this);
  const $parent = $this.parent();
  const $container = $parent.closest('.nav-tabs');
  const $underline = $container.find('.nav-underline');

  const left = $parent.position().left;
  const width = $parent.outerWidth();

  $underline.css({left, width});
})
.on('mouseleave blur', '.nav-tabs:has(.nav-underline) > li > a', function () {
  const $this = $(this);
  const $container = $this.closest('.nav-tabs');
  const $active = $container.find('li.active').first();
  const $underline = $container.find('.nav-underline');

  const left = $active.position().left;
  const width = $active.outerWidth();

  $underline.css({left, width});
});

// end tab


// price range

const elements = document.querySelectorAll(['range-slider']);

elements.forEach(element => {
  element.insertAdjacentHTML('afterend', `
    <output>${element.value}</output>
  `);
});

// end price range


// image upload 

$('.file-input').change(function(){
  var curElement = $('.image');
  console.log(curElement);
  var reader = new FileReader();

  reader.onload = function (e) {
      // get loaded data and render thumbnail.
      curElement.attr('src', e.target.result);
  };

  // read the image file as a data URL.
  reader.readAsDataURL(this.files[0]);
});




// dropbtn

  /* When the user clicks on the button, 
      toggle between hiding and showing the dropdown content */
    function myDropdown() {
      document.getElementById("myDropdown").classList.toggle("show");
    }
    
    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
      if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
    }


    