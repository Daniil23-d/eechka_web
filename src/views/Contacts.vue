<template>
  <div class="contacts">
    <HeaderComponent />
    
    <div class="container">
      <h1>Контакты</h1>
      
      <div class="contacts-grid">
        <div class="contact-info">
          <h2>Наш адрес</h2>
          <p> г. Буй, ул. Красной армии, д. 10 (стеклянный павильон напротив "Плюшек ватрушек")</p>
          
          <h2>Телефон</h2>
          <p> <a href="tel:+79991234567">8-906-666-23-05</a></p>
          
          <h2>VK</h2>
          <p><a href="mailto:info@eshka.ru">eshberibuy</a></p>
          
          <h2>Режим работы</h2>
          <p> Работаем для вас ВС-ЧТ с 10:00 до 22:00, ПТ-СБ с 10:00 до 23:00</p>
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
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  padding-top: 0; 
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 100px 20px 60px; 
  flex: 1; 
}

.contacts h1 {
  text-align: center;
  font-size: 36px;
  margin-top: 0;
  margin-bottom: 40px;
  color: var(--text-heading);
}

.contacts-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
}

.contact-info h2 {
  font-size: 24px;
  margin: 25px 0 15px;
  color: var(--text-heading);
}

.contact-info p {
  font-size: 18px;
  margin-bottom: 10px;
  color: var(--text-muted);
}

.contact-info a {
  color: var(--accent-yellow);
  text-decoration: none;
}

.contact-info a:hover {
  text-decoration: underline;
}

@media (max-width: 768px) {
  .container { padding-top: 90px; }
  .contacts-grid { grid-template-columns: 1fr; }
  .contacts h1 { font-size: 28px; }
}
</style>