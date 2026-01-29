<template>
  <div class="menu">
    <HeaderComponent />
    
    <div class="container">
      <div class="menu-header">
        <h1>Наше меню</h1>
        
        <div class="category-tabs">
          <button 
            :class="{ active: selectedCategory === null }"
            @click="filterByCategory(null)"
          >
            Все
          </button>
          <button 
            v-for="category in categories" 
            :key="category.id"
            :class="{ active: selectedCategory === category.id }"
            @click="filterByCategory(category.id)"
          >
            {{ category.name }}
          </button>
        </div>
      </div>

      <div v-if="loading" class="loading">Загрузка...</div>
      
      <div v-else-if="dishes.length === 0" class="empty">
        Меню временно пусто
      </div>
      
      <div v-else class="dishes-grid">
        <div v-for="dish in dishes" :key="dish.id" class="dish-card">
          <img :src="getImageUrl(dish.image)" :alt="dish.name">
          <div class="dish-content">
            <h3>{{ dish.name }}</h3>
            <p class="dish-description">{{ dish.description }}</p>
            <p class="dish-price">{{ dish.price }} ₽</p>
          </div>
        </div>
      </div>
    </div>
    
    <FooterComponent />
  </div>
</template>

<script>
import HeaderComponent from '../components/Header.vue'
import FooterComponent from '../components/Footer.vue'
import api from '../api/axios'

export default {
  name: 'MenuView',
  components: {
    HeaderComponent,
    FooterComponent
  },
  data() {
    return {
      categories: [],
      dishes: [],
      selectedCategory: null,
      loading: false
    }
  },
  mounted() {
    this.loadCategories()
    this.loadDishes()
  },
  methods: {
    async loadCategories() {
      try {
        const response = await api.get('/categories.php')
        this.categories = response.data
      } catch (error) {
        console.error('Ошибка загрузки категорий:', error)
      }
    },
    async loadDishes(categoryId = null) {
      this.loading = true
      try {
        let url = '/menu.php'
        if (categoryId) {
          url += `?category_id=${categoryId}`
        }
        const response = await api.get(url)
        this.dishes = response.data
      } catch (error) {
        console.error('Ошибка загрузки меню:', error)
      } finally {
        this.loading = false
      }
    },
    filterByCategory(categoryId) {
      this.selectedCategory = categoryId
      this.loadDishes(categoryId)
    },
    getImageUrl(filename) {
      return `http://localhost/eshka/backend/uploads/${filename}`
    }
  }
}
</script>

<style scoped>
.menu {
  padding: 40px 0;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.menu-header {
  text-align: center;
  margin-bottom: 40px;
}

.menu-header h1 {
  font-size: 36px;
  margin-bottom: 20px;
  color: #333;
}

.category-tabs {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  justify-content: center;
}

.category-tabs button {
  padding: 10px 25px;
  background: #f1f1f1;
  border: none;
  border-radius: 20px;
  cursor: pointer;
  font-size: 16px;
  transition: all 0.3s;
}

.category-tabs button:hover, .category-tabs button.active {
  background: #e74c3c;
  color: white;
}

.loading, .empty {
  text-align: center;
  padding: 60px 20px;
  font-size: 18px;
  color: #666;
}

.dishes-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 30px;
}

.dish-card {
  background: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  transition: transform 0.3s;
}

.dish-card:hover {
  transform: translateY(-5px);
}

.dish-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  display: block;
}

.dish-content {
  padding: 20px;
}

.dish-content h3 {
  font-size: 20px;
  margin-bottom: 10px;
  color: #333;
}

.dish-description {
  color: #666;
  margin-bottom: 15px;
  font-size: 14px;
}

.dish-price {
  font-size: 24px;
  font-weight: bold;
  color: #e74c3c;
}

@media (max-width: 768px) {
  .dishes-grid {
    grid-template-columns: 1fr;
  }
}
</style>