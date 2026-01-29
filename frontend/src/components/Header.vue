<template>
  <header class="header">
    <div class="container">
      <router-link to="/" class="logo">Е<span>Ш</span>КА</router-link>
      <nav>
        <ul>
          <li><router-link to="/" exact>Главная</router-link></li>
          <li><router-link to="/menu">Меню</router-link></li>
          <li><router-link to="/contacts">Контакты</router-link></li>
          <li><router-link to="/partnership">Сотрудничество</router-link></li>
        </ul>
      </nav>
    </div>
  </header>
</template>

<script>
export default {
  name: 'HeaderComponent'
}
</script>

<style scoped>
.header {
  background: rgb(255, 255, 255);
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  position: sticky;
  top: 0;
  z-index: 100;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.header .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 0;
}

.logo {
  font-size: 32px;
  font-weight: bold;
  color: #000;
  text-decoration: none;
}

.logo span {
  color: #e74c3c;
}

nav ul {
  display: flex;
  list-style: none;
  gap: 30px;
}

nav a {
  text-decoration: none;
  color: #333;
  font-weight: 500;
  font-size: 16px;
  transition: color 0.3s;
}

nav a:hover, nav a.router-link-active {
  color: #e74c3c;
}

@media (max-width: 768px) {
  .header .container {
    flex-direction: column;
    gap: 15px;
  }

  nav ul {
    gap: 15px;
  }
}
</style>