<template>
  <section class="slider">
    <div class="slider-track" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
      <div v-for="(slide, index) in slides" :key="index" class="slide">
        <img :src="getImageUrl(slide.image)" :alt="slide.title">
        <div class="slide-caption">{{ slide.title }}</div>
      </div>
    </div>

    <div class="slider-controls">
      <button class="slider-btn" @click="prevSlide">❮</button>
      <button class="slider-btn" @click="nextSlide">❯</button>
    </div>

    <div class="dots">
      <button 
        v-for="(slide, index) in slides" 
        :key="index"
        class="dot"
        :class="{ active: currentIndex === index }"
        @click="goToSlide(index)"
      ></button>
    </div>
  </section>
</template>

<script>
import api from '../api/axios'

export default {
  name: 'SliderComponent',
  data() {
    return {
      slides: [],
      currentIndex: 0,
      interval: null
    }
  },
  mounted() {
    this.loadSlides()
    this.startAutoPlay()
  },
  beforeUnmount() {
    this.stopAutoPlay()
  },
  methods: {
    async loadSlides() {
      try {
        const response = await api.get('/slides.php')
        this.slides = response.data
      } catch (error) {
        console.error('Ошибка загрузки слайдов:', error)
      }
    },
    getImageUrl(filename) {
      return `http://localhost/eshka/backend/uploads/${filename}`
    },
    goToSlide(index) {
      this.currentIndex = index
    },
    nextSlide() {
      this.currentIndex = (this.currentIndex + 1) % this.slides.length
    },
    prevSlide() {
      this.currentIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length
    },
    startAutoPlay() {
      this.interval = setInterval(() => {
        this.nextSlide()
      }, 5000)
    },
    stopAutoPlay() {
      clearInterval(this.interval)
    }
  }
}
</script>

<style scoped>
.slider {
  margin: 40px 0;
  position: relative;
  overflow: hidden;
  border-radius: 10px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  height: 500px;
}

.slider-track {
  display: flex;
  transition: transform 0.5s ease;
}

.slide {
  min-width: 100%;
  height: 100%;
  position: relative;
}

.slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.slide-caption {
  position: absolute;
  bottom: 20px;
  left: 20px;
  background: rgba(255, 0, 0, 0.7);
  color: rgb(0, 0, 0);
  padding: 10px 20px;
  border-radius: 5px;
  font-size: 18px;
}

.slider-controls {
  position: absolute;
  bottom: 20px;
  right: 20px;
  display: flex;
  gap: 10px;
}

.slider-btn {
  width: 40px;
  height: 40px;
  background: rgba(255,255,255,0.8);
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 18px;
  transition: background 0.3s;
}

.slider-btn:hover {
  background: white;
}

.dots {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
}

.dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: rgba(255,255,255,0.5);
  border: none;
  cursor: pointer;
  transition: background 0.3s;
}

.dot.active {
  background: white;
}

@media (max-width: 768px) {
  .slider {
    height: 300px;
  }
}
</style>