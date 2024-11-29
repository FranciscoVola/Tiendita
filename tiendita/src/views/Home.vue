<template>
  <div class="home-container">
    <h1 class="title">Lista de Productos</h1>
    <div v-if="productos.length > 0" class="productos-grid">
      <div v-for="producto in productos" :key="producto.id" class="producto-card">
        <img :src="`/tienda/tiendita/public/img/${producto.imagen}`" alt="Imagen de {{ producto.nombre }}" class="producto-img" />
        <div class="producto-info">
          <h3 class="producto-nombre">{{ producto.nombre }}</h3>
          <p class="producto-precio">${{ producto.precio }}</p>
          <button @click="irADetalles(producto)" class="btn-ver-detalles">Ver Detalles</button>
        </div>
      </div>
    </div>
    <div v-else>
      <p class="no-productos">No hay productos disponibles.</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      productos: [], // aca se cargan los productos desde el backend
    };
  },
  methods: {
    async fetchProductos() {
      try {
        const response = await fetch('http://localhost/tienda/backend/productos.php');
        const data = await response.json();
        this.productos = data;
      } catch (error) {
        console.error('Error al cargar productos:', error);
      }
    },
    irADetalles(producto) {
      if (producto && producto.id) {
        this.$router.push({ name: 'detalles', params: { id: producto.id } });
      } else {
        console.error('Producto inválido o sin ID');
      }
    },
  },
  mounted() {
    this.fetchProductos();
  },
};
</script>

<style scoped>
/* Contenedor principal */
.home-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  font-family: 'Poppins', sans-serif;
}

/* Título de la página */
.title {
  text-align: center;
  font-size: 2.5em;
  color: #333;
  margin-bottom: 20px;
  font-weight: 700;
}

/* Grid de productos */
.productos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
}

/* Estilo de cada card de producto */
.producto-card {
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  padding: 20px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.producto-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

/* Imagen del producto */
.producto-img {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  margin-bottom: 15px;
}

/* Información del producto */
.producto-info {
  text-align: center;
}

/* Nombre del producto */
.producto-nombre {
  font-size: 1.3em;
  font-weight: 600;
  color: #333;
  margin-bottom: 10px;
}

/* Precio del producto */
.producto-precio {
  font-size: 1.2em;
  color: #e74c3c;
  margin-bottom: 15px;
}

/* Botón de ver detalles */
.btn-ver-detalles {
  background-color: #3498db;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  font-size: 1em;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-ver-detalles:hover {
  background-color: #2980b9;
  transform: scale(1.05);
}

/* Mensaje de no hay productos */
.no-productos {
  text-align: center;
  font-size: 1.2em;
  color: #555;
}
</style>
