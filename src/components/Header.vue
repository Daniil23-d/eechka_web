<template>
  <header class="header">
    <div class="container">
      <div class="logo-container" @click="toggleAdminPanel">
        <div class="avatar">
          <img src="/logo.png" alt="ЕШКА" v-if="logoExists">
          <div v-else class="avatar-placeholder"></div>
        </div>
      </div>
      
      <nav class="main-nav">
        <ul>
          <li><router-link to="/" exact>Главная</router-link></li>
          <li><router-link to="/menu">Меню</router-link></li>
          <li><router-link to="/contacts">Контакты</router-link></li>
        </ul>
      </nav>
      
      <button @click="toggleMenu" class="mobile-menu-btn">
        <span></span><span></span><span></span>
      </button>
    </div>
    
    <!-- Админ панель -->
    <transition name="slide-down">
      <div v-if="showAdminPanel" class="admin-panel card">
        <div class="admin-panel-content">
          <div class="admin-panel-header">
            <h3>Вход для администратора</h3>
            <button @click="showAdminPanel = false" class="close-btn">×</button>
          </div>
          
          <form @submit.prevent="login" class="admin-login-form">
            <div class="form-group">
              <label>Логин:</label>
              <input type="text" v-model="adminUsername" placeholder="Введите логин" required autocomplete="username">
            </div>
            <div class="form-group">
              <label>Пароль:</label>
              <input type="password" v-model="adminPassword" placeholder="Введите пароль" required autocomplete="current-password">
            </div>
            <button type="submit" :disabled="loginLoading" class="login-btn">
              {{ loginLoading ? 'Вход...' : 'Войти' }}
            </button>
            <p v-if="loginError" class="login-error">{{ loginError }}</p>
          </form>
        </div>
      </div>
    </transition>
    
    <!-- Мобильное меню -->
    <div v-if="isMenuOpen" class="mobile-menu card">
      <router-link to="/" exact @click="closeMenu">Главная</router-link>
      <router-link to="/menu" @click="closeMenu">Меню</router-link>
      <router-link to="/contacts" @click="closeMenu">Контакты</router-link>
    </div>
  </header>
</template>

<script>
export default {
  name: 'HeaderComponent',
  data() {
    return {
      isMenuOpen: false,
      showAdminPanel: false,
      adminUsername: '',
      adminPassword: '',
      loginLoading: false,
      loginError: '',
      logoExists: true
    }
  },
  mounted() {
    const img = new Image()
    img.onload = () => { this.logoExists = true }
    img.onerror = () => { this.logoExists = false }
    img.src = '/logo.png'
  },
  methods: {
    toggleMenu() { this.isMenuOpen = !this.isMenuOpen },
    closeMenu() { this.isMenuOpen = false },
    toggleAdminPanel() {
      this.showAdminPanel = !this.showAdminPanel
      this.loginError = ''
    },
    login() {
  if (this.adminUsername === 'admin' && this.adminPassword === 'admin123') {
    localStorage.setItem('admin_logged_in', 'true')
    localStorage.setItem('admin_username', this.adminUsername)
    this.showAdminPanel = false
    this.adminUsername = ''
    this.adminPassword = ''
    this.loginError = ''
    this.$router.push('/admin')
  } else {
    this.loginError = 'Неверный логин или пароль'
  }
}
  }
}
</script>

<style scoped>
.header .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 0;
}

.logo-container { cursor: pointer; }

.avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  overflow: hidden;
  background: linear-gradient(135deg, #fbbf24, #f59e0b);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.3s;
}
.avatar:hover { transform: scale(1.1); }
.avatar img { width: 100%; height: 100%; object-fit: cover; }
.avatar-placeholder { font-size: 28px; }

.main-nav ul {
  display: flex;
  list-style: none;
  gap: 2rem;
  margin: 0;
  padding: 0;
}

.mobile-menu-btn {
  display: none;
  flex-direction: column;
  gap: 5px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.5rem;
}
.mobile-menu-btn span {
  width: 25px;
  height: 3px;
  background: var(--accent-yellow);
  border-radius: 3px;
}

.mobile-menu {
  display: none;
  padding: 1rem;
  margin-top: 1rem;
}
.mobile-menu a {
  display: block;
  padding: 0.75rem 0;
  border-bottom: 1px solid var(--border-subtle);
}

.admin-panel {
  position: absolute;
  top: 100%;
  right: 1rem;
  min-width: 320px;
  z-index: 101;
}
.admin-panel-content { padding: 1.5rem; }
.admin-panel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid var(--border-subtle);
}
/* 🔥 ЗАМЕНИТЕ ЭТОТ БЛОК В <style scoped> ФАЙЛА Header.vue */
.close-btn {
  background: transparent !important;
  color: var(--text-muted) !important;
  border: none !important;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  cursor: pointer;
  padding: 0 !important;
  border-radius: 0 !important;
  box-shadow: none !important;
  transition: color 0.2s, transform 0.2s;
}

.close-btn:hover {
  background: transparent !important;
  color: #ffffff !important;
  transform: scale(1.15) rotate(90deg);
  box-shadow: none !important;
}
.admin-login-form .form-group { margin-bottom: 1rem; }
.admin-login-form label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}
.login-error {
  color: #ef4444;
  margin-top: 0.75rem;
  text-align: center;
}

.slide-down-enter-active, .slide-down-leave-active {
  transition: all 0.3s ease;
}
.slide-down-enter-from, .slide-down-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

@media (max-width: 768px) {
  .main-nav { display: none; }
  .mobile-menu-btn { display: flex; }
  .mobile-menu { display: block; }
  .admin-panel {
    right: 0.5rem;
    left: 0.5rem;
    min-width: auto;
  }
}
</style>