<template>
  <div class="slider-container">
    <div class="slider-wrapper">
      <div 
        v-for="(slide, index) in slides" 
        :key="index" 
        class="slide"
        :class="{ active: currentSlide === index }"
      >
        <img :src="slide.image" :alt="slide.title" class="slide-image">
        <div class="slide-overlay">
          <h2 class="slide-title">{{ slide.title }}</h2>
          <p class="slide-subtitle">{{ slide.subtitle }}</p>
          <router-link to="/menu" class="slider-btn">Перейти в меню</router-link>
        </div>
      </div>
    </div>
    
    <!-- Точки навигации -->
    <div class="slider-dots">
      <span 
        v-for="(slide, index) in slides" 
        :key="index" 
        class="dot" 
        :class="{ active: currentSlide === index }"
        @click="currentSlide = index"
      ></span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SliderComponent',
  data() {
    return {
      currentSlide: 0,
      slides: [
        {
          image: '/fastfood-bg..jpg',
          title: 'Вкусная еда с доставкой',
          subtitle: 'Пицца, роллы, бургеры и многое другое'
        },
        {
          image: '/sveg.jpg',
          title: 'Свежие ингредиенты каждый день',
          subtitle: 'Готовим с любовью для вас'
        },
        {
          image: '/dostavka.jpg',
          title: 'Быстрая доставка по городу',
          subtitle: 'При заказе от 1000₽ доставка бесплатно'
        }
      ],
      timer: null
    }
  },
  mounted() {
    this.startAutoSlide()
  },
  beforeDestroy() {
    this.stopAutoSlide()
  },
  methods: {
    startAutoSlide() {
      this.timer = setInterval(() => {
        this.currentSlide = (this.currentSlide + 1) % this.slides.length
      }, 5000)
    },
    stopAutoSlide() {
      if (this.timer) clearInterval(this.timer)
    }
  }
}
</script>

<style scoped>
.slider-container {
  position: relative;
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
  overflow: hidden;
  border-radius: 0 0 20px 20px;
  background: var(--bg-primary);
}

/*  АДАПТИВНАЯ ВЫСОТА БЕЗ ЛОМКИ ПРИ ЗУМЕ */
.slider-wrapper {
  position: relative;
  width: 100%;
  /* padding-bottom задаёт пропорцию. 45% ≈ широкоформатный слайдер */
  padding-bottom: 45%; 
  background: var(--bg-card);
}

.slide {
  position: absolute;
  top: 0; left: 0;
  width: 100%; height: 100%;
  opacity: 0;
  transition: opacity 0.8s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}
.slide.active { opacity: 1; }

/* ИЗОБРАЖЕНИЕ ЗАПОЛНЯЕТ КОНТЕЙНЕР, НЕ ЛОМАЕТСЯ ПРИ ЗУМЕ */
.slide-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.slide-overlay {
  position: absolute;
  bottom: 0; left: 0;
  width: 100%;
  padding: 40px 20px 60px;
  background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
  text-align: center;
}

/* ТЕКСТ АДАПТИВНО МЕНЯЕТ РАЗМЕР ПРИ ЗУМЕ */
.slide-title {
  color: var(--text-heading);
  font-size: clamp(1.6rem, 4vw, 3rem);
  margin: 0 0 10px;
  text-shadow: 0 2px 8px rgba(0,0,0,0.5);
}
.slide-subtitle {
  color: var(--text-main);
  font-size: clamp(0.9rem, 2vw, 1.2rem);
  margin: 0 0 25px;
  opacity: 0.9;
}

.slider-btn {
  display: inline-block;
  padding: 12px 32px;
  background: var(--accent-yellow);
  color: var(--bg-primary);
  border-radius: 50px;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.3s;
}
.slider-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 15px var(--accent-yellow-glow);
}

.slider-dots {
  position: absolute;
  bottom: 20px; left: 50%;
  transform: translateX(-50%);
  display: flex; gap: 10px; z-index: 10;
}
.dot {
  width: 10px; height: 10px;
  border-radius: 50%;
  background: rgba(255,255,255,0.4);
  cursor: pointer;
  transition: all 0.3s;
}
.dot.active {
  background: var(--accent-yellow);
  transform: scale(1.3);
}

/* Адаптивность под мобильные */
@media (max-width: 768px) {
  .slider-wrapper { padding-bottom: 60%; }
  .slide-overlay { padding: 30px 15px 50px; }
}
@media (max-width: 480px) {
  .slider-wrapper { padding-bottom: 75%; }
}
</style>