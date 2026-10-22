<template>
  <div class="dish-card">
    <img :src="getImageUrl(dish.image)" :alt="dish.name">
    <div class="dish-content">
      <h3>{{ dish.name }}</h3>
      <p class="dish-description">{{ dish.description }}</p>
      <p class="dish-price">{{ dish.price }} ₽</p>
      <router-link :to="`/dish/${dish.id}`" class="dish-btn">
        Подробнее
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DishCard',
  props: {
    dish: {
      type: Object,
      required: true
    }
  },
  methods: {
    getImageUrl(filename) {
      return `http://localhost/eshka/backend/uploads/${filename}`
    }
  }
}
</script>

<style scoped>
.dish-card {
  background: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  transition: transform 0.3s;
}

.dish-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 20px rgba(0,0,0,0.15);
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
  line-height: 1.6;
}

.dish-price {
  font-size: 24px;
  font-weight: bold;
  color: #e74c3c;
  margin-bottom: 15px;
}

.dish-btn {
  display: inline-block;
  padding: 10px 20px;
  background: #e74c3c;
  color: white;
  text-decoration: none;
  border-radius: 5px;
  font-weight: bold;
  transition: background 0.3s;
}

.dish-btn:hover {
  background: #c0392b;
}
</style>