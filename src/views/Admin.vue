<template>
  <div class="admin-page">
    <HeaderComponent />
    
    <div class="admin-container">
      <div class="content-section" style="background: linear-gradient(135deg, #1e293b, #1a2233);">
        <div class="admin-header">
          <h1>Админ-панель ЕШКА</h1>
          <div class="admin-info">
            <span>{{ adminUsername }}</span>
            <button @click="logout" class="logout-btn">Выйти</button>
          </div>
        </div>
        
        <div class="admin-tabs">
          <button @click="activeTab = 'dishes'" :class="{ active: activeTab === 'dishes' }">
             Блюда ({{ dishes.length }})
          </button>
          <button @click="activeTab = 'categories'" :class="{ active: activeTab === 'categories' }">
             Категории ({{ categories.length }})
          </button>
        </div>
        
        <!-- Вкладка: Блюда -->
        <div v-if="activeTab === 'dishes'" class="admin-content">
          <h2>Управление блюдами</h2>
          
          <div class="search-filter">
            <input type="text" v-model="searchDish" placeholder="Поиск блюд..." class="search-input">
            <select v-model="filterCategory" class="filter-select">
              <option value="">Все категории</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
            <button @click="showAddDishModal = true" class="add-btn">+ Добавить блюдо</button>
          </div>
          
          <div class="dishes-list">
            <div v-for="dish in filteredDishes" :key="dish.id" class="dish-card">
              <div class="dish-header">
                <strong class="dish-name">{{ dish.name }}</strong>
                <span class="dish-price">{{ dish.price }} ₽</span>
              </div>
              <p class="dish-category">{{ getCategoryName(dish.category_id) }}</p>
              <p class="dish-description">{{ dish.description }}</p>
              <div class="dish-actions">
                <button @click="editDish(dish)" class="edit-btn"> Редактировать</button>
                <button @click="deleteDish(dish.id)" class="delete-btn"> Удалить</button>
              </div>
            </div>
          </div>
          
          <p v-if="filteredDishes.length === 0" class="empty-message">Блюда не найдены</p>
        </div>
        
        <!-- Вкладка: Категории -->
        <div v-if="activeTab === 'categories'" class="admin-content">
          <h2>Управление категориями</h2>
          
          <div class="categories-header">
            <button @click="showAddCategoryModal = true" class="add-btn">+ Добавить категорию</button>
          </div>
          
          <div class="categories-list">
            <div v-for="category in categories" :key="category.id" class="category-card">
              <div class="category-header">
                <strong class="category-name">{{ category.name }}</strong>
                <span class="category-count">{{ getCategoryDishCount(category.id) }} блюд</span>
              </div>
              <p class="category-slug">/{{ category.slug }}</p>
              <p class="category-description">{{ category.description }}</p>
              <div class="category-actions">
                <button @click="editCategory(category)" class="edit-btn"> Редактировать</button>
                <button @click="deleteCategory(category.id)" class="delete-btn"> Удалить</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <transition name="modal">
      <div v-if="showAddDishModal" class="modal-overlay" @click.self="closeModals">
        <div class="modal">
          <div class="modal-header">
            <h3>{{ editingDish ? 'Редактировать блюдо' : 'Добавить блюдо' }}</h3>
            <button @click="closeModals" class="close-modal">×</button>
          </div>
          
          <div class="modal-body">
            <div class="form-group">
              <label>Название блюда:</label>
              <input type="text" v-model="dishForm.name" required>
            </div>
            
            <div class="form-group">
              <label>Категория:</label>
              <select v-model="dishForm.category_id" required>
                <option value="">Выберите категорию</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
              </select>
            </div>
            
            <div class="form-group">
              <label>Описание:</label>
              <textarea v-model="dishForm.description" rows="3"></textarea>
            </div>
            
            <div class="form-group">
              <label>Цена (₽):</label>
              <input type="number" v-model.number="dishForm.price" min="0" step="0.01" required>
            </div>
            

            <div class="form-group">
              <label>Фото блюда:</label>
              <input type="file" @change="e => handleImageSelect(e, 'dish')" accept="image/*">
              <div v-if="imagePreview.dish" class="img-preview-box">
                <img :src="imagePreview.dish" alt="Preview">
                <button @click="clearImage('dish')" class="remove-img-btn">×</button>
              </div>
            </div>
          </div>
          
          <div class="modal-footer">
            <button @click="editingDish ? updateDish() : addDish()" class="primary-btn">
              {{ editingDish ? 'Сохранить' : 'Добавить' }}
            </button>
            <button @click="closeModals" class="secondary-btn">Отмена</button>
          </div>
        </div>
      </div>
    </transition>
    
    <!-- Модальное окно: Категории -->
    <transition name="modal">
      <div v-if="showAddCategoryModal" class="modal-overlay" @click.self="closeModals">
        <div class="modal">
          <div class="modal-header">
            <h3>{{ editingCategory ? 'Редактировать категорию' : 'Добавить категорию' }}</h3>
            <button @click="closeModals" class="close-modal">×</button>
          </div>
          
          <div class="modal-body">
            <div class="form-group">
              <label>Название категории:</label>
              <input type="text" v-model="categoryForm.name" required>
            </div>
            
            <div class="form-group">
              <label>URL (slug):</label>
              <input type="text" v-model="categoryForm.slug" placeholder="napitki" required>
            </div>
            
            <div class="form-group">
              <label>Описание:</label>
              <textarea v-model="categoryForm.description" rows="2"></textarea>
            </div>
            
            <!--  ЗАГРУЗКА ФОТО ДЛЯ КАТЕГОРИИ -->
            <div class="form-group">
              <label>Фото категории:</label>
              <input type="file" @change="e => handleImageSelect(e, 'category')" accept="image/*">
              <div v-if="imagePreview.category" class="img-preview-box">
                <img :src="imagePreview.category" alt="Preview">
                <button @click="clearImage('category')" class="remove-img-btn">×</button>
              </div>
            </div>
            
            <div class="form-group">
              <label>
                <input type="checkbox" v-model="categoryForm.show_on_homepage" true-value="1" false-value="0">
                Показывать на главной странице
              </label>
            </div>
          </div>
          
          <div class="modal-footer">
            <button @click="editingCategory ? updateCategory() : addCategory()" class="primary-btn">
              {{ editingCategory ? 'Сохранить' : 'Добавить' }}
            </button>
            <button @click="closeModals" class="secondary-btn">Отмена</button>
          </div>
        </div>
      </div>
    </transition>
    
    <FooterComponent />
  </div>
