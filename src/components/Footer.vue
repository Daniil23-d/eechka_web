<template>
  <footer class="footer">
    <div class="container">
      <div class="footer-content">       
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
  background: #0c0909b6;
  color: rgb(255, 255, 255);
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

.footer-bottom {
  text-align: center;
  padding-top: 30px;
  border-top: 1px solid rgb(255, 255, 255);
  margin-top: 30px;
}

@media (max-width: 768px) {
  .footer-content {
    grid-template-columns: 1fr;
  }
}
</style>