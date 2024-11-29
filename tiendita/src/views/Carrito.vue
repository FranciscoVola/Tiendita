<template>
    <div>
      <h1>Carrito de Compras</h1>
  
      <!-- Mensaje de acción -->
      <div v-if="mensaje" class="mensaje" :class="mensaje.tipo">
        <p>{{ mensaje.texto }}</p>
      </div>
  
      <div v-if="carrito.length === 0">
        <p>No hay productos en el carrito.</p>
      </div>
  
      <div v-else>
        <div v-for="(producto, index) in carrito" :key="producto.id">
          <div class="producto">
            <h3>{{ producto.nombre }}</h3>
            <p>{{ producto.descripcion }}</p>
            <img :src="producto.imagen" alt="Imagen del producto" width="100" />
            <p>Precio: ${{ producto.precio }}</p>
            <p>Cantidad: {{ producto.cantidad }}</p>
            <button @click="eliminarDelCarrito(index)">Eliminar</button>
          </div>
        </div>
  
        <div class="total">
          <h3>Total a Pagar: ${{ calcularTotal() }}</h3>
        </div>
        <button @click="procederAlPago">Proceder al Pago</button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        carrito: [],
        mensaje: null, // Para mostrar los mensajes de acción
      };
    },
    methods: {
      cargarCarrito() {
        this.carrito = JSON.parse(localStorage.getItem('carrito')) || [];
      },
  
      calcularTotal() {
        return this.carrito.reduce((total, producto) => total + producto.precio * producto.cantidad, 0);
      },
  
      eliminarDelCarrito(index) {
        this.carrito.splice(index, 1);
        this.actualizarCarrito();
        this.mostrarMensaje('Producto eliminado correctamente.', 'exito');
      },
  
      actualizarCarrito() {
        localStorage.setItem('carrito', JSON.stringify(this.carrito));
      },
  
      mostrarMensaje(texto, tipo) {
        this.mensaje = { texto, tipo }; // tipo puede ser 'exito' o 'error'
        setTimeout(() => {
          this.mensaje = null; // Limpiar el mensaje después de 3 segundos
        }, 3000);
      },
  
      procederAlPago() {
        this.mostrarMensaje('Redirigiendo al pago...', 'exito');
        this.$router.push('/pago');
      },
    },
    mounted() {
      this.cargarCarrito();
    },
  };
  </script>
  
  <style scoped>
.carrito {
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

h1 {
  text-align: center;
  margin-bottom: 20px;
}

.producto {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
  border-bottom: 1px solid #ccc;
  padding-bottom: 10px;
}

.producto img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  margin-right: 20px;
}

button {
  padding: 10px 20px;
  background-color: #ff4d4f;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #e13327;
}

.total {
  margin-top: 20px;
  font-size: 1.2em;
  font-weight: bold;
}

.mensaje {
  text-align: center;
  margin-bottom: 20px;
  padding: 10px;
  border-radius: 5px;
}

.mensaje.exito {
  background-color: #d4edda;
  color: #155724;
}

.mensaje.error {
  background-color: #f8d7da;
  color: #721c24;
}

.btn-pago {
  width: 100%;
  padding: 12px;
  background-color: #28a745;
  color: white;
  font-size: 1.2em;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.btn-pago:hover {
  background-color: #218838;
}
</style>