</template>

<script>
import HeaderComponent from '../components/Header.vue'
import FooterComponent from '../components/Footer.vue'
import api from '../api/axios'

export default {
  name: 'AdminPage',
  components: { HeaderComponent, FooterComponent },
  data() {
    return {
      activeTab: 'dishes',
      adminUsername: localStorage.getItem('admin_username') || 'Админ',

      imagePreview: { dish: null, category: null },
      selectedFiles: { dish: null, category: null },
      
      dishes: [],
      categories: [],
      searchDish: '',
      filterCategory: '',
      showAddDishModal: false,
      editingDish: null,
      dishForm: { name: '', category_id: '', description: '', price: 0 },
      
      showAddCategoryModal: false,
      editingCategory: null,
      categoryForm: { name: '', slug: '', description: '', show_on_homepage: '1' }
    }
  },
  computed: {
    filteredDishes() {
      return this.dishes.filter(dish => {
        const matchesSearch = !this.searchDish || 
          dish.name.toLowerCase().includes(this.searchDish.toLowerCase()) ||
          dish.description.toLowerCase().includes(this.searchDish.toLowerCase())
        const matchesCategory = !this.filterCategory || dish.category_id == this.filterCategory
        return matchesSearch && matchesCategory
      })
    }
  },
  async mounted() {
  if (!localStorage.getItem('admin_logged_in')) {
    this.$router.push('/')
    return
  }
  this.adminUsername = localStorage.getItem('admin_username') || 'Админ'
  
  await this.loadDishes()
  await this.loadCategories()
},
  methods: {
    handleImageSelect(e, type) {
      const file = e.target.files[0]
      if (!file) return
      if (file.size > 5 * 1024 * 1024) {
        alert('Файл слишком большой! Макс. 5МБ')
        e.target.value = ''
        return
      }
      this.selectedFiles[type] = file
      this.imagePreview[type] = URL.createObjectURL(file)
    },
    
    clearImage(type) {
      this.selectedFiles[type] = null
      this.imagePreview[type] = null
    },
    
    //  РЕДАКТИРОВАНИЕ С ПРЕВЬЮ
    editDish(dish) {
      this.editingDish = dish
      this.dishForm = { name: dish.name, category_id: dish.category_id, description: dish.description, price: dish.price }
      this.clearImage('dish')
      if (dish.image) {
        this.imagePreview.dish = dish.image.startsWith('/') ? dish.image : `/eshka/backend/uploads/${dish.image}`
      }
      this.showAddDishModal = true
    },
    
    editCategory(category) {
      this.editingCategory = category
      this.categoryForm = {
        name: category.name,
        slug: category.slug,
        description: category.description || '',
        show_on_homepage: category.show_on_homepage?.toString() || '1'
      }
      this.clearImage('category')
      if (category.image) {
        this.imagePreview.category = category.image.startsWith('/') ? category.image : `/eshka/backend/uploads/${category.image}`
      }
      this.showAddCategoryModal = true
    },
    
    //  ЗАКРЫТИЕ МОДАЛОК С ОЧИСТКОЙ
    closeModals() {
      this.showAddDishModal = false
      this.showAddCategoryModal = false
      this.editingDish = null
      this.editingCategory = null
      this.dishForm = { name: '', category_id: '', description: '', price: 0 }
      this.categoryForm = { name: '', slug: '', description: '', show_on_homepage: '1' }
      this.clearImage('dish')
      this.clearImage('category')
    },

    async loadDishes() {
      try { this.dishes = (await api.get('/menu.php')).data } catch (e) { console.error(e) }
    },
    async loadCategories() {
      try { this.categories = (await api.get('/categories.php')).data } catch (e) { console.error(e) }
    },
    
    getCategoryName(id) {
      return this.categories.find(c => c.id == id)?.name || 'Неизвестная категория'
    },
    
    getCategoryDishCount(id) {
      return this.dishes.filter(d => d.category_id == id).length
    },

    async addDish() {
      if (!this.dishForm.name || !this.dishForm.category_id) {
        this.showMessage('Заполните название и категорию', 'error')
        return
      }
      const formData = new FormData()
      formData.append('name', this.dishForm.name)
      formData.append('category_id', this.dishForm.category_id)
      formData.append('description', this.dishForm.description || '')
      formData.append('price', this.dishForm.price)
      if (this.selectedFiles.dish) formData.append('image', this.selectedFiles.dish)
      
      try {
        const res = await fetch('https://olivine-zircon23547.my-vm.work/eshka/backend/api/add-dish.php', { method: 'POST', body: formData })
        const result = await res.json()
        if (result.success) {
          this.showMessage('Блюдо добавлено!', 'success')
          this.closeModals()
          await this.loadDishes()
        } else { this.showMessage(' ' + result.message, 'error') }
      } catch (e) { console.error(e); this.showMessage(' Ошибка сервера', 'error') }
    },
    
    async updateDish() {
      if (!this.dishForm.name || !this.dishForm.category_id) {
        this.showMessage('Заполните название и категорию', 'error')
        return
      }
      const formData = new FormData()
      formData.append('id', this.editingDish.id)
      formData.append('name', this.dishForm.name)
      formData.append('category_id', this.dishForm.category_id)
      formData.append('description', this.dishForm.description || '')
      formData.append('price', this.dishForm.price)
      formData.append('old_image', this.editingDish.image || 'default-dish.png')
      if (this.selectedFiles.dish) formData.append('image', this.selectedFiles.dish)
      
      try {
        const res = await fetch('https://olivine-zircon23547.my-vm.work/eshka/backend/api/update-dish.php', { method: 'POST', body: formData })
        const result = await res.json()
        if (result.success) {
          this.showMessage(' Блюдо обновлено!', 'success')
          this.closeModals()
          await this.loadDishes()
        } else { this.showMessage(' ' + result.message, 'error') }
      } catch (e) { console.error(e); this.showMessage(' Ошибка сервера', 'error') }
    },
    
    async deleteDish(id) {
      if (!confirm('Удалить блюдо?')) return
      try {
        const res = await fetch(`https://olivine-zircon23547.my-vm.work/eshka/backend/api/delete-dish.php?id=${id}`, { method: 'DELETE' })
        const result = await res.json()
        if (result.success) { this.showMessage(' Удалено', 'success'); await this.loadDishes() }
        else { this.showMessage(' ' + result.message, 'error') }
      } catch (e) { console.error(e); this.showMessage(' Ошибка', 'error') }
    },
    
    //  КАТЕГОРИИ: ДОБАВЛЕНИЕ/ОБНОВЛЕНИЕ С ФОТО
    async addCategory() {
      if (!this.categoryForm.name || !this.categoryForm.slug) {
        this.showMessage('Заполните название и slug', 'error')
        return
      }
      const formData = new FormData()
      formData.append('name', this.categoryForm.name)
      formData.append('slug', this.categoryForm.slug)
      formData.append('description', this.categoryForm.description || '')
      formData.append('show_on_homepage', this.categoryForm.show_on_homepage)
      if (this.selectedFiles.category) formData.append('image', this.selectedFiles.category)
      
      try {
        const res = await fetch('https://olivine-zircon23547.my-vm.work/eshka/backend/api/add-category.php', { method: 'POST', body: formData })
        const result = await res.json()
        if (result.success) {
          this.showMessage(' Категория добавлена!', 'success')
          this.closeModals()
          await this.loadCategories()
          await this.loadDishes()
        } else { this.showMessage(' ' + result.message, 'error') }
      } catch (e) { console.error(e); this.showMessage(' Ошибка', 'error') }
    },
    
    async updateCategory() {
      if (!this.categoryForm.name || !this.categoryForm.slug) {
        this.showMessage('Заполните название и slug', 'error')
        return
      }
      const formData = new FormData()
      formData.append('id', this.editingCategory.id)
      formData.append('name', this.categoryForm.name)
      formData.append('slug', this.categoryForm.slug)
      formData.append('description', this.categoryForm.description || '')
      formData.append('show_on_homepage', this.categoryForm.show_on_homepage)
      formData.append('old_image', this.editingCategory.image || 'default-category.png')
      if (this.selectedFiles.category) formData.append('image', this.selectedFiles.category)
      
      try {
        const res = await fetch('https://olivine-zircon23547.my-vm.work/eshka/backend/api/update-category.php', { method: 'POST', body: formData })
        const result = await res.json()
        if (result.success) {
          this.showMessage(' Категория обновлена!', 'success')
          this.closeModals()
          await this.loadCategories()
        } else { this.showMessage(' ' + result.message, 'error') }
      } catch (e) { console.error(e); this.showMessage(' Ошибка', 'error') }
    },
    
    async deleteCategory(id) {
      if (!confirm('Удалить категорию? Все блюда в ней будут удалены!')) return
      try {
        const res = await fetch(`https://olivine-zircon23547.my-vm.work/eshka/backend/api/delete-category.php?id=${id}`, { method: 'DELETE' })
        const result = await res.json()
        if (result.success) {
          this.showMessage(' Категория удалена', 'success')
          await this.loadCategories()
          await this.loadDishes()
        } else { this.showMessage(' ' + result.message, 'error') }
      } catch (e) { console.error(e); this.showMessage(' Ошибка', 'error') }
    },
    
    showMessage(text) { alert(text) },
    
    logout() {
      localStorage.removeItem('admin_logged_in')
      localStorage.removeItem('admin_username')
      this.$router.push('/')
      alert('Вы вышли')
    }
  }
}
</script>

