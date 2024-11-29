<template>
  <div id="app">
    <header>
      <nav>
        <router-link to="/" class="nav-link">Inicio</router-link>
        <router-link to="/admin" class="nav-link" v-if="user?.rol === 'admin'">Administración</router-link>
        <router-link to="/login" class="nav-link" v-if="!user">Iniciar Sesión</router-link>
        <router-link to="/register" class="nav-link" v-if="!user">Registrarse</router-link>
        <router-link to="/carrito" class="nav-link">Carrito</router-link>
        <div v-if="user" class="user-info">
          <span class="welcome-text">Bienvenido, {{ user.nombre }}</span>
          <button @click="cerrarSesion" class="logout-btn">Cerrar Sesión</button>
        </div>
      </nav>
    </header>
    <main>
      <router-view />
    </main>
    <footer>
      <p>&copy; Tienda 2024 | Aplicaciones con Dispositivos móviles | Francisco Vola.</p>
    </footer>
  </div>
</template>

<script>
export default {
  name: "App",
  data() {
    return {
      user: null, // Estado del usuario logueado
    };
  },
  mounted() {
    // Cargar datos del usuario desde localStorage si existen
    const userData = localStorage.getItem("user");
    if (userData) {
      this.user = JSON.parse(userData);
    }
  },
  methods: {
    cerrarSesion() {
      // Eliminar datos del usuario y actualizar el estado
      localStorage.removeItem("user");
      this.user = null;

      // Redirigir al inicio
      this.$router.push("/");
    },
  },
};
</script>

<style scoped>
/* Importando la fuente Poppins desde Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap');

/* Fuentes globales */
body {
  font-family: 'Poppins', sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f4f7f6;
  color: #333;
}

/* Layout principal */
#app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

/* Estilo para el encabezado */
header {
  background-color: #003366; /* Azul oscuro */
  color: white;
  padding: 40px 0; /* Más espacio en el header */
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

nav {
  display: flex;
  justify-content: center;
  gap: 25px; /* Reducir espacio entre los links */
}

.nav-link {
  color: white;
  text-decoration: none;
  font-weight: 700; /* Hacer la fuente más gruesa */
  font-size: 1.5em; /* Tamaño más pequeño que antes */
  padding: 10px 20px; /* Reducir padding para hacerlo más compacto */
  border-radius: 5px;
  transition: background-color 0.3s ease, transform 0.2s ease;
  text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7); /* Sombra más pronunciada */
}

/* Efecto de hover para mejorar la interactividad */
.nav-link:hover {
  background-color: #00509e;
  transform: scale(1.05); /* Aumento más sutil en hover */
}

/* Estilo principal del contenido */
main {
  flex: 1;
  padding: 40px 20px;
  background-color: #fff;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  margin: 20px;
  font-size: 1.1em;
}

/* Estilo para el pie de página */
footer {
  text-align: center;
  background-color: #003366;
  color: white;
  padding: 20px 0;
  font-size: 1.2em;
  position: relative;
  bottom: 0;
  width: 100%;
}

footer p {
  margin: 0;
  font-size: 1em;
  font-weight: 300;
}

/* Estilo para el mensaje de bienvenida y botón de cerrar sesión */
.user-info {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-left: auto; /* Alineación a la derecha */
  color: white;
}

.welcome-text {
  font-size: 1.2em;
  font-weight: 600;
}

.logout-btn {
  padding: 8px 15px;
  background-color: #ff4d4d;
  color: white;
  font-weight: 700;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

/* Efecto de hover para el botón */
.logout-btn:hover {
  background-color: #e74c3c;
  transform: scale(1.05);
}
</style>
