<template>
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-col">
          <h3>ЕШКА</h3>
          <p>Фастфуд ресторан</p>
          <p>Вкусно. Быстро. Горячо.</p>
        </div>
        <div class="footer-col">
          <h3>Контакты</h3>
          <a href="tel:+79991234567">📞 +7 (999) 123-45-67</a>
          <a href="mailto:info@eshka.ru">✉️ info@eshka.ru</a>
          <p>📍 г. [Ваш город], ул. Примерная, 15</p>
        </div>
        <div class="footer-col">
          <h3>Мы в сети</h3>
          <a href="#" target="_blank">📱 ВКонтакте</a>
          <a href="#" target="_blank">✈️ Telegram</a>
          <a href="#" target="_blank">💬 WhatsApp</a>
        </div>
        <div class="footer-col">
          <h3>Работаем</h3>
          <p>Ежедневно 10:00 — 23:00</p>
          <p>Доставка по городу</p>
          <p>Самовывоз: ул. Примерная, 15</p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© 2026 «ЕШКА». Все права защищены.</p>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'FooterComponent'
}
</script>

<style scoped>
.footer {
  background: #6e7da7;
  color: white;
  padding: 40px 0 20px;
  margin-top: 60px;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.footer-content {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 40px;
}

.footer-col h3 {
  color: white;
  margin-bottom: 20px;
  font-size: 20px;
}

.footer-col p, .footer-col a {
  color: #ecf0f1;
  text-decoration: none;
  display: block;
  margin-bottom: 10px;
}

.footer-col a:hover {
  color: #e74c3c;
}

.footer-bottom {
  text-align: center;
  padding-top: 30px;
  border-top: 1px solid rgba(255,255,255,0.1);
  margin-top: 30px;
}

@media (max-width: 768px) {
  .footer-content {
    grid-template-columns: 1fr;
  }
}
</style>