<template>
  <div class="dish-detail">
    <HeaderComponent />
    
    <section class="dish-section">
      <div class="container">
        <div v-if="dish" class="dish-container">
          <div class="dish-image-wrapper">
            <img :src="getImageUrl(dish.image)" :alt="dish.name">
          </div>
          <div class="dish-info">
            <h1>{{ dish.name }}</h1>
            <div class="dish-meta">
              <span class="category-tag">{{ getCategoryName(dish.category_id) }}</span>
              <span class="dish-price">{{ dish.price }} ₽</span>
            </div>
            
            <div class="dish-description-box">
              <h3>Описание</h3>
              <p>{{ dish.description }}</p>
            </div>
            
            <div class="dish-actions">
              <button class="btn secondary-btn" @click="$router.back()">← Назад в меню</button>
            </div>
          </div>
        </div>
        <div v-else class="loading">Загрузка блюда...</div>
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
  name: 'DishDetailView',
  components: { HeaderComponent, FooterComponent },
  data() {
    return {
      dish: null,
      categories: []
    }
  },
  async mounted() {
    try {
      const resCats = await api.get('/categories.php')
      const catsData = resCats.data?.data || resCats.data || []
      this.categories = catsData
      
      //  Загрузка конкретного блюда
      const id = this.$route.params.id
      const res = await api.get(`/menu.php?id=${id}`)
      const list = res.data?.data || res.data || []
      
      this.dish = Array.isArray(list) ? list.find(d => d.id == id) : list
      
      if (!this.dish) {
        this.$router.push('/menu')
      }
    } catch (e) {
      console.error('Ошибка загрузки блюда:', e)
      this.$router.push('/menu')
    }
  },
  methods: {

    addToCartAndRedirect() {
    if (!this.dish) return
    const cart = JSON.parse(localStorage.getItem('eshka_cart') || '[]')
    const existing = cart.find(i => i.id === this.dish.id)
    if (existing) existing.quantity++
    else cart.push({ id: this.dish.id, name: this.dish.name, price: this.dish.price, image: this.dish.image, category: this.getCategoryName(this.dish.category_id), quantity: 1 })
    
    localStorage.setItem('eshka_cart', JSON.stringify(cart))
    this.$router.push('/cart')
  },
  
    getCategoryName(id) {
      const cat = this.categories.find(c => c.id == id)
      return cat ? cat.name : 'Категория'
    },
    
    // ВОЗВРАЩАЕТ ПРАВИЛЬНЫЙ ПУТЬ К ФОТО
    getImageUrl(filename) {
  if (!filename || filename.includes('default')) return '/default-dish.png'
  if (filename.startsWith('http')) return filename
  
  //  ПОЛНЫЙ URL для работы через Vite
  return `https://olivine-zircon23547.my-vm.work/eshka/backend/api/uploads/${filename}`
}
  }
}
</script>

<style scoped>
.dish-section { padding: 4rem 0; }
.dish-container {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  background: var(--bg-card);
  border: 1px solid var(--border-subtle);
  border-radius: 20px;
  padding: 2.5rem;
}

.dish-image-wrapper {
  width: 100%;
  height: 400px;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 8px 24px rgba(0,0,0,0.3);
  background: var(--bg-primary);
}
.dish-image-wrapper img { width: 100%; height: 100%; object-fit: cover; display: block; }

.dish-info { display: flex; flex-direction: column; }
.dish-info h1 { margin: 0 0 1rem; font-size: 2.5rem; color: var(--text-heading); }

.dish-meta {
  display: flex;
  gap: 1rem;
  align-items: center;
  margin-bottom: 2rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid var(--border-subtle);
}
.category-tag {
  background: var(--bg-secondary);
  color: var(--text-main);
  padding: 0.4rem 1rem;
  border-radius: 50px;
  font-size: 0.9rem;
  font-weight: 500;
}
.dish-price {
  font-size: 2rem;
  font-weight: 800;
  color: var(--accent-yellow);
}

.dish-description-box {
  background: var(--bg-secondary);
  border-left: 4px solid var(--accent-yellow);
  padding: 1.5rem;
  border-radius: 0 12px 12px 0;
  margin-bottom: 2rem;
  flex-grow: 1;
}
.dish-description-box h3 { margin: 0 0 0.75rem; font-size: 1.3rem; color: var(--text-heading); }
.dish-description-box p { margin: 0; line-height: 1.7; color: var(--text-muted); white-space: pre-line; }

.dish-actions { display: flex; gap: 1rem; }
.loading { text-align: center; padding: 4rem; color: var(--text-muted); font-size: 1.2rem; }

@media (max-width: 992px) {
  .dish-container { grid-template-columns: 1fr; }
  .dish-image-wrapper { height: 300px; }
}
@media (max-width: 768px) {
  .dish-info h1 { font-size: 2rem; }
  .dish-price { font-size: 1.75rem; }
  .dish-actions { flex-direction: column; }
  .dish-container { padding: 1.5rem; }
}
</style>