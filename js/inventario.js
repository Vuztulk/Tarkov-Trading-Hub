$.ajax({
  url: "./controller.php",
  type: "GET",
  dataType: "json",
  data: {
    identifier: "getInventario",
  },
  success: function (inventario) {
    // Pintando el inventario
    const inventarioEl = document.getElementById("inventario");

    for (let i = 0; i < inventario.length; i++) {
      let item = inventario[i];
      let itemEl = document.createElement("li");
      itemEl.classList.add("item");
      itemEl.dataset.id = item.id;
      itemEl.style.cssText = `grid-column: ${item.x} / span ${item.anchura};grid-row: ${item.y} / span ${item.altura};`;
      let itemImage = document.createElement("img");
      itemImage.src = item.imagen;
      itemEl.appendChild(itemImage);
      inventarioEl.appendChild(itemEl);
    }

    const TAM_CASILLA = 70;

    $(".item").draggable({
      containment: "parent",
      grid: [TAM_CASILLA, TAM_CASILLA],
      revert: function (dropped) {
        let item = this[0];
        let id = item.dataset.id;
        let itemInventario = inventario.find(function (e) {
          return e.id == id;
        });
        let nuevaX =
          parseInt(itemInventario.x) +
          Math.floor(parseInt(item.style.left) / TAM_CASILLA);
        let nuevaY =
          parseInt(itemInventario.y) +
          Math.floor(parseInt(item.style.top) / TAM_CASILLA);

        return solapaInventario(nuevaX, nuevaY, itemInventario, inventario);
      },
      stop: function (event, ui) {
        let item = event.target;
        let id = item.dataset.id;
        let itemInventario = inventario.find(function (e) {
          return e.id == id;
        });

        let nuevaX =
          parseInt(itemInventario.x) +
          Math.floor(ui.position.left / TAM_CASILLA);
        let nuevaY =
          parseInt(itemInventario.y) +
          Math.floor(ui.position.top / TAM_CASILLA);

        if (!solapaInventario(nuevaX, nuevaY, itemInventario, inventario)) {
          // Actualizamos item del inventario
          itemInventario.x = nuevaX;
          itemInventario.y = nuevaY;

          // Actualizamos item en la página
          item.style.gridColumnStart = nuevaX;
          item.style.gridRowStart = nuevaY;

          finalX = nuevaX;
          finalY = nuevaY;
        }

        // Reseteamos propiedades modificadas por JQuery UI
        item.style.left = null;
        item.style.top = null;

        // Realizar la petición AJAX
        $.ajax({
          url: "./controller.php",
          type: "POST",
          data: {
            identifier: "actualizacionInventario",
            itemInventario: itemInventario,
          },
          success: function (response) {
            console.log("Inventario actualizado correctamente");
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.error(
              "Error al actualizar el inventario: " +
                textStatus +
                ", " +
                errorThrown
            );
          },
        });
      },
    });

    // Añadir evento dblclick a todos los elementos con clase "item"
    $(".item").on("dblclick", function (e) {
      const itemEl = event.target.closest(".item");
      e.preventDefault();

      const menu = document.createElement("div");
      menu.classList.add("menu");
      const vender = document.createElement("div");
      vender.classList.add("opcion-menu");
      vender.textContent = "Vender";
      const eliminar = document.createElement("div");
      eliminar.classList.add("opcion-menu");
      eliminar.textContent = "Eliminar";

      const id = itemEl.dataset.id;
      const itemIndex = inventario.findIndex(function (e) {
        return e.id == id;
      });
      
      const nombreItem = inventario[itemIndex].nombre;
      const nombreItemEl = document.createElement("div");
      nombreItemEl.classList.add("opcion-menu");
      nombreItemEl.textContent = `${nombreItem}`;

      menu.appendChild(nombreItemEl);
      menu.appendChild(vender);
      menu.appendChild(eliminar);

      vender.addEventListener("click", function () {
        window.location.href =
          "iniciar_venta.php?id_item=" + itemEl.getAttribute("data-id");
      });

      eliminar.addEventListener("click", function () {
        const id = itemEl.dataset.id;
        const itemIndex = inventario.findIndex(function (e) {
          return e.id == id;
        });
        const itemInventario = inventario[itemIndex];

        const confirmacion = window.confirm(
          "¿Está seguro de que desea eliminar este elemento del inventario?"
        );
        if (confirmacion) {
          inventario.splice(itemIndex, 1);
          itemEl.remove();
          $.ajax({
            url: "./controller.php",
            type: "POST",
            data: {
              identifier: "borradoItem",
              itemInventario: itemInventario,
            },
            success: function (response) {
              console.log("Item eliminado correctamente");
            },
            error: function (jqXHR, textStatus, errorThrown) {
              console.error(
                "Error al borrar el item: " + textStatus + ", " + errorThrown
              );
            },
          });
        }
      });

      menu.style.left = e.clientX + 30 + "px";
      menu.style.top = e.clientY + "px";

      document.body.appendChild(menu);

      $(document).on("click", function () {
        menu.remove();
      });
    });
  },
  error: function (jqXHR, textStatus, errorThrown) {
    console.log(
      "Error al obtener el inventario: " + textStatus + ", " + errorThrown
    );
  },
});

function solapaInventario(x, y, itemMovido, inventario) {
  // Calcular el rango de casillas ocupadas por el item movido
  let xIni = x;
  let xFin = x + parseInt(itemMovido.anchura) - 1;
  let yIni = y;
  let yFin = y + parseInt(itemMovido.altura) - 1;

  // Recorrer el inventario y comprobar si algún item ocupa alguna de las casillas del item movido
  for (let i = 0; i < inventario.length; i++) {
    let item = inventario[i];
    if (item.id === itemMovido.id) {
      continue;
    }
    let itemXIni = parseInt(item.x);
    let itemXFin = itemXIni + parseInt(item.anchura) - 1;
    let itemYIni = parseInt(item.y);
    let itemYFin = itemYIni + parseInt(item.altura) - 1;

    if (
      (itemXIni >= xIni && itemXIni <= xFin) ||
      (itemXFin >= xIni && itemXFin <= xFin)
    ) {
      if (
        (itemYIni >= yIni && itemYIni <= yFin) ||
        (itemYFin >= yIni && itemYFin <= yFin)
      ) {
        return true;
      }
    }
  }

  return false;
}
