<template>
  <div>
    <h2>Gestión de Usuarios</h2>
    <button @click="mostrarFormularioCrear">Crear Usuario</button>

    <!-- Tabla de usuarios -->
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Email</th>
          <th>Rol</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="usuario in usuarios" :key="usuario.id">
          <td>{{ usuario.id }}</td>
          <td>{{ usuario.nombre }}</td>
          <td>{{ usuario.email }}</td>
          <td>{{ usuario.rol }}</td>
          <td>
            <button @click="mostrarFormularioEditar(usuario)">Editar</button>
            <button @click="confirmarEliminarUsuario(usuario.id)">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Formulario de creación y edición -->
    <div v-if="mostrarFormulario" class="form-container">
      <h3>{{ isEdicion ? 'Editar Usuario' : 'Crear Usuario' }}</h3>
      <form @submit.prevent="guardarUsuario">
        <input v-model="usuario.nombre" type="text" placeholder="Nombre" required />
        <input v-model="usuario.email" type="email" placeholder="Email" required />
        <input v-model="usuario.password" type="password" placeholder="Contraseña" v-if="!isEdicion" required />
        <select v-model="usuario.rol">
          <option value="user">Usuario</option>
          <option value="admin">Administrador</option>
        </select>
        <button type="submit">{{ isEdicion ? 'Actualizar' : 'Crear' }}</button>
        <button type="button" @click="cerrarFormulario">Cancelar</button>
      </form>
    </div>

    <!-- Modal de Confirmación -->
    <div v-if="mostrarModalConfirmacion" class="modal">
      <div class="modal-content">
        <p>¿Estás seguro de eliminar este usuario?</p>
        <button @click="eliminarUsuario">Sí, Eliminar</button>
        <button @click="cerrarModalConfirmacion">Cancelar</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      usuarios: [],
      mostrarFormulario: false,
      isEdicion: false,
      mostrarModalConfirmacion: false,
      usuarioIdAEliminar: null,
      usuario: {
        id: null,
        nombre: '',
        email: '',
        password: '',
        rol: 'user',
      }
    };
  },
  methods: {
    // Cargar usuarios al montar el componente
    async cargarUsuarios() {
      try {
        const response = await fetch('http://localhost/tienda/backend/usuarios.php');
        this.usuarios = await response.json();
      } catch (error) {
        console.error('Error al cargar usuarios:', error);
      }
    },

    // Mostrar formulario para crear un usuario
    mostrarFormularioCrear() {
      this.mostrarFormulario = true;
      this.isEdicion = false;
      this.resetFormulario();
    },

    // Mostrar formulario para editar un usuario
    mostrarFormularioEditar(usuario) {
      this.mostrarFormulario = true;
      this.isEdicion = true;
      this.usuario = { ...usuario }; // Copiar los datos del usuario para editarlos
    },

    // Guardar usuario (crear o actualizar)
    async guardarUsuario() {
      const metodo = this.isEdicion ? 'PUT' : 'POST';
      const url = this.isEdicion
        ? `http://localhost/tienda/backend/usuarios.php`
        : `http://localhost/tienda/backend/usuarios.php`;

      try {
        const response = await fetch(url, {
          method: metodo,
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(this.usuario)
        });
        const result = await response.json();
        if (result.success) {
          this.cargarUsuarios(); // Recargar usuarios después de la operación
          this.cerrarFormulario(); // Cerrar el formulario
        }
      } catch (error) {
        console.error('Error al guardar usuario:', error);
      }
    },

    // Mostrar el modal de confirmación antes de eliminar
    confirmarEliminarUsuario(id) {
      this.usuarioIdAEliminar = id;
      this.mostrarModalConfirmacion = true;
    },

    // Eliminar un usuario
    async eliminarUsuario() {
      console.log('Eliminando usuario con ID:', this.usuarioIdAEliminar); // Depuración
      try {
        const response = await fetch(`http://localhost/tienda/backend/usuarios.php?id=${this.usuarioIdAEliminar}`, {
          method: 'DELETE',
        });
        const result = await response.json();
        if (result.success) {
          this.cargarUsuarios(); // Recargar usuarios después de eliminar
          this.cerrarModalConfirmacion(); // Cerrar el modal
        } else {
          console.error('No se pudo eliminar el usuario:', result);
        }
      } catch (error) {
        console.error('Error al eliminar usuario:', error);
      }
    },

    // Cerrar el modal de confirmación
    cerrarModalConfirmacion() {
      this.mostrarModalConfirmacion = false;
      this.usuarioIdAEliminar = null;
    },

    // Resetear el formulario
    resetFormulario() {
      this.usuario = { id: null, nombre: '', email: '', password: '', rol: 'user' };
    },

    // Cerrar el formulario
    cerrarFormulario() {
      this.mostrarFormulario = false;
    }
  },
  mounted() {
    this.cargarUsuarios();
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
input, select {
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

/* Modal de confirmación */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}
.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 5px;
  text-align: center;
}
.modal button {
  margin: 10px;
}
</style>
