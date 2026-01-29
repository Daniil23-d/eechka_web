<template>
  <div class="partnership">
    <HeaderComponent />
    
    <div class="container">
      <h1>Сотрудничество</h1>
      
      <div class="partnership-content">
        <h2>Вакансии</h2>
        
        <div class="vacancy">
          <h3>Повар</h3>
          <p class="requirements">
            <strong>Требования:</strong><br>
            - Опыт работы от 1 года<br>
            - Знание технологии приготовления фастфуда
          </p>
          <p class="salary">
            <strong>Зарплата:</strong> от 40 000 ₽ (уточняется при собеседовании)
          </p>
        </div>

        <div class="vacancy">
          <h3>Курьер</h3>
          <p class="requirements">
            <strong>Требования:</strong><br>
            - Наличие собственного транспорта (велосипед, скутер)<br>
            - Ответственность и пунктуальность
          </p>
          <p class="salary">
            <strong>Зарплата:</strong> от 35 000 ₽ + %
          </p>
        </div>

        <div class="vacancy">
          <h3>Кассир-продавец</h3>
          <p class="requirements">
            <strong>Требования:</strong><br>
            - Опыт работы в сфере общепита приветствуется
          </p>
          <p class="salary">
            <strong>Зарплата:</strong> от 30 000 ₽
          </p>
        </div>

        <div class="contact-block">
          <h2>Связаться с нами</h2>
          <p>Для трудоустройства звоните:</p>
          <p><strong>📞 +7 (999) 123-45-67</strong></p>
          <p>Или пишите на почту:</p>
          <p><strong>✉️ hr@eshka.ru</strong></p>
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
  name: 'PartnershipView',
  components: {
    HeaderComponent,
    FooterComponent
  }
}
</script>

<style scoped>
.partnership {
  padding: 60px 0;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.partnership h1 {
  text-align: center;
  font-size: 36px;
  margin-bottom: 40px;
  color: #333;
}

.partnership-content {
  background: white;
  padding: 40px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.partnership-content h2 {
  font-size: 28px;
  margin-bottom: 30px;
  color: #333;
  border-bottom: 2px solid #e74c3c;
  padding-bottom: 10px;
}

.vacancy {
  margin-bottom: 30px;
  padding: 20px;
  border-left: 4px solid #e74c3c;
  background: #f8f9fa;
  border-radius: 5px;
}

.vacancy h3 {
  font-size: 22px;
  margin-bottom: 15px;
  color: #e74c3c;
}

.requirements, .salary {
  margin: 10px 0;
  line-height: 1.8;
}

.contact-block {
  margin-top: 40px;
  padding: 30px;
  background: #2c3e50;
  color: white;
  border-radius: 10px;
  text-align: center;
}

.contact-block h2 {
  color: white;
  border-bottom: 2px solid #e74c3c;
}

.contact-block p {
  font-size: 18px;
  margin: 10px 0;
}

.contact-block strong {
  font-size: 22px;
  color: #e74c3c;
}

@media (max-width: 768px) {
  .partnership-content {
    padding: 20px;
  }
}
</style>