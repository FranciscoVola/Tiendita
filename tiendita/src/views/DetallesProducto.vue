<template>
  <div class="producto-detalle">
    <h1 class="titulo">Detalles del Producto</h1>
    
    <!-- Mensaje de acción -->
    <div v-if="mensaje" class="mensaje" :class="mensaje.tipo">
      <p>{{ mensaje.texto }}</p>
    </div>

    <div v-if="producto" class="producto-info">
      <img :src="`/tienda/tiendita/public/img/${producto.imagen}`" alt="Imagen del producto" class="producto-imagen" />
      <div class="detalle-info">
        <h2 class="producto-nombre">{{ producto.nombre }}</h2>
        <p class="producto-descripcion">{{ producto.descripcion }}</p>
        <p class="producto-precio">$ {{ producto.precio }}</p>
        <button @click="agregarAlCarrito" class="btn-agregar">Agregar al Carrito</button>
      </div>
    </div>

    <div v-else class="loading">
      <p>Cargando detalles del producto...</p>
    </div>
  </div>
</template>

<script>
import { useRoute } from 'vue-router';

export default {
  data() {
    return {
      producto: null,
      mensaje: null, // Para mostrar los mensajes de acción
    };
  },
  setup() {
    const route = useRoute();
    const id = route.params.id; // esto para agarrar el id
    return { id };
  },
  methods: {
    async fetchProducto() {
      try {
        const response = await fetch(
          `http://localhost/tienda/backend/producto.php?id=${this.id}`
        );
        this.producto = await response.json();
      } catch (error) {
        console.error('Error al cargar producto:', error);
      }
    },

    agregarAlCarrito() {
      let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

      // Verificar si el producto ya esta en el carrito
      const index = carrito.findIndex((p) => p.id === this.producto.id);

      if (index === -1) {
        this.producto.cantidad = 1;
        carrito.push(this.producto);
      } else {
        carrito[index].cantidad++;
      }

      localStorage.setItem('carrito', JSON.stringify(carrito));

      // Mostrar mensaje
      this.mostrarMensaje('Producto agregado al carrito con éxito.', 'exito');

      // Redirigir al carrito
      this.$router.push('/carrito');
    },

    mostrarMensaje(texto, tipo) {
      this.mensaje = { texto, tipo };
      setTimeout(() => {
        this.mensaje = null; // Limpiar el mensaje después de 3 segundos
      }, 3000);
    },
  },
  mounted() {
    this.fetchProducto();
  },
};
</script>

<style scoped>
/* Contenedor principal */
.producto-detalle {
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
  font-family: 'Poppins', sans-serif;
  text-align: center;
}

/* Título de la página */
.titulo {
  font-size: 2.5em;
  font-weight: 700;
  color: #333;
  margin-bottom: 20px;
}

/* Mensaje de acción */
.mensaje {
  padding: 15px;
  margin-bottom: 20px;
  border-radius: 5px;
  text-align: center;
  font-weight: bold;
}

.mensaje.exito {
  background-color: #28a745;
  color: white;
}

.mensaje.error {
  background-color: #dc3545;
  color: white;
}

/* Información del producto */
.producto-info {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 30px;
  margin-top: 20px;
}

/* Imagen del producto */
.producto-imagen {
  max-width: 400px;
  height: auto;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.producto-imagen:hover {
  transform: scale(1.05);
}

/* Detalles del producto */
.detalle-info {
  max-width: 500px;
  text-align: left;
}

/* Nombre del producto */
.producto-nombre {
  font-size: 2em;
  font-weight: 700;
  color: #333;
  margin-bottom: 15px;
}

/* Descripción del producto */
.producto-descripcion {
  font-size: 1.2em;
  color: #555;
  margin-bottom: 15px;
}

/* Precio del producto */
.producto-precio {
  font-size: 1.5em;
  color: #e74c3c;
  margin-bottom: 20px;
}

/* boton de agregar al carrito */
.btn-agregar {
  padding: 12px 25px;
  background-color: #3498db;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 1.1em;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn-agregar:hover {
  background-color: #2980b9;
  transform: scale(1.05);
}

/* Cargando */
.loading {
  font-size: 1.2em;
  color: #777;
  margin-top: 50px;
}
</style>
