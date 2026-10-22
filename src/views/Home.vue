<template>
  <div class="home">
    <HeaderComponent />
    <SliderComponent />
    
    <!-- Категории меню -->
    <section class="categories-section">
      <div class="container">
        <h2 class="section-title">Наше меню</h2>
        
        <div class="categories-grid">
          <router-link 
            v-for="category in mainCategories" 
            :key="category.slug"
            :to="`/menu?category=${category.slug}`"
            class="category-card"
          >
            <div class="category-image">
              <img :src="getCategoryImage(category.slug)" :alt="category.name">
            </div>
            <div class="category-content">
              <h3>{{ category.name }}</h3>
              <p class="category-count">{{ getCategoryCount(category.id) }} блюд</p>
              <span class="category-btn">Выбрать</span>
            </div>
          </router-link>
        </div>
      </div>
    </section>
    
    <!-- Режим работы -->
    <section class="working-hours-section">
      <div class="container">
        <div class="working-hours-card">
          <h3> Режим работы</h3>
          <div class="hours-grid">
            <div class="hours-item">
              <span class="day">Вс–Чт</span>
              <span class="time">10:00 – 22:00</span>
            </div>
            <div class="hours-item">
              <span class="day">Пт–Сб</span>
              <span class="time">10:00 – 23:00</span>
            </div>
          </div>
        </div>
      </div>
    </section>   
    
    <section class="info-section">
      <div class="container">
        <div class="info-toggle" @click="isInfoOpen = !isInfoOpen">
          <h3> Подробная информация о доставке и оплате</h3>
          <span :class="['info-arrow', { open: isInfoOpen }]">▼</span>
        </div>
        
        <div v-show="isInfoOpen" class="info-content">
          <div class="info-main">
            <h4>ЕШКА | БУЙ | ДОСТАВКА ЕДЫ</h4>
            <p class="contact-row">🚚 ДОСТАВКА 👉 <a href="tel:+79066662305">☎ 8-906-666-23-05</a></p>
            <p class="contact-row">📦 САМОВЫВОЗ 👉 ул. Красной Армии д. 10 (стеклянный павильон напротив)</p>
            <p class="desc">Доставка еды в городе Буй — пицца, роллы, бургеры</p>
            <p class="phone-big">Номер для заказа: <a href="tel:+79066662305">+7-906-666-23-05</a></p>
          </div>
          
          <div class="info-cards">
            <div class="info-card">
              <h5>💳 Условия оплаты</h5>
              <p>Оплату можно производить наличным и безналичным расчетом.</p>
            </div>
            <div class="info-card">
              <h5>🚚 Условия доставки</h5>
              <p class="warning">❗❗ Внешний вид блюд может отличаться от фото в меню! Просьба проверять заказ при курьере!</p>
              <p><strong>Прием заказов:</strong><br>ПН-ЧТ: 10:00 – 22:00<br>ПТ-СБ: 10:00 – 23:00<br>ВС: 10:00 – 22:00</p>
              <p>📞 По телефону: 8-906-666-23-05<br>🆓 При заказе от 1000 ₽ доставка по городу бесплатно (другие районы уточняйте).</p>
            </div>
            <div class="info-card">
              <h5>🔄 Условия возврата</h5>
              <p>👉 Если случилась неприятность, связывайтесь сразу по тел. <a href="tel:+79066662305">8-906-666-23-05</a> или в личные сообщения группы.</p>
            </div>
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
import SliderComponent from '../components/Slider.vue'
import api from '../api/axios'