<style scoped>
.admin-page { background: var(--bg-primary); min-height: 100vh; }
.admin-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
.content-section { border-radius: 20px; padding: 30px; margin: 20px 0; }

.admin-header {
  display: flex; justify-content: space-between; align-items: center;
  margin-bottom: 30px; padding: 20px;
  background: var(--bg-card); border: 1px solid var(--border-subtle);
  border-radius: 16px;
}
.admin-header h1 { margin: 0; color: var(--text-heading); font-size: 28px; }
.admin-info { display: flex; align-items: center; gap: 15px; }
.admin-info span { color: var(--text-main); font-weight: 600; }

.logout-btn {
  padding: 10px 20px; background: var(--accent-yellow); color: var(--bg-primary);
  border: none; border-radius: 8px; cursor: pointer; font-weight: 600;
  transition: all 0.3s;
}
.logout-btn:hover { background: var(--accent-yellow-hover); transform: translateY(-2px); }

.admin-tabs { display: flex; gap: 10px; margin-bottom: 30px; flex-wrap: wrap; }
.admin-tabs button {
  padding: 12px 24px; background: var(--bg-card); color: var(--text-main);
  border: 2px solid var(--border-subtle); border-radius: 8px;
  cursor: pointer; font-size: 16px; font-weight: 600; transition: all 0.3s;
}
.admin-tabs button:hover { border-color: var(--accent-yellow); color: var(--accent-yellow); }
.admin-tabs button.active {
  background: var(--accent-yellow); color: var(--bg-primary); border-color: var(--accent-yellow);
}

