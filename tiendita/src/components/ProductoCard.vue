<template>
    <div class="producto-card">
      <img :src="producto.imagen" :alt="producto.nombre" />
      <h3>{{ producto.nombre }}</h3>
      <p>{{ producto.descripcion }}</p>
      <span>${{ producto.precio }}</span>
      <button @click="$emit('verDetalles', producto)"class="btn-detalles">Ver detalles</button>
    </div>
  </template>
  
  <script>
  export default {
    props: ['producto'],
  };
  </script>
  
  <style scoped>
/* Estilo del botón "Ver detalles" */
.btn-detalles {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 15px 30px;
  font-size: 1.2em;
  font-weight: 600;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.2s ease;
  text-transform: uppercase;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-detalles:hover {
  background-color: #0056b3;
  transform: scale(1.05); /* Sutil aumento de tamaño al hacer hover */
}

.btn-detalles:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.5); /* Resalta el botón cuando está en foco */
}

.btn-detalles:active {
  background-color: #004085;
  transform: scale(1); /* Restablece el tamaño del botón cuando es presionado */
}
</style>