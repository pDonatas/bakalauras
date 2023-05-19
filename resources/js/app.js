import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

$(document).ready(function() {
  const form = $('#ai-form');

  if (form) {
    let submitted = false;
    if (!submitted) {
      form.on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form[0]);
        const url = form.attr('action');
        const method = form.attr('method');
        const token = $('meta[name="csrf-token"]').attr('content');
        const headers = {
          'X-CSRF-TOKEN': token,
          'X-Requested-With': 'XMLHttpRequest',
        };
        const settings = {
          method,
          headers,
          body: formData,
        };
        fetch(url, settings)
            .then((response) => response.json())
            .then((data) => {
              const div = document.getElementById('ai-images');
              div.innerHTML = '';

              data.data.forEach((item) => {
                const image = document.createElement('img');
                image.classList.add('ai-image');
                image.src = item;

                image.addEventListener('click', function() {
                  const input = document.getElementById('ai-input');
                  input.value = item;

                  $('.ai-image').removeClass('selected');
                  $(this).addClass('selected');
                });
                div.appendChild(image);
              });
            })
            .catch((error) => {
              console.error(error);
            }).finally(() => {
              submitted = false;
            });
      });
    }
  }
});
