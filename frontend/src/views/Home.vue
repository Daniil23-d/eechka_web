<template>
  <div class="home">
    <HeaderComponent />
    
    <SliderComponent />
    
    <section class="working-hours">
      <div class="container">
        <h2>Режим работы</h2>
        <p>Ежедневно с 10:00 до 23:00</p>
        <p>Без выходных и перерывов</p>
      </div>
    </section>

    <section class="categories-section">
      <div class="category-pair">
        <router-link to="/menu?category=drinks" class="category-card drinks-card">
          <img src="https://via.placeholder.com/600x300/3498db/ffffff?text=Напитки" alt="Напитки">
          <div class="category-content">
            <h3>🥤 Напитки</h3>
            <p>Освежающие напитки, соки, газировка и многое другое</p>
            <span class="category-btn">Смотреть</span>
          </div>
        </router-link>

        <router-link to="/menu?category=fastfood" class="category-card fastfood-card">
          <img src="https://via.placeholder.com/600x300/e74c3c/ffffff?text=Фастфуд" alt="Фастфуд">
          <div class="category-content">
            <h3>🍔 Фастфуд</h3>
            <p>Шаурма, бургеры, роллы, картошка фри и другие вкусности</p>
            <span class="category-btn">Смотреть</span>
          </div>
        </router-link>
      </div>
    </section>
    
    <FooterComponent />
  </div>
</template>

<script>
import HeaderComponent from '../components/Header.vue'
import FooterComponent from '../components/Footer.vue'
import SliderComponent from '../components/Slider.vue'

export default {
  name: 'HomeView',
  components: {
    HeaderComponent,
    FooterComponent,
    SliderComponent
  }
}
</script>

<style scoped>
.working-hours {
  background: #f8f9fa;
  padding: 40px 0;
  text-align: center;
  margin: 40px 0;
  border-radius: 10px;
}

.working-hours h2 {
  font-size: 28px;
  margin-bottom: 15px;
  color: #333;
}

.working-hours p {
  font-size: 18px;
  color: #666;
}

.categories-section {
  padding: 40px 0;
  background: #f8f9fa;
}

.category-pair {
  display: flex;
  justify-content: center;
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.category-card {
  display: block;
  background: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  transition: transform 0.3s, box-shadow 0.3s;
  text-decoration: none;
  color: inherit;
  flex: 1;
  max-width: 550px;
}

.category-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.category-card img {
  width: 100%;
  height: 300px;
  object-fit: cover;
  display: block;
}

.category-content {
  padding: 25px;
}

.category-content h3 {
  font-size: 24px;
  margin-bottom: 10px;
  color: #333;
}

.category-content p {
  color: #666;
  margin-bottom: 15px;
}

.category-btn {
  display: inline-block;
  padding: 12px 30px;
  background: #e74c3c;
  color: white;
  text-decoration: none;
  border-radius: 5px;
  font-weight: bold;
  transition: background 0.3s;
}

.category-btn:hover {
  background: #c0392b;
}

@media (max-width: 768px) {
  .category-pair {
    flex-direction: column;
    align-items: center;
  }
  
  .category-card {
    width: 100%;
    max-width: 100%;
  }
}
</style>