export default {
  name: 'HomeView',
  components: {
    HeaderComponent,
    FooterComponent,
    SliderComponent
  },
  data() {
    return {
      mainCategories: [],
      dishes: [],
      staticIcons: {
        'pizza': '', 'rolls': '', 'shawarma': '', 'wings': '',
        'shashlik': '', 'burgers': '', 'fries': '', 'wok': '', 'drinks': ''
      },
      isInfoOpen: false
    }
  },
  async mounted() {
    try {
      const res = await api.get('/categories.php')
      const categories = res.data?.data || res.data || []
      
      this.mainCategories = categories
        .filter(c => c.show_on_homepage == 1)
        .map(c => ({
          id: c.id,
          name: c.name,
          slug: c.slug.replace(/_/g, '-'),
          image: c.image || 'default-category.png'
        }))
        
      const resDishes = await api.get('/menu.php')
      this.dishes = resDishes.data?.data || resDishes.data || []
    } catch (e) {
      console.error('❌ Ошибка загрузки:', e)
      this.mainCategories = [
        { id: 3, name: 'Пиццы', slug: 'pizza', image: '/pizzas.png' },
        { id: 5, name: 'Роллы', slug: 'rolls', image: '/rolls.png' },
        { id: 7, name: 'Шаурма', slug: 'shawarma', image: '/shawarma.png' },
        { id: 14, name: 'Крылышки', slug: 'wings', image: '/wings.png' },
        { id: 13, name: 'Шашлык', slug: 'shashlik', image: '/shashlik.png' },
        { id: 9, name: 'Бургеры', slug: 'burgers', image: '/burgers.png' },
        { id: 10, name: 'Фри', slug: 'fries', image: '/fries.png' },
        { id: 11, name: 'Wok', slug: 'wok', image: '/wok.png' },
        { id: 15, name: 'Соусы', slug: 'sauces', image: '/default-category.png' },
        { id: 17, name: 'Молочные напитки', slug: 'milk-drinks', image: '/milk-drinks.png' }
      ]
      this.dishes = []
    }
  },
  methods: {
    getCategoryImage(slug) {
      const category = this.mainCategories.find(c => c.slug === slug)
      if (category && category.image && category.image !== 'default-category.png') {
        return this.buildUploadUrl(category.image)
      }
      const staticImages = {
        'pizza': '/pizzas.png', 'rolls': '/rolls.png', 'shawarma': '/shawarma.png',
        'wings': '/wings.png', 'shashlik': '/shashlik.png', 'burgers': '/burgers.png',
        'fries': '/fries.png', 'wok': '/wok.png', 'drinks': '/drinks.png',
        'milk-drinks': '/milk-drinks.png', 'milk_drinks': '/milk-drinks.png'
      }
      return staticImages[slug] || '/default-category.png'
    },
    
    buildUploadUrl(filename, defaultFile = 'default-category.png') {
      if (!filename || filename === defaultFile) return `/${defaultFile}`
      if (filename.startsWith('http')) return filename
      return `https://olivine-zircon23547.my-vm.work/eshka/backend/api/uploads/${filename}`
    },
    
    getCategoryCount(id) {
      return this.dishes ? this.dishes.filter(d => d.category_id == id).length : 0
    }
  }
}
</script>

<style scoped>
.home { background: transparent; }
.categories-section { padding: 60px 0; }
.section-title { text-align: center; font-size: 36px; margin-bottom: 50px; color: var(--text-heading); }
.categories-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 25px; max-width: 1400px; margin: 0 auto; padding: 0 20px; }

.category-card {
  display: flex; flex-direction: column;
  background: var(--bg-card); border: 1px solid var(--border-subtle);
  border-radius: 18px; overflow: hidden;
  transition: transform 0.3s, border-color 0.3s;
  text-decoration: none; color: inherit; height: 300px;
}
.category-card:hover { transform: translateY(-8px); border-color: var(--accent-yellow); }
.category-image {
  width: 100%; height: 180px; overflow: hidden;
  background: var(--bg-secondary); border-radius: 14px 14px 0 0;
}
.category-image img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.5s; }
.category-card:hover .category-image img { transform: scale(1.08); }
.category-content { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; text-align: center; }
.category-content h3 { font-size: 18px; margin-bottom: 8px; color: var(--text-heading); font-weight: 600; }
.category-count { font-size: 14px; color: var(--text-muted); margin-bottom: 15px; }
.category-btn { align-self: center; width: 100%; max-width: 180px; }

