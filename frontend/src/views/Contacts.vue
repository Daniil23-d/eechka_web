<template>
  <div class="contacts">
    <HeaderComponent />
    
    <div class="container">
      <h1>Контакты</h1>
      
      <div class="contacts-grid">
        <div class="contact-info">
          <h2>Наш адрес</h2>
          <p>📍 г. [Ваш город], ул. Примерная, 15</p>
          
          <h2>Телефон</h2>
          <p>📞 <a href="tel:+79991234567">+7 (999) 123-45-67</a></p>
          
          <h2>Email</h2>
          <p>✉️ <a href="mailto:info@eshka.ru">info@eshka.ru</a></p>
          
          <h2>Режим работы</h2>
          <p>🕒 Ежедневно с 10:00 до 23:00</p>
          <p>Без выходных</p>
        </div>
        
        <div class="contact-map">
          <h2>На карте</h2>
          <div class="map-placeholder">
            <p>Карта будет здесь</p>
            <p>(интеграция с Яндекс.Картами или 2GIS)</p>
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

export default {
  name: 'ContactsView',
  components: {
    HeaderComponent,
    FooterComponent
  }
}
</script>

<style scoped>
.contacts {
  padding: 60px 0;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.contacts h1 {
  text-align: center;
  font-size: 36px;
  margin-bottom: 40px;
  color: #333;
}

.contacts-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
}

.contact-info h2 {
  font-size: 24px;
  margin: 25px 0 15px;
  color: #333;
}

.contact-info p {
  font-size: 18px;
  margin-bottom: 10px;
}

.contact-info a {
  color: #e74c3c;
  text-decoration: none;
}

.contact-info a:hover {
  text-decoration: underline;
}

.contact-map h2 {
  font-size: 24px;
  margin-bottom: 20px;
  color: #333;
}

.map-placeholder {
  background: #f8f9fa;
  padding: 40px;
  border-radius: 10px;
  text-align: center;
}

.map-placeholder p {
  margin: 10px 0;
  color: #666;
}

@media (max-width: 768px) {
  .contacts-grid {
    grid-template-columns: 1fr;
  }
}
</style>