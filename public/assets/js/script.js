(function($) {

  "use strict";

  var initPreloader = function() {
    $(document).ready(function($) {
    var Body = $('body');
        Body.addClass('preloader-site');
    });
    $(window).load(function() {
        $('.preloader-wrapper').fadeOut();
        $('body').removeClass('preloader-site');
    });
  }

  // init Chocolat light box
	var initChocolat = function() {
		Chocolat(document.querySelectorAll('.image-link'), {
		  imageSize: 'contain',
		  loop: true,
		})
	}

  var initSwiper = function() {

    var swiper = new Swiper(".main-swiper", {
      speed: 500,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
    });

    var category_swiper = new Swiper(".category-carousel", {
      slidesPerView: 6,
      spaceBetween: 30,
      speed: 500,
      navigation: {
        nextEl: ".category-carousel-next",
        prevEl: ".category-carousel-prev",
      },
      breakpoints: {
        0: {
          slidesPerView: 2,
        },
        768: {
          slidesPerView: 3,
        },
        991: {
          slidesPerView: 4,
        },
        1500: {
          slidesPerView: 6,
        },
      }
    });

    var brand_swiper = new Swiper(".brand-carousel", {
      slidesPerView: 4,
      spaceBetween: 30,
      speed: 500,
      navigation: {
        nextEl: ".brand-carousel-next",
        prevEl: ".brand-carousel-prev",
      },
      breakpoints: {
        0: {
          slidesPerView: 2,
        },
        768: {
          slidesPerView: 2,
        },
        991: {
          slidesPerView: 3,
        },
        1500: {
          slidesPerView: 4,
        },
      }
    });

    var products_swiper = new Swiper(".products-carousel", {
      slidesPerView: 5,
      spaceBetween: 30,
      speed: 500,
      navigation: {
        nextEl: ".products-carousel-next",
        prevEl: ".products-carousel-prev",
      },
      breakpoints: {
        0: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 3,
        },
        991: {
          slidesPerView: 4,
        },
        1500: {
          slidesPerView: 6,
        },
      }
    });
  }


  // init jarallax parallax
  var initJarallax = function() {
    jarallax(document.querySelectorAll(".jarallax"));

    jarallax(document.querySelectorAll(".jarallax-keep-img"), {
      keepImg: true,
    });
  }

  // document ready
  $(document).ready(function() {
    
    initPreloader();
    initSwiper();
    initJarallax();
    initChocolat();

  }); // End of a document

})(jQuery);


document.addEventListener("DOMContentLoaded", () => {

  document.querySelectorAll(".product-qty").forEach(qtyWrapper => {
    const input = qtyWrapper.querySelector("input[type='text']");
    const plusBtn = qtyWrapper.querySelector(".quantity-right-plus");
    const minusBtn = qtyWrapper.querySelector(".quantity-left-minus");

    plusBtn.addEventListener("click", e => {
      e.preventDefault();
      let value = parseInt(input.value) || 1;
      input.value = value + 1;
    });

    minusBtn.addEventListener("click", e => {
      e.preventDefault();
      let value = parseInt(input.value) || 1;
      if (value > 1) input.value = value - 1;
    });
  });

  // 2️⃣ Handle Add to Cart buttons
  document.querySelectorAll(".add-to-cart").forEach(button => {
    button.addEventListener("click", async e => {
      e.preventDefault();

      const productId = button.dataset.product;
      const quantityInput = document.getElementById(`quantity-${productId}`);
      const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

      const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
      const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

      try {
        const response = await fetch(addToCartUrl, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken
          },
          body: JSON.stringify({ product_id: productId, quantity: quantity })
        });

        if (response.status === 401) {
          window.location.href = "/login"; // redirect if not logged in
          return;
        }

        if (!response.ok) throw new Error("Network response was not ok");

        const data = await response.json();

        const cartCountEl = document.getElementById("cart-count");
        const cartTotalEl = document.getElementById("cart-total");
        if (cartCountEl) cartCountEl.textContent = data.cartCount;
        if (cartTotalEl) cartTotalEl.textContent = data.cartTotal;

      } catch (error) {
        console.error("Error adding to cart:", error);
        alert("Something went wrong while adding to cart.");
      }
    });
  });
});


// document.addEventListener('DOMContentLoaded', function () {
//     document.querySelectorAll('.btn-wishlist').forEach(function (button) {
//         button.addEventListener('click', function (e) {
//             e.preventDefault();
//             let form = this.closest('form');
//             let btn = this;

//             fetch(form.action, {
//                 method: 'POST',
//                 body: new FormData(form),
//                 headers: {
//                     'X-Requested-With': 'XMLHttpRequest'
//                 }
//             })
//             .then(response => response.json())
//             .then(data => {
//                 if (data.status === 'added') {
//                     btn.classList.add('active');   // make heart red
//                 } else if (data.status === 'removed') {
//                     btn.classList.remove('active'); // back to gray
//                 }
//             })
//             .catch(() => alert('Something went wrong!'));
//         });
//     });
// });
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.btn-wishlist').forEach(function (button) {
    button.addEventListener('click', function (e) {
      e.preventDefault();

      const productId = this.dataset.productId;

      const form = this.closest('form');

      fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(response => response.json())
      .then(data => {
        // Select all wishlist buttons for this product
        const buttons = document.querySelectorAll(`.btn-wishlist[data-product-id='${productId}']`);
        
        buttons.forEach(btn => {
          if (data.status === 'added') {
            btn.classList.add('active');   // make heart red
          } else if (data.status === 'removed') {
            btn.classList.remove('active'); // back to gray
          }
        });
      })
      .catch(() => alert('Something went wrong!'));
    });
  });
});
