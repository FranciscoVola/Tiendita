<template>
    <div class="error-404">
      <h1>Error 404</h1>
      <p>La página que buscas no existe.</p>
      <router-link to="/">Volver al inicio</router-link>
    </div>
  </template>
  
  <script>
  export default {
    name: "Error404",
  };
  </script>
  
  <style scoped>
  .error-404 {
    text-align: center;
    margin-top: 50px;
    color: #ff4d4f;
  }
  
  .error-404 h1 {
    font-size: 3rem;
  }
  
  .error-404 p {
    margin: 10px 0;
  }
  </style>
  