.working-hours-section { padding: 40px 0; background: var(--bg-secondary); }
.working-hours-card { background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 16px; padding: 30px; max-width: 600px; margin: 0 auto; text-align: center; }
.working-hours-card h3 { margin-bottom: 20px; color: var(--text-heading); font-size: 24px; }
.hours-grid { display: flex; flex-direction: column; gap: 12px; }
.hours-item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border-subtle); }
.hours-item:last-child { border-bottom: none; }
.hours-item .day { font-weight: 600; color: var(--text-main); }
.hours-item .time { color: var(--accent-yellow); font-weight: 600; }

.payment-section { padding: 40px 0; }
.payment-card { background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 16px; padding: 30px; max-width: 800px; margin: 0 auto; text-align: center; }
.payment-card h3 { margin-bottom: 15px; color: var(--text-heading); font-size: 24px; }
.payment-card p { color: var(--text-muted); line-height: 1.6; font-size: 16px; }

/* 🔥 СТИЛИ ДЛЯ РАСКРЫВАЮЩЕГОСЯ БЛОКА */
.info-section { padding: 40px 0; }
.info-toggle {
  display: flex; justify-content: space-between; align-items: center;
  background: var(--bg-card); border: 2px solid var(--border-subtle);
  border-radius: 14px; padding: 20px 25px; cursor: pointer;
  transition: all 0.3s ease;
}
.info-toggle:hover { border-color: var(--accent-yellow); background: var(--bg-secondary); }
.info-toggle h3 { margin: 0; font-size: 1.3rem; color: var(--text-heading); }
.info-arrow { font-size: 1rem; color: var(--accent-yellow); transition: transform 0.3s ease; }
.info-arrow.open { transform: rotate(180deg); }

.info-content {
  background: var(--bg-secondary); border: 1px solid var(--border-subtle);
  border-top: 3px solid var(--accent-yellow);
  border-radius: 0 0 14px 14px; margin-top: -8px;
  padding: 25px; animation: slideDown 0.4s ease forwards;
}
@keyframes slideDown {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

.info-main { margin-bottom: 25px; }
.info-main h4 { color: var(--accent-yellow); margin: 0 0 12px; font-size: 1.4rem; }
.contact-row { margin: 6px 0; font-weight: 600; }
.contact-row a { color: var(--accent-yellow); text-decoration: none; }
.contact-row a:hover { text-decoration: underline; }
.desc { color: var(--text-muted); margin: 10px 0; font-style: italic; }
.phone-big { font-size: 1.2rem; margin-top: 15px; }
.phone-big a { color: var(--accent-yellow); font-weight: 800; }

.info-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
.info-card {
  background: var(--bg-card); padding: 20px; border-radius: 12px;
  border-left: 4px solid var(--accent-yellow);
}
.info-card h5 { color: var(--text-heading); margin: 0 0 10px; font-size: 1.1rem; }
.info-card p { color: var(--text-muted); line-height: 1.6; margin: 0 0 8px; }
.warning { background: rgba(251, 191, 36, 0.12); padding: 12px; border-radius: 8px; color: var(--accent-yellow); font-weight: 500; }

@media (max-width: 1200px) { .categories-grid { grid-template-columns: repeat(4, 1fr); } }
@media (max-width: 992px) { .categories-grid { grid-template-columns: repeat(3, 1fr); } .section-title { font-size: 32px; } }
@media (max-width: 768px) {
  .categories-grid { grid-template-columns: repeat(2, 1fr); }
  .category-card { height: 300px; } .category-image { height: 140px; }
  .working-hours-card, .payment-card { padding: 25px 20px; }
  .info-toggle h3 { font-size: 1.1rem; } .info-cards { grid-template-columns: 1fr; } .info-main h4 { font-size: 1.2rem; }
}
@media (max-width: 480px) {
  .categories-grid { grid-template-columns: 1fr; } .section-title { font-size: 28px; } .category-content h3 { font-size: 17px; }
}
</style>