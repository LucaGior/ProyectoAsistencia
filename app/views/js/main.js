document.addEventListener('DOMContentLoaded', () => {

  /* Obtener todos los elementos con la clase "navbar-burger" */
  const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0); 
  /* Agregar un evento de clic a cada uno de ellos */
  $navbarBurgers.forEach(el => {
    el.addEventListener('click', () => {

      /* Obtener el objetivo desde el atributo "data-target" */
      const target = el.dataset.target;
      const $target = document.getElementById(target);

      /* Alternar la clase "is-active" tanto en "navbar-burger" como en "navbar-menu"*/
      el.classList.toggle('is-active');
      $target.classList.toggle('is-active');

    });
  });
});
