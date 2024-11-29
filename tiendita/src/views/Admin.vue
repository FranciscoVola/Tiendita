<template>
  <div>
    <h1>Sección de Administración</h1>
    <div class="admin-options">
      <button @click="mostrarUsuarios">Gestionar Usuarios</button>
      <button @click="mostrarProductos">Gestionar Productos</button>
    </div>
    <div v-if="seccion === 'usuarios'">
      <UsuariosCrud />
    </div>
    <div v-if="seccion === 'productos'">
      <ProductosCrud />
    </div>
  </div>
</template>

<script>
import UsuariosCrud from "../components/UsuariosCrud.vue";
import ProductosCrud from "../components/ProductosCrud.vue";

export default {
  data() {
    return {
      seccion: null, // Define qué sección mostrar
    };
  },
  components: {
    UsuariosCrud,
    ProductosCrud,
  },
  methods: {
    mostrarUsuarios() {
      this.seccion = "usuarios";
    },
    mostrarProductos() {
      this.seccion = "productos";
    },
  },
};
</script>

<style scoped>
.admin-options {
  display: flex;
  justify-content: space-around;
  margin: 20px 0;
}
button {
  padding: 10px 20px;
  background-color: #003366;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 1em;
  cursor: pointer;
}
button:hover {
  background-color: #00509e;
}
</style>

  