<template>
  <div class="contact-page-container">
    <Navbar />

    <div class="page-header">
      <h1>Contactez-nous</h1>
      <p>Nous sommes là pour vous aider</p>
    </div>

    <div class="contact-container">
      <div class="contact-info">
        <h2>Nos coordonnées</h2>
        <p>Une question, une suggestion ou vous souhaitez rejoindre notre communauté ? N'hésitez pas à nous contacter.</p>

        <div class="info-item">
          <div class="icon">@</div>
          <div class="text">
            <h4>Email</h4>
            <p>contact@beauteeshop.ma</p>
          </div>
        </div>

        <div class="info-item">
          <div class="icon">☏</div>
          <div class="text">
            <h4>Téléphone</h4>
            <p>+212 6 00 00 00 00</p>
          </div>
        </div>

        <div class="info-item">
          <div class="icon">🕒</div>
          <div class="text">
            <h4>Horaires</h4>
            <p>Lun - Ven : 9h00 - 18h00</p>
          </div>
        </div>

        <div class="info-item">
          <div class="icon">📍</div>
          <div class="text">
            <h4>Adresse</h4>
            <p>Maroc</p>
          </div>
        </div>
      </div>

      <div class="form-container">
        <h2>Envoyer un message</h2>

        <form @submit.prevent="envoyer" class="contact-form" novalidate>
          <div class="form-group" :class="{ 'has-error': errors.nomComplet }">
            <label for="nomComplet">Nom complet</label>
            <input 
              id="nomComplet"
              type="text" 
              v-model="form.nomComplet" 
              placeholder="Votre nom"
              @input="validerChamp('nomComplet')"
            />
            <span v-if="errors.nomComplet" class="error-text">{{ errors.nomComplet }}</span>
          </div>

          <div class="form-group" :class="{ 'has-error': errors.email }">
            <label for="email">Email</label>
            <input 
              id="email"
              type="email" 
              v-model="form.email" 
              placeholder="Votre email"
              @input="validerChamp('email')"
            />
            <span v-if="errors.email" class="error-text">{{ errors.email }}</span>
          </div>

          <div class="form-group" :class="{ 'has-error': errors.sujet }">
            <label for="sujet">Sujet</label>
            <select 
              id="sujet" 
              v-model="form.sujet"
              @change="validerChamp('sujet')"
            >
              <option value="">Choisir un sujet...</option>
              <option>Question sur un produit</option>
              <option>Devenir vendeuse</option>
              <option>Problème de commande</option>
              <option>Suggestion</option>
              <option>Autre</option>
            </select>
            <span v-if="errors.sujet" class="error-text">{{ errors.sujet }}</span>
          </div>

          <div class="form-group" :class="{ 'has-error': errors.message }">
            <label for="message">Message</label>
            <textarea 
              id="message" 
              v-model="form.message" 
              placeholder="Écrivez votre message ici..."
              @input="validerChamp('message')"
            ></textarea>
            <span v-if="errors.message" class="error-text">{{ errors.message }}</span>
          </div>

          <button type="submit" class="btn-submit">Envoyer le message</button>
        </form>

        <Transition name="fade-slide">
          <div v-if="afficherSucces" class="success-msg">
            Message envoyé avec succès ! Nous vous répondrons bientôt.
          </div>
        </Transition>
      </div>
    </div>

    <Footer />
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import Navbar from '../components/Navbar.vue'
import Footer from '../components/Footer.vue'

const formInitial = {
  nomComplet: '',
  email: '',
  sujet: '',
  message: ''
}

const form = ref({ ...formInitial })
const errors = reactive({
  nomComplet: '',
  email: '',
  sujet: '',
  message: ''
})

const afficherSucces = ref(false)

const validerEmail = (email) => {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return re.test(email)
}

const validerChamp = (champ) => {
  if (champ === 'nomComplet') {
    errors.nomComplet = form.value.nomComplet.trim() ? '' : 'Le nom complet est requis'
  } else if (champ === 'email') {
    if (!form.value.email.trim()) {
      errors.email = "L'adresse email est requise"
    } else if (!validerEmail(form.value.email)) {
      errors.email = "L'adresse email n'est pas valide"
    } else {
      errors.email = ''
    }
  } else if (champ === 'sujet') {
    errors.sujet = form.value.sujet ? '' : 'Veuillez choisir un sujet'
  } else if (champ === 'message') {
    errors.message = form.value.message.trim() ? '' : 'Le message est requis'
  }
}

const validerFormulaire = () => {
  validerChamp('nomComplet')
  validerChamp('email')
  validerChamp('sujet')
  validerChamp('message')

  return !Object.values(errors).some(error => error !== '')
}

const resetForm = () => {
  form.value = { ...formInitial }
  Object.keys(errors).forEach(key => errors[key] = '')
}

const envoyer = () => {
  if (validerFormulaire()) {
    afficherSucces.value = true
    resetForm()
    setTimeout(() => {
      afficherSucces.value = false
    }, 4500)
  }
}
</script>

<style scoped>
.contact-page-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background-color: #fff5f8;
}

.page-header {
  background: linear-gradient(135deg, #8b1a4a, #c2185b);
  color: white;
  text-align: center;
  padding: 60px 20px;
}

.page-header h1 {
  font-size: 40px;
  margin-bottom: 10px;
  letter-spacing: 2px;
  font-family: 'Georgia', serif;
}

.page-header p {
  font-size: 17px;
  opacity: 0.9;
  font-family: 'Georgia', serif;
}

.contact-container {
  display: flex;
  gap: 40px;
  max-width: 1000px;
  width: 90%;
  margin: 60px auto;
  box-sizing: border-box;
  flex-wrap: wrap;
  flex-grow: 1;
}

.contact-info {
  flex: 1;
  min-width: 280px;
}

.contact-info h2 {
  color: #8b1a4a;
  font-size: 24px;
  margin-bottom: 20px;
  font-family: 'Georgia', serif;
}

.contact-info p {
  font-size: 15px;
  color: #555;
  line-height: 1.8;
  margin-bottom: 30px;
  font-family: 'Georgia', serif;
}

.info-item {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 20px;
  padding: 15px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 3px 15px rgba(139, 26, 74, 0.1);
  transition: transform 0.3s;
}

.info-item:hover {
  transform: translateY(-3px);
}

.info-item .icon {
  width: 45px;
  height: 45px;
  background: linear-gradient(135deg, #8b1a4a, #c2185b);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 18px;
  flex-shrink: 0;
}

.info-item .text h4 {
  font-size: 14px;
  color: #8b1a4a;
  margin-bottom: 3px;
  font-family: 'Georgia', serif;
}

.info-item .text p {
  font-size: 14px;
  color: #555;
  margin: 0;
  font-family: 'Georgia', serif;
}

.form-container {
  flex: 1.5;
  min-width: 320px;
  background: white;
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 6px 30px rgba(139, 26, 74, 0.15);
  box-sizing: border-box;
}

.form-container h2 {
  color: #8b1a4a;
  font-size: 24px;
  margin-bottom: 25px;
  font-family: 'Georgia', serif;
}

.contact-form {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 7px;
}

.form-group label {
  font-size: 14px;
  color: #555;
  font-weight: bold;
  font-family: 'Georgia', serif;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 12px 15px;
  border: 1px solid #e0c0cc;
  border-radius: 10px;
  font-size: 15px;
  font-family: 'Georgia', serif;
  color: #333;
  outline: none;
  background-color: white;
  transition: border-color 0.3s, box-shadow 0.3s;
  box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #c2185b;
  box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.15);
}

.form-group textarea {
  height: 130px;
  resize: vertical;
}

.btn-submit {
  width: 100%;
  padding: 14px;
  background: linear-gradient(135deg, #8b1a4a, #c2185b);
  color: white;
  border: none;
  border-radius: 10px;
  font-size: 16px;
  cursor: pointer;
  font-family: 'Georgia', serif;
  font-weight: bold;
  letter-spacing: 1px;
  transition: opacity 0.3s, transform 0.2s;
  margin-top: 5px;
}

.btn-submit:hover {
  opacity: 0.9;
  transform: translateY(-2px);
}

.btn-submit:active {
  transform: translateY(0);
}

/* Error states */
.has-error input,
.has-error select,
.has-error textarea {
  border-color: #ff3366 !important;
  background-color: #fffafb;
}

.error-text {
  font-size: 12px;
  color: #ff3366;
  font-family: 'Georgia', serif;
  margin-top: -2px;
  text-align: left;
}

/* Success Message */
.success-msg {
  background: #e8f5e9;
  color: #2e7d32;
  padding: 15px;
  border-radius: 10px;
  text-align: center;
  font-size: 15px;
  margin-top: 15px;
  font-family: 'Georgia', serif;
  box-shadow: 0 4px 12px rgba(46, 125, 50, 0.1);
}

/* Transitions */
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.4s ease;
}

.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(10px);
}

.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

@media (max-width: 768px) {
  .page-header {
    padding: 40px 15px;
  }

  .page-header h1 {
    font-size: 30px;
  }

  .contact-container {
    margin: 30px auto;
    gap: 30px;
  }

  .form-container {
    padding: 30px 20px;
  }
}
</style>

