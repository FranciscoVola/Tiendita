<template>
    <div>
      <h2>Carrito de Compras</h2>
      <div v-if="carrito.length > 0">
        <div v-for="(producto, index) in carrito" :key="producto.id" class="producto">
          <p>{{ producto.nombre }} ({{ producto.cantidad }})</p>
          <p>${{ producto.precio * producto.cantidad }}</p>
          <button @click="eliminarProducto(index)">Eliminar</button>
        </div>
        <h3>Total: ${{ total }}</h3>
      </div>
      <div v-else>
        <p>El carrito está vacío.</p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        carrito: JSON.parse(localStorage.getItem('carrito')) || [],
      };
    },
    computed: {
      total() {
        return this.carrito.reduce(
          (acumulador, producto) => acumulador + producto.precio * producto.cantidad,
          0
        );
      },
    },
    methods: {
      eliminarProducto(index) {
        this.carrito.splice(index, 1);
        localStorage.setItem('carrito', JSON.stringify(this.carrito));
      },
    },
  };
  </script>
  