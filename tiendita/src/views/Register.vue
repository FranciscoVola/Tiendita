<template>
  <div class="register-container">
    <div class="register-card">
      <h2 class="titulo">Registrar Usuario</h2>

      <form @submit.prevent="registrarUsuario" class="register-form">
        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input type="text" v-model="nombre" required placeholder="Ingresa tu nombre" />
        </div>

        <div class="form-group">
          <label for="email">Correo Electrónico</label>
          <input type="email" v-model="email" required placeholder="Ingresa tu correo" />
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" v-model="password" required placeholder="Ingresa tu contraseña" />
        </div>

        <button type="submit" class="btn-registrar">Registrar</button>
      </form>

      <p v-if="message" class="message">{{ message }}</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      nombre: '',
      email: '',
      password: '',
      message: '',
    };
  },
  methods: {
    async registrarUsuario() {
      try {
        const response = await fetch('http://localhost/tienda/backend/usuarios/registro.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            nombre: this.nombre,
            email: this.email,
            password: this.password,
          }),
        });

        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || 'Error desconocido');
        }

        const data = await response.json();
        if (data.success) {
          this.message = data.message;
          this.$router.push('/login'); // Redirigir al login
        } else {
          this.message = data.message;
        }
      } catch (error) {
        console.error('Error al registrar usuario:', error);
        this.message = error.message || 'Hubo un error al registrar el usuario.';
      }
    }
  }
};
</script>

<style scoped>
/* Contenedor principal */
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f4f7f6; /* Fondo gris suave */
  font-family: 'Poppins', sans-serif;
}

/* Tarjeta de registro */
.register-card {
  background-color: #ffffff; /* Fondo blanco para la tarjeta */
  padding: 40px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  width: 100%;
  max-width: 400px;
  text-align: center;
  border: 2px solid #3498db; /* Borde azul suave */
}

/* Título */
.titulo {
  font-size: 2em;
  color: #2c3e50; /* Color de texto oscuro para el título */
  margin-bottom: 20px;
  font-weight: 600;
}

/* Estilos del formulario */
.register-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* Estilos para los grupos de los inputs */
.form-group {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

label {
  font-size: 1em;
  color: #333; /* Color de texto más oscuro para las etiquetas */
  margin-bottom: 5px;
}

/* Inputs */
input {
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 1em;
  width: 100%;
  transition: border-color 0.3s ease;
  background-color: #f7f7f7; /* Fondo gris claro para los inputs */
}

input:focus {
  border-color: #3498db; /* Color azul brillante en focus */
  outline: none;
  background-color: #ffffff; /* Fondo blanco cuando el input está en focus */
}

/* Botón de registro */
.btn-registrar {
  padding: 12px 20px;
  background-color: #3498db; /* Azul brillante */
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 1.1em;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn-registrar:hover {
  background-color: #2980b9; /* Azul más oscuro al hacer hover */
  transform: scale(1.05);
}

/* Mensaje de error o éxito */
.message {
  margin-top: 20px;
  font-size: 1em;
  color: #e74c3c; /* Rojo para el error */
}

.message.success {
  color: #28a745; /* Verde para el éxito */
}
</style>
