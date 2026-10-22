<template>
  <div class="menu-page">
    <HeaderComponent />
    
    <section class="menu-section">
      <div class="container">
        <div class="content-box">
          <div class="menu-header">
            <div>
              <h1>Наше меню</h1>
              <p>Показано {{ filteredDishes.length }} из {{ dishes.length }} блюд</p>
            </div>
            <div class="header-controls">
              <input type="text" v-model="searchQuery" placeholder="Поиск блюд..." class="search-input">
              <div class="price-filter">
                <input type="number" v-model.number="minPrice" placeholder="От ₽" min="0" class="price-input">
                <span class="price-sep">—</span>
                <input type="number" v-model.number="maxPrice" placeholder="До ₽" min="0" class="price-input">
              </div>
            </div>
          </div>
          
          <div class="quick-filters">
            <button :class="{ active: selectedCategory === null }" @click="filterByCategory(null)">Все</button>
            <button 
              v-for="cat in categories" 
              :key="cat.id"
              :class="{ active: selectedCategory === cat.id }"
              @click="filterByCategory(cat.id)"
            >
              {{ cat.name }}
            </button>
          </div>
          
          <div class="dishes-grid">
            <router-link v-for="dish in filteredDishes" :key="dish.id" :to="`/dish/${dish.id}`" class="dish-card">
              <div class="dish-image">
                <img :src="getImageUrl(dish.image)" :alt="dish.name">
              </div>
              <div class="dish-content">
                <h3>{{ dish.name }}</h3>
                <p class="dish-category">{{ getCategoryName(dish.category_id) }}</p>
                <p class="dish-description">{{ dish.description }}</p>
                <div class="dish-footer">
                  <span class="dish-price">{{ dish.price }} ₽</span>
                  <button class="dish-btn">Подробнее</button>
                </div>
              </div>
            </router-link>
          </div>
          
          <div v-if="filteredDishes.length === 0" class="no-results">
            <p>Блюда не найдены</p>
            <button @click="resetFilters" class="secondary-btn">Сбросить фильтры</button>
          </div>
        </div>
      </div>
    </section>
    
    <FooterComponent />
  </div>
</template>

<script>
import HeaderComponent from '../components/Header.vue'
import FooterComponent from '../components/Footer.vue'
import api from '../api/axios'

export default {
  name: 'MenuView',
  components: { HeaderComponent, FooterComponent },
  data() {
    return {
      dishes: [],
      categories: [],
      searchQuery: '',
      minPrice: null,
      maxPrice: null,
      selectedCategory: null
    }
  },
  computed: {
    filteredDishes() {
      let result = this.dishes
      
      if (this.selectedCategory) {
        result = result.filter(d => d.category_id == this.selectedCategory)
      }
      
      if (this.searchQuery.trim()) {
        const q = this.searchQuery.toLowerCase()
        result = result.filter(d => 
          d.name.toLowerCase().includes(q) || 
          d.description.toLowerCase().includes(q)
        )
      }
      
      if (this.minPrice !== null) {
        result = result.filter(d => d.price >= this.minPrice)
      }
      if (this.maxPrice !== null) {
        result = result.filter(d => d.price <= this.maxPrice)
      }
      
      return result
    }
  },
  async mounted() {
    try {
      // Загрузка данных
      const resDishes = await api.get('/menu.php')
      const resCats = await api.get('/categories.php')
      this.dishes = resDishes.data?.data || resDishes.data || []
      this.categories = resCats.data?.data || resCats.data || []
      
      //  ОБРАБОТКА КАТЕГОРИИ ИЗ URL
      const categorySlug = this.$route.query.category
      if (categorySlug) {
        // Нормализуем slug из URL: milk_drinks → milk-drinks
        const normalizedSlug = categorySlug.replace(/_/g, '-')
        
        // Ищем категорию с нормализацией
        const category = this.categories.find(c => {
          const catSlug = c.slug ? c.slug.replace(/_/g, '-') : ''
          return catSlug === normalizedSlug
        })
        
        if (category) {
          this.selectedCategory = category.id
        }
      }
    } catch (e) {
      console.error('Ошибка загрузки меню:', e)
    }
  },
  methods: {
    filterByCategory(categoryId) {
      this.selectedCategory = categoryId
      // Прокрутка вверх для удобства
      window.scrollTo({ top: 300, behavior: 'smooth' })
    },
    
    resetFilters() {
      this.searchQuery = ''
      this.minPrice = null
      this.maxPrice = null
      this.selectedCategory = null
    },
    
    
    getCategoryName(id) {
      const cat = this.categories.find(c => c.id == id)
      return cat ? cat.name : 'Категория'
    },
    
    getImageUrl(filename) {
      if (!filename || filename.includes('default')) return '/default-dish.png'
      if (filename.startsWith('http')) return filename
      return `https://olivine-zircon23547.my-vm.work/eshka/backend/api/uploads/${filename}`
    }
  }
}
</script>