.admin-content {
  background: var(--bg-card); padding: 30px; border-radius: 16px;
  border: 1px solid var(--border-subtle);
}
.admin-content h2 { color: var(--text-heading); margin: 0 0 20px; font-size: 24px; }

.search-filter { display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; }
.search-input, .filter-select {
  padding: 10px 15px; border: 1px solid var(--border-subtle);
  border-radius: 8px; font-size: 15px; background: var(--bg-secondary);
  color: var(--text-main);
}
.search-input { flex: 1; min-width: 200px; }
.filter-select { min-width: 150px; }

.add-btn {
  padding: 10px 20px; background: var(--accent-yellow); color: var(--bg-primary);
  border: none; border-radius: 8px; font-size: 16px; font-weight: 600;
  cursor: pointer; transition: all 0.3s;
}
.add-btn:hover { background: var(--accent-yellow-hover); transform: translateY(-2px); }

/* Сетки карточек */
.dishes-list, .categories-list {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 20px; align-items: stretch;
}

/* Карточка блюда */
.dish-card {
  display: flex; flex-direction: column; background: var(--bg-secondary);
  padding: 20px; border-radius: 12px; border: 1px solid var(--border-subtle);
  height: 220px; transition: border-color 0.3s;
}
.dish-card:hover { border-color: var(--accent-yellow); }
.dish-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
.dish-name { font-size: 16px; color: var(--text-heading); line-height: 1.3; min-height: 42px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.dish-price { color: var(--accent-yellow); font-weight: bold; font-size: 18px; white-space: nowrap; margin-left: 10px; }
.dish-category { color: var(--text-muted); font-size: 13px; margin: 0 0 10px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
.dish-description { color: var(--text-muted); font-size: 14px; margin: 0 0 15px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 39px; }
.dish-actions { display: flex; gap: 10px; margin-top: auto; padding-top: 12px; border-top: 1px solid var(--border-subtle); }

/* Карточка категории */
.category-card {
  display: flex; flex-direction: column; background: var(--bg-secondary);
  padding: 20px; border-radius: 12px; border: 1px solid var(--border-subtle);
  height: 220px;
}
.category-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
.category-name { font-size: 16px; color: var(--text-heading); line-height: 1.3; min-height: 42px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.category-count { color: var(--accent-yellow); font-size: 14px; white-space: nowrap; margin-left: 10px; }
.category-slug { color: var(--text-muted); font-size: 13px; margin: 0 0 6px; }
.category-description { color: var(--text-muted); font-size: 14px; margin: 0 0 15px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 39px; }
.category-actions { display: flex; gap: 10px; margin-top: auto; padding-top: 12px; border-top: 1px solid var(--border-subtle); }

/* Кнопки действий */
.edit-btn, .delete-btn {
  flex: 1; padding: 8px; border: none; border-radius: 5px;
  cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.2s;
}
.edit-btn { background: var(--accent-yellow); color: var(--bg-primary); }
.edit-btn:hover { background: var(--accent-yellow-hover); }
.delete-btn { background: #ef4444; color: white; }
.delete-btn:hover { background: #dc2626; }

.empty-message { text-align: center; color: var(--text-muted); font-size: 18px; padding: 40px; }

/*  ПРЕВЬЮ ИЗОБРАЖЕНИЯ В МОДАЛКЕ */
.img-preview-box {
  position: relative; margin-top: 10px; width: 100px; height: 100px;
  border-radius: 12px; overflow: hidden; border: 2px solid var(--border-subtle);
  background: var(--bg-secondary);
}
.img-preview-box img { width: 100%; height: 100%; object-fit: cover; display: block; }
.remove-img-btn {
  position: absolute; top: 4px; right: 4px; width: 22px; height: 22px;
  background: #3d3d3d; color: white; border: none; border-radius: 50%;
  font-size: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center;
  transition: transform 0.2s;
}
.remove-img-btn:hover { transform: scale(1.15); }

/* Модальные окна */
.modal-overlay {
  position: fixed; top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.7); display: flex; align-items: center; justify-content: center; z-index: 2000;
}
.modal {
  background: var(--bg-card); border-radius: 20px; max-width: 600px; width: 90%;
  max-height: 90vh; overflow-y: auto; border: 1px solid var(--border-subtle);
}
.modal-header {
  padding: 20px; border-bottom: 1px solid var(--border-subtle);
  display: flex; justify-content: space-between; align-items: center;
}
.modal-header h3 { margin: 0; color: var(--text-heading); font-size: 24px; }
.close-modal {
  width: 40px; height: 40px; background: transparent; color: var(--text-muted);
  border: none; border-radius: 50%; font-size: 28px; cursor: pointer;
  display: flex; align-items: center; justify-content: center; transition: color 0.2s;
}

.modal-body { padding: 20px; }
.modal-footer {
  padding: 20px; border-top: 1px solid var(--border-subtle);
  display: flex; gap: 15px;
}

.form-group { margin-bottom: 15px; }
.form-group label { display: block; margin-bottom: 6px; font-weight: 500; color: var(--text-main); }
.form-group input, .form-group textarea, .form-group select {
  width: 100%; padding: 10px 12px; border: 1px solid var(--border-subtle);
  border-radius: 8px; font-size: 15px; background: var(--bg-secondary);
  color: var(--text-main);
}
.form-group input:focus, .form-group textarea:focus, .form-group select:focus {
  outline: none; border-color: var(--accent-yellow); box-shadow: 0 0 0 2px var(--accent-yellow-glow);
}

.modal-enter-active, .modal-leave-active { transition: opacity 0.3s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }

/* Адаптивность */
@media (max-width: 768px) {
  .admin-tabs { flex-direction: column; }
  .search-filter { flex-direction: column; }
  .search-input, .filter-select { width: 100%; }
  .dishes-list, .categories-list { grid-template-columns: 1fr; }
  .dish-card, .category-card { height: 240px; }
  .modal { width: 95%; }
}
@media (max-width: 480px) {
  .dish-card, .category-card { height: 260px; padding: 15px; }
  .dish-header, .category-header { flex-direction: column; align-items: flex-start; gap: 5px; }
  .dish-price, .category-count { margin-left: 0; }
  .dish-actions, .category-actions { flex-direction: column; }
}
</style>