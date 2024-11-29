<template>
  <div>
    <h2>Gestión de Productos</h2>
    <button @click="mostrarFormularioCrear">Crear Producto</button>

    <!-- Tabla de productos -->
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Imagen</th>
          <th>Precio</th>
          <th>Descripción</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="producto in productos" :key="producto.id">
          <td>{{ producto.id }}</td>
          <td>{{ producto.nombre }}</td>
          <td><img :src="producto.imagen" alt="Producto" class="product-image" /></td>
          <td>{{ producto.precio }}</td>
          <td>{{ producto.descripcion }}</td>
          <td>
            <button @click="mostrarFormularioEditar(producto)">Editar</button>
            <button @click="eliminarProducto(producto.id)">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Formulario de creación y edición -->
    <div v-if="mostrarFormulario" class="form-container">
      <h3>{{ isEdicion ? 'Editar Producto' : 'Crear Producto' }}</h3>
      <form @submit.prevent="guardarProducto">
        <input v-model="producto.nombre" type="text" placeholder="Nombre" required />
        <input v-model="producto.precio" type="number" placeholder="Precio" required />
        <textarea v-model="producto.descripcion" placeholder="Descripción" required></textarea>
        <input v-model="producto.imagen" type="text" placeholder="URL Imagen" />
        <button type="submit">{{ isEdicion ? 'Actualizar' : 'Crear' }}</button>
        <button type="button" @click="cerrarFormulario">Cancelar</button>
      </form>
    </div>

    <!-- Mensajes de confirmación o error -->
    <div v-if="mensaje" :class="mensaje.tipo" class="mensaje">
      {{ mensaje.texto }}
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      productos: [],
      mostrarFormulario: false,
      isEdicion: false,
      producto: {
        id: null,
        nombre: '',
        precio: '',
        descripcion: '',
        imagen: ''
      },
      mensaje: null // Aquí se almacena el mensaje de éxito o error
    };
  },
  methods: {
    // Cargar productos al montar el componente
    async cargarProductos() {
      try {
        const response = await fetch('http://localhost/tienda/backend/productos.php');
        this.productos = await response.json();
      } catch (error) {
        console.error('Error al cargar productos:', error);
      }
    },

    // Mostrar formulario para crear un producto
    mostrarFormularioCrear() {
      this.mostrarFormulario = true;
      this.isEdicion = false;
      this.resetFormulario();
    },

    // Mostrar formulario para editar un producto
    mostrarFormularioEditar(producto) {
      this.mostrarFormulario = true;
      this.isEdicion = true;
      this.producto = { ...producto }; // Copiar los datos del producto para editarlos
    },

    // Guardar producto (crear o actualizar)
    async guardarProducto() {
      const metodo = this.isEdicion ? 'PUT' : 'POST';
      const url = this.isEdicion
        ? `http://localhost/tienda/backend/productos.php`
        : `http://localhost/tienda/backend/productos.php`;

      try {
        const response = await fetch(url, {
          method: metodo,
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(this.producto)
        });
        const result = await response.json();
        if (result.success) {
          this.cargarProductos(); // Recargar productos después de la operación
          this.cerrarFormulario(); // Cerrar el formulario
          this.mostrarMensaje('Producto guardado correctamente', 'success');
        } else {
          this.mostrarMensaje('Hubo un error al guardar el producto', 'error');
        }
      } catch (error) {
        console.error('Error al guardar producto:', error);
        this.mostrarMensaje('Error al guardar producto', 'error');
      }
    },

    // Eliminar un producto
    async eliminarProducto(id) {
      try {
        const response = await fetch(`http://localhost/tienda/backend/productos.php?id=${id}`, {
          method: 'DELETE',
        });
        const result = await response.json();
        if (result.success) {
          this.cargarProductos(); // Recargar productos después de eliminar
          this.mostrarMensaje('Producto eliminado correctamente', 'success');
        } else {
          this.mostrarMensaje('No se pudo eliminar el producto', 'error');
        }
      } catch (error) {
        console.error('Error al eliminar producto:', error);
        this.mostrarMensaje('Error al eliminar producto', 'error');
      }
    },

    // Resetear el formulario
    resetFormulario() {
      this.producto = { id: null, nombre: '', precio: '', descripcion: '', imagen: '' };
    },

    // Cerrar el formulario
    cerrarFormulario() {
      this.mostrarFormulario = false;
    },

    // Mostrar un mensaje de éxito o error
    mostrarMensaje(texto, tipo) {
      this.mensaje = { texto, tipo };
      setTimeout(() => {
        this.mensaje = null; // Limpiar el mensaje después de 5 segundos
      }, 5000);
    }
  },
  mounted() {
    this.cargarProductos();
  }
};
</script>

<style scoped>
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}
th, td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}
th {
  background-color: #003366;
  color: white;
}
.product-image {
  width: 50px;
  height: 50px;
  object-fit: cover;
}
button {
  padding: 5px 10px;
  margin: 0 5px;
  background-color: #003366;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
button:hover {
  background-color: #00509e;
}
.form-container {
  margin-top: 20px;
  border: 1px solid #ddd;
  padding: 20px;
  background-color: #f9f9f9;
}
input, textarea {
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
button[type="button"] {
  background-color: #c0392b;
  color: white;
}
button[type="button"]:hover {
  background-color: #e74c3c;
}

/* Estilo para los mensajes */
.mensaje {
  padding: 10px;
  margin-top: 10px;
  border-radius: 5px;
  text-align: center;
  font-weight: bold;
}
.mensaje.success {
  background-color: #28a745;
  color: white;
}
.mensaje.error {
  background-color: #dc3545;
  color: white;
}
</style>