<style scoped>
.menu-section { padding: 3rem 0; }
.content-box { padding: 2.5rem; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 16px; }
.menu-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1.5rem; }
.menu-header h1 { margin: 0; color: var(--text-heading); }
.menu-header p { margin: 0.25rem 0 0; color: var(--text-muted); }
.header-controls { display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
.search-input { min-width: 220px; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-subtle); background: var(--bg-secondary); color: var(--text-main); }
.price-filter { display: flex; align-items: center; gap: 0.5rem; }
.price-input { width: 80px; padding: 0.75rem; border-radius: 10px; text-align: center; border: 1px solid var(--border-subtle); background: var(--bg-secondary); color: var(--text-main); }
.price-sep { color: var(--text-muted); font-weight: bold; }
.quick-filters { display: flex; gap: 0.75rem; margin-bottom: 2rem; flex-wrap: wrap; }
.quick-filters button { padding: 0.5rem 1.25rem; border-radius: 25px; font-size: 0.9rem; background: var(--bg-secondary); color: var(--text-main); border: 1px solid var(--border-subtle); cursor: pointer; transition: all 0.3s; }
.quick-filters button:hover { border-color: var(--accent-yellow); color: var(--accent-yellow); }
.quick-filters button.active { background: var(--accent-yellow); color: var(--bg-primary); border-color: var(--accent-yellow); }
.dishes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.5rem; }
.dish-card { display: flex; flex-direction: column; background: var(--bg-secondary); border: 1px solid var(--border-subtle); border-radius: 16px; overflow: hidden; text-decoration: none; color: inherit; height: 340px; transition: transform 0.25s, border-color 0.25s, box-shadow 0.25s; }
.dish-card:hover { transform: translateY(-6px); border-color: var(--accent-yellow); box-shadow: 0 8px 24px rgba(251, 191, 36, 0.15); }
.dish-image { width: 100%; height: 150px; overflow: hidden; background: var(--bg-primary); position: relative; }
.dish-image img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.4s; }
.dish-card:hover .dish-image img { transform: scale(1.05); }
.dish-content { padding: 1rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
.dish-content h3 { font-size: 1.05rem; margin: 0 0 0.3rem; color: var(--text-heading); font-weight: 600; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.dish-category { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
.dish-description { font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin-bottom: 0.75rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; flex: 1; }
.dish-footer { display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 0.75rem; border-top: 1px solid var(--border-subtle); gap: 12px; flex-wrap: nowrap; }
.dish-price { font-size: 1.15rem; font-weight: 800; color: var(--accent-yellow); white-space: nowrap; line-height: 1; }
.dish-btn { padding: 0.45rem 1rem; font-size: 0.8rem; border-radius: 50px; white-space: nowrap; flex-shrink: 0; margin: 0; }
.no-results { text-align: center; padding: 3rem; color: var(--text-muted); }
@media (max-width: 768px) { .menu-header { flex-direction: column; gap: 1rem; } .header-controls { width: 100%; flex-direction: column; gap: 0.75rem; } .search-input, .price-filter { width: 100%; } .price-input { width: 100%; } .dishes-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; } .dish-card { height: auto; min-height: 260px; } .dish-image { height: 120px; } .dish-content { padding: 0.7rem; gap: 0.4rem; } .dish-content h3 { font-size: 0.95rem; -webkit-line-clamp: 1; margin-bottom: 0.2rem; } .dish-category { font-size: 0.75rem; margin-bottom: 0.3rem; } .dish-description { font-size: 0.8rem; -webkit-line-clamp: 2; margin-bottom: 0.5rem; } .dish-footer { padding-top: 0.5rem; gap: 6px; } .dish-price { font-size: 0.95rem; } .dish-btn { padding: 0.35rem 0.8rem; font-size: 0.75rem; } }
@media (max-width: 480px) { .dishes-grid { grid-template-columns: 1fr; } .dish-card { min-height: 280px; } }
